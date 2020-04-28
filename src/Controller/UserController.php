<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
        return $this->render('user/users.html.twig', [
            
        ]);
    }

    /**
     * @Route("/user-search", name="user-search")
     */
    public function userSearch()
    {
        return $this->render('user/userSearch.html.twig', [
            
        ]);
    }

}
