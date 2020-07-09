<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Entity\PostLike;
use App\Entity\SearchPost;
use App\Entity\PostComment;
use App\Form\SearchPostType;
use App\Form\PostCommentType;
use App\Repository\PostRepository;
use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostCommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="local-posts")
     */
    public function localPosts(PostRepository $repository, Request $request, EntityManagerInterface $manager)
    {   
        $ville = $this->getUser()->getVille();

        if($ville){
            $posts = $repository->findLocalPosts($ville);
        }else{
            $posts = $repository->findAll(['postedAt' => 'DESC']);
        }
        
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post->setPostedBy($this->getUser())->setCreatedAt(new DateTime('now'))->setUpdatedAt(new DateTime('now'));
            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Votre publication a été enregistré.');

            return $this->redirectToRoute('local-posts');
        }

        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'ville' => $ville,
            'form' => $form->createView(),
            'hideLocalPostsButton' => true
        ]);
    }

    /**
     * @Route("/post/{id}", name="single-post")
     */
    
    public function singlePost(Post $post, PostCommentRepository $repository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator)
    {   
        $comments = $paginator->paginate(
            $repository->findByPostWithPagination($post), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        
        $postComment = new PostComment();

        $form = $this->createForm(PostCommentType::class, $postComment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $postComment->setPostedBy($this->getUser())
                        ->setPost($post)
                        ->setPostedAt(new DateTime('now'));
            $manager->persist($postComment);
            $manager->flush();

            return $this->redirectToRoute('single-post', ['id' => $post->getId()]);
        }

        return $this->render('post/singlePost.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'comments' => $comments
        ]);

    }

    /**
     * @Route("/user/posts/{id}", name="user-posts")
     */
    public function userPosts(User $user)
    {   
        
        $posts = $user->getPosts();

        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'user' => $user,
            'search' => false
        ]);
    }

    /**
     * @Route("/post/likes/{id}", name="post-likes")
     */
    public function postLikes(Post $post)
    {   
        $postLikes = $post->getPostLikes();

        $users = [];

        foreach($postLikes as $postLike){
            $users[] = $postLike->getPostedBy();
        }

        return $this->render('user/users.html.twig', [
            'users' => $users,
            'likes' => true,
            'search' => false
        ]);
    }

    /**
     * @Route("/post/like/{id}", name="post-like")
     */

    public function like(Post $post, EntityManagerInterface $manager, PostLikeRepository $repository): Response
    {
        $user = $this->getUser();
        
        if($post->isLikedByUser($user)){
            $like = $repository->findOneBy(['postedBy' => $user, 'post' => $post]);
            
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' => $repository->count(['post' => $post]) 
            ], 200);

        }

        $like = new PostLike();
        $like->setPostedBy($user)
            ->setPost($post);

        $manager->persist($like);
        $manager->flush();
        
        return $this->json([
            'code' => 200,
            'message' => 'Like ajouté',
            'likes' => $repository->count(['post' => $post]) 
        ], 200);
    
    }

    /**
     * @Route("/profil/posts", name="my-posts")
     */

    public function myPosts(PostRepository $repository, EntityManagerInterface $manager, Request $request):Response
    {

        $user = $this->getUser();

        $posts = $repository->findCurrentUserPosts($user);

        return $this->render('post/myPosts.html.twig', [
            'myPosts' => $posts,
            'hideMyPostsButton' => true
        ]);
    }

    /**
     * @Route("/profil/post/{id}/delete", name="post-delete", methods="delete")
     */

    public function postDelete(Post $post, EntityManagerInterface $manager, Request $request){

        if($this->isCsrfTokenValid('SUP' . $post->getId(), $request->get('_token'))){
            $manager->remove($post);
            $manager->flush();
            $this->addFlash("success",  "La suppression a été effectuée");
        }
        
        return $this->redirectToRoute('local-posts');
    }

    /**
     * @Route("/profil/post/{id}/update", name="post-update")
     */

     public function updatePost(Post $post, EntityManagerInterface $manager, Request $request):Response
     {
        $form = $this->createForm(PostType::class, $post,  [
            'action' => $this->generateUrl('post-update', [ 'id'=>$post->getId()])
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'La modification a bien été effectué.');

            return $this->redirectToRoute('my-posts');
        }

        $response = array(
            'code' => 200,
            'response' => $this->render('post/updatePost.html.twig', [
                'form' => $form->createView(),
                'post' => $post
            ])->getContent()
        );

        return new JsonResponse($response);
     }

     /**
     * @Route("/profil/post/json/{id}", name="post-json")
     */

     public function jsonPost(Post $post):Response
     {
        
        $response = $this->render('post/myPost.html.twig', ['post' => $post])->getContent();

        return new JsonResponse($response);
     }
}
