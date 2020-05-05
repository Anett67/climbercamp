<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\User;
use App\Form\PostType;
use App\Entity\SearchPost;
use App\Form\SearchPostType;
use App\Repository\PostRepository;
use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="local-posts")
     */
    public function localPosts(PostRepository $repository, Request $request, EntityManagerInterface $manager)
    {   
        $ville = $this->getUser()->getVille();

        $posts = $repository->findLocalPosts($ville);

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post->setPostedBy($this->getUser())->setCreatedAt(new DateTime('now'));
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('local-posts');
        }

        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'ville' => $ville,
            'form' => $form->createView(),
            'search' => false
        ]);
    }

    /**
     * @Route("/post/search", name="post-search")
     */
    public function postSearch(PostRepository $repository, Request $request)
    {   
        $searchPost = new SearchPost();

        $form = $this->createForm(SearchPostType::class, $searchPost);

        $form->handleRequest($request);

        $posts = $repository->findWithSearch($searchPost);

        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
            'search' => true
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
                'likes' =>$repository->count(['post' => $post]) 
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


}
