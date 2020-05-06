<?php

namespace App\Controller;

use App\Entity\PostComment;
use App\Entity\EventComment;
use App\Entity\PostCommentLike;
use App\Entity\EventCommentLike;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostCommentRepository;
use App\Repository\PostCommentLikeRepository;
use App\Repository\EventCommentLikeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function commentReplies(PostComment $comment)
    {   
        
        $replies = $comment->getPostCommentReplies();

        $response = array(
            "code" => 200,
            "response" => $this->render('comment/commentReplies.html.twig', ['replies' => $replies ])->getContent()
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
            "response" => $this->render('comment/commentReplies.html.twig', ['replies' => $replies ])->getContent()
        );

        return new JsonResponse($response);

    }
}
