<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index(MessageRepository $repository, UserRepository $userRepo)
    {   
        $user = $this->getUser();
     
        $allMessages = $repository->findUsersMessages($user);

        $usersNotToCountAnyMore = [];

        $messages = [];

        foreach($allMessages as $message){
            $fromUserId = $message->getFromUser()->getId();
            $toUserId = $message->getToUser()->getId();

            if(!in_array($fromUserId, $usersNotToCountAnyMore) && $fromUserId != $user->getId() ){
                $messages[] = $message;
                $usersNotToCountAnyMore[] = $fromUserId;
            }

            if(!in_array($toUserId, $usersNotToCountAnyMore) && $toUserId != $user->getId() ){
                $messages[] = $message;
                $usersNotToCountAnyMore[] = $toUserId;
            }
        }

        return $this->render('message/messages.html.twig', [
            'messages' => $messages
        ]);
    }
}
