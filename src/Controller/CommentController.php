<?php

namespace App\Controller;

use DateTime;
use App\Entity\PostComment;
use App\Entity\EventComment;
use App\Entity\PostCommentLike;
use App\Entity\EventCommentLike;
use App\Entity\PostCommentReply;
use App\Form\PostCommentReplyType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostCommentRepository;
use App\Repository\PostCommentLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventCommentLikeRepository;
use App\Repository\PostCommentReplyRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * @Route("/post/comment/like/{id}", name="comment-like")
     */
    public function postCommentLike(PostComment $comment, PostCommentLikeRepository $repository, EntityManagerInterface $manager)
    {   
        $post = $comment->getPost();

        $user = $this->getUser();

        if($comment->isLikedByUser($user)){

            $postCommentLike = $repository->findOneBy(['postedBy' => $user, 'postComment' => $comment]);

            $manager->remove($postCommentLike);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' =>$repository->count(['postComment' => $comment]) 
            ], 200);

        }

        $postCommentLike = new PostCommentLike();
            $postCommentLike->setPostedBy($user)
                            ->setPostComment($comment);

            $manager->persist($postCommentLike);
            $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like ajouté',
            'likes' => $repository->count(['postComment' => $comment]) 
        ], 200);

    }

    /**
     * @Route("/event/comment/like/{id}", name="event-comment-like")
     */
    public function eventCommentLike(EventComment $comment, EventCommentLikeRepository $repository, EntityManagerInterface $manager)
    {   
        $event = $comment->getEvent();

        $user = $this->getUser();

        if($comment->isLikedByUser($user)){

            $eventCommentLike = $repository->findOneBy(['postedBy' => $user, 'eventComment' => $comment]);

            $manager->remove($eventCommentLike);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' =>$repository->count(['eventComment' => $comment]) 
            ], 200);

        }

        $eventCommentLike = new EventCommentLike();
            $eventCommentLike->setPostedBy($user)
                            ->setEventComment($comment);

            $manager->persist($eventCommentLike);
            $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like ajouté',
            'likes' => $repository->count(['eventComment' => $comment]) 
        ], 200);

    }

    /**
     * @Route("post/comment/replies/{id}", name="comment-replies")
     */

    public function commentReplies(PostComment $comment, Request $request, EntityManagerInterface $manager, PostCommentReplyRepository $repository)
    {   
        $post = $comment->getPost();

        $postCommentReply = new PostCommentReply();

        $form = $this->createForm(PostCommentReplyType::class, $postCommentReply, [
            'action' => $this->generateUrl('comment-replies', [ 'id'=>$comment->getId()])
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $postCommentReply->setPostedBy($this->getUser())
                            ->setPostedAt(new DateTime('now'))
                            ->setPostComment($comment);
            $manager->persist($postCommentReply);
            $manager->flush();

        }

        $replies = $repository->findBy(['postComment' => $comment], ['postedAt' => 'DESC' ]);

        $response = array(
            "code" => 200,
            "response" => $this->render('comment/commentReplies.html.twig', ['replies' => $replies, 'form' => $form->createView() ])->getContent()
        );

        return new JsonResponse($response);

    }

    /**
     * @Route("event/comment/replies/{id}", name="event-comment-replies")
     */

    public function eventCommentReplies(EventComment $comment)
    {   
        
        $replies = $comment->getEventCommentReplies();

        $response = array(
            "code" => 200,
            "response" => $this->render('comment/commentReplies.html.twig', ['replies' => $replies, 'comment' => $comment ])->getContent()
        );

        return new JsonResponse($response);

    }

}
