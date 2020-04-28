<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{
    /**
     * @Route("/friends", name="friends")
     */
    public function index()
    {
        return $this->render('friend/friends.html.twig', [
            
        ]);
    }
}
