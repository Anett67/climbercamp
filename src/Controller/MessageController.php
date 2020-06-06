<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     * @Route("/conversation/{id}", name="conversation")
     */

     public function conversation(User $partner, MessageRepository $repository, EntityManagerInterface $manager, Request $request){

        $user = $this->getUser();

        $messages = $repository->findConversation($user, $partner);

        return $this->render('message/conversation.html.twig',[
            'partner' => $partner,
            'messages' => $messages
        ]);

     }
}