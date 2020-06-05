<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index(MessageRepository $repository)
    {   
        $user = $this->getUser();

        $messages = $repository->findUsersMessages($user);

        return $this->render('message/messages.html.twig', [
            'messages' => $messages
        ]);
    }
}
