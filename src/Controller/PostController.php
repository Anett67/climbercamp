<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'posts' => $posts
        ]);
    }
}
