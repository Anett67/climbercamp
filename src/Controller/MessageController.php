<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index()
    {
        return $this->render('message/messages.html.twig', [
            
        ]);
    }
}