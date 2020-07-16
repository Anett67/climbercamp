<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Entity\PostLike;
use App\Entity\PostComment;
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
     * The page shows the local posts if the user has his town defined or all posts if it's not
     * 
     * @Route("/", name="local-posts")
     */
    public function localPosts(PostRepository $repository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator): Response
    {   
        $ville = $this->getUser()->getVille();
        $postsPerPage = 5;

        if($ville){
            $totalPosts = count($repository->findLocalPosts($ville));
            $posts = $paginator->paginate(
                $repository->findLocalPostsWithPagination($ville), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                $postsPerPage /*limit per page*/
            );
        }else{
            $totalPosts = count($repository->findAll());
            $posts = $paginator->paginate(
                $repository->findAllWithPagination(), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                $postsPerPage /*limit per page*/
            );
        }

        //Last page of pagination where the infinite scroll must stop
        $lastPage = ceil($totalPosts/$postsPerPage);
        
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
            'hideLocalPostsButton' => true, 
            'lastPage' => $lastPage
        ]);
    }

    /**
     * The action called when th users scrolls on the bottom of the page and loads more posts
     * 
     * @Route("/posts/{page}", name="scroll-post", requirements={"page":"\d+"})
     */

    public function scroll($page, PaginatorInterface $paginator, PostRepository $repository, Request $request): JsonResponse
    {

        $ville = $this->getUser()->getVille();
        $postsPerPage = 5;

        if($ville){
            $posts = $paginator->paginate(
                $repository->findLocalPostsWithPagination($ville), /* query NOT result */
                $request->query->getInt('page', $page), /*page number*/
                $postsPerPage /*limit per page*/
            );
        }else{
            $posts = $paginator->paginate(
                $repository->findAllWithPagination(), /* query NOT result */
                $request->query->getInt('page', $page), /*page number*/
                $postsPerPage /*limit per page*/
            );
        }

        $response = $this->render('post/post.html.twig', ['posts' => $posts])->getContent();
        return new JsonResponse($response);
    }

    /**
     * The page which shows a post with its comments
     * 
     * @Route("/post/{id}", name="single-post", requirements={"id":"\d+"})
     */
    
    public function singlePost(Post $post, PostCommentRepository $repository, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator): Response
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
     * The page which shows all the posts of a user
     * 
     * @Route("/user/posts/{id}", name="user-posts", requirements={"id":"\d+"})
     */
    public function userPosts(User $user): Response
    {   
        
        $posts = $user->getPosts();

        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'user' => $user,
            'search' => false
        ]);
    }

    /**
     * The page which shows users who liked a post
     * 
     * @Route("/post/likes/{id}", name="post-likes", requirements={"id":"\d+"})
     */
    public function postLikes(Post $post): Response
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
     * The action to add or delete a like and send back the total number of likes for the post
     * 
     * @Route("/post/like/{id}", name="post-like", requirements={"id":"\d+"})
     */

    public function like(Post $post, EntityManagerInterface $manager, PostLikeRepository $repository): Response
    {
        $user = $this->getUser();
        
        if($post->isLikedByUser($user)){
            
            $like = $repository->findOneBy(['postedBy' => $user, 'post' => $post]);
            $manager->remove($like);
            $manager->flush();

        }else{
            
            $like = new PostLike();
            $like->setPostedBy($user)
                ->setPost($post);
            $manager->persist($like);
            $manager->flush();
        }

        return $this->json([
            'likes' => $repository->count(['post' => $post]) 
        ], 200);
    }

    /**
     * Shows the posts of the current user
     * 
     * @Route("/profil/posts", name="my-posts")
     */

    public function myPosts(PostRepository $repository):Response
    {

        $user = $this->getUser();

        $posts = $repository->findCurrentUserPosts($user);

        return $this->render('post/myPosts.html.twig', [
            'myPosts' => $posts,
            'hideMyPostsButton' => true
        ]);
    }

    /**
     * Delete a post 
     * 
     * @Route("/profil/post/{id}/delete", name="post-delete", methods="delete", requirements={"id":"\d+"})
     */

    public function postDelete(Post $post, EntityManagerInterface $manager, Request $request): Response
    {   
        
        if($this->isCsrfTokenValid('SUP' . $post->getId(), $request->get('_token'))){
            $manager->remove($post);
            $manager->flush();
            $this->addFlash("success",  "La suppression a été effectuée");
        }
        
        return $this->redirectToRoute('local-posts');
    }

    /**
     * Update a post
     * 
     * @Route("/profil/post/{id}/update", name="post-update", requirements={"id":"\d+"})
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
      * AJAX endpoint to send back the templat of a post on "My posts" page 
      *
     * @Route("/profil/post/json/{id}", name="post-json", requirements={"id":"\d+"})
     */

     public function jsonPost(Post $post):Response
     {
        
        $response = $this->render('post/myPost.html.twig', ['post' => $post])->getContent();

        return new JsonResponse($response);
     }
}
