<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\SearchPost;
use App\Entity\User;
use App\Form\SearchPostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="local-posts")
     */
    public function localPosts(PostRepository $repository)
    {   
        $ville = $this->getUser()->getVille();

        $posts = $repository->findLocalPosts($ville);

        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'ville' => $ville,
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
}
