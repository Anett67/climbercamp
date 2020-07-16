<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index(MessageRepository $repository): Response
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
     * @Route("/conversation/{id}", name="conversation", requirements={"id":"\d+"})
     */

     public function conversation(User $partner, MessageRepository $repository, EntityManagerInterface $manager, Request $request): Response
     {

        $user = $this->getUser();

        $messages = [];

        $allMessages = $repository->findUsersMessages($user);

        foreach($allMessages as $message){
            $fromUserId = $message->getFromUser()->getId();
            $toUserId = $message->getToUser()->getId();
            if($fromUserId === $partner->getId() || $toUserId === $partner->getId()){
                $messages[] = $message;
                if($toUserId === $user->getId() && $fromUserId === $partner->getId()){
                    $message->setSeen(true);
                    $manager->persist($message);
                    $manager->flush();
                }
            }
        }

        $newMessage = new Message();

        $form = $this->createForm(MessageType::class, $newMessage);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newMessage->setFromUser($user)
                        ->setToUser($partner)
                        ->setSendDate(new DateTime('now'))
                        ->setSeen(false);
            $manager->persist($newMessage);
            $manager->flush();

            $this->addFlash('success', 'Votre message a été envoyé avec succès');

            return $this->redirectToRoute('conversation', ['id' => $partner->getId()]);
        }

        return $this->render('message/conversation.html.twig',[
            'partner' => $partner,
            'messages' => $messages,
            'form' => $form->createView()
        ]);

     }

    /**
    * @Route("/message/seen/{id}", name="message-seen", requirements={"id":"\d+"})
    */

    public function setMessageSeen(Message $message, EntityManagerInterface $manager): Response
    {

        $message->setSeen(true);
        $manager->persist($message);
        $manager->flush();

        return $this->redirectToRoute('messages');

    }
}