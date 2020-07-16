<?php

namespace App\Controller;

use DateTime;
use App\Entity\PostComment;
use App\Entity\EventComment;
use App\Entity\PostCommentLike;
use App\Entity\EventCommentLike;
use App\Entity\PostCommentReply;
use App\Entity\EventCommentReply;
use App\Form\PostCommentReplyType;
use App\Form\EventCommentReplyType;
use App\Form\EventCommentType;
use App\Form\PostCommentType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostCommentLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EventCommentLikeRepository;
use App\Repository\PostCommentReplyRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\EventCommentReplyRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * This action saves a new like or deletes one and sends back the total number of likes 
     * 
     * @Route("/post/comment/like/{id}", name="comment-like", requirements={"id":"\d+"})
     */
    public function postCommentLike(PostComment $comment, PostCommentLikeRepository $repository, EntityManagerInterface $manager): JsonResponse
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
     * This action saves a new like or deletes one and sends back the total number of likes 
     * 
     * @Route("/event/comment/like/{id}", name="event-comment-like", requirements={"id":"\d+"})
     */
    public function eventCommentLike(EventComment $comment, EventCommentLikeRepository $repository, EntityManagerInterface $manager): JsonResponse
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
     * This action loads the replies of a postlike
     * 
     * @Route("post/comment/replies/{id}", name="comment-replies", requirements={"id":"\d+"})
     */

    public function commentReplies(PostComment $comment, Request $request, EntityManagerInterface $manager, PostCommentReplyRepository $repository): JsonResponse
    {   
        $post = $comment->getPost();

        $postCommentReply = new PostCommentReply();

        $form = $this->createForm(PostCommentReplyType::class, $postCommentReply, [
            'action' => $this->generateUrl('comment-replies', [ 'id'=> $comment->getId()])
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
            "replies" => $repository->count(['postComment' => $comment]),
            "response" => $this->render('comment/commentReplies.html.twig', ['replies' => $replies, 'form' => $form->createView() ])->getContent()
        );

        return new JsonResponse($response);

    }

    /**
     * This action loads the replies of an eventlike
     * 
     * @Route("event/comment/replies/{id}", name="event-comment-replies", requirements={"id":"\d+"})
     */

    public function eventCommentReplies(EventComment $comment, EventCommentReplyRepository $repository, EntityManagerInterface $manager, Request $request): JsonResponse
    {   
        $eventCommentReply = new EventCommentReply();

        $form = $this->createForm(EventCommentReplyType::class, $eventCommentReply,  [
            'action' => $this->generateUrl('event-comment-replies', [ 'id'=>$comment->getId()])
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $eventCommentReply->setPostedAt(new DateTime('now'))
                            ->setPostedBy($this->getUser())
                            ->setEventComment($comment);
            $manager->persist($eventCommentReply);
            $manager->flush();
        }

        $replies = $repository->findBy(['eventComment' => $comment], ['postedAt' => 'DESC' ]);

        $response = array(
            "code" => 200,
            "replies" => $repository->count(['eventComment' => $comment]),
            "response" => $this->render('comment/commentReplies.html.twig', ['replies' => $replies, 'comment' => $comment, 'form' =>$form->createView() ])->getContent()
        );

        return new JsonResponse($response);

    }

    /**
     * Delete an event comment
     * 
     * @Route("/event/comment/{id}/delete", name="eventcomment-delete", requirements={"id":"\d+"}, methods="delete")
     */

    public function eventCommentDelete(EventComment $comment, EntityManagerInterface $manager, Request $request): Response
    {

        $event = $comment->getEvent();

        if($this->isCsrfTokenValid('comment_delete' . $comment->getId(), $request->get('_token'))){
            $manager->remove($comment);
            $manager->flush();
            $this->addFlash("success",  "La suppression a été effectuée");
        }

            return $this->redirectToRoute('single-event',[ 
            'id' => $event->getId()
        ]);
    }

    /**
     * Delete a post comment
     * 
     * @Route("/post/comment/{id}/delete", name="postcomment-delete", requirements={"id":"\d+"}, methods="delete")
     */

    public function postDelete(PostComment $comment, EntityManagerInterface $manager, Request $request): Response
    {
        $post = $comment->getPost();

        if($this->isCsrfTokenValid('comment_delete' . $comment->getId(), $request->get('_token'))){
            $manager->remove($comment);
            $manager->flush();
            $this->addFlash("success",  "La suppression a été effectuée");
        }
        return $this->redirectToRoute('single-post',[ 
            'id' => $post->getId()
        ]);
    }

    /**
     * Deletes a post comment reply
     * 
     * @Route("/post/reply/{id}/delete", name="postcommentreply-delete", requirements={"id":"\d+"}, methods="delete")
     */

    public function postCommentReplyDelete(PostCommentReply $reply, EntityManagerInterface $manager, Request $request):Response
    {

        $comment = $reply->getPostComment();

        if($this->isCsrfTokenValid('reply_delete' . $reply->getId(), $request->get('_token'))){
            $manager->remove($reply);
            $manager->flush();

            $this->addFlash('success', 'La réponse a bien été supprimé');

            return $this->redirectToRoute('single-post', ['id' => $comment->getPost()->getId()]);
        }
    }

    /**
     * Deletes an event comment reply
     * 
     * @Route("/event/reply/{id}/delete", name="eventcommentreply-delete", requirements={"id":"\d+"}, methods="delete")
     */

    public function eventCommentReplyDelete(EventCommentReply $reply, EntityManagerInterface $manager, Request $request): Response
    {

        $comment = $reply->getEventComment();

        if($this->isCsrfTokenValid('reply_delete' . $reply->getId(), $request->get('_token'))){
            $manager->remove($reply);
            $manager->flush();

            $this->addFlash('success', 'La réponse a bien été supprimé');

            return $this->redirectToRoute('single-event', ['id' => $comment->getEvent()->getId()]);            
        }
    }

    /**
     * Updates a post comment
     * 
     * @Route("/post/comment/{id}/update", name="post-comment-update", requirements={"id":"\d+"})
     */

    public function updatePostComment(PostComment $comment, EntityManagerInterface $manager, Request $request):Response
    {
       $form = $this->createForm(PostCommentType::class , $comment,  [
           'action' => $this->generateUrl('post-comment-update', [ 'id'=>$comment->getId()])
       ]);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
           $manager->persist($comment);
           $manager->flush();

           $this->addFlash('success', 'La modification a bien été effectué.');

           return $this->redirectToRoute('single-post', ['id' => $comment->getPost()->getId()]);
       }

       $response = array(
           'code' => 200,
           'response' => $this->render('comment/updateComment.html.twig', [
               'form' => $form->createView(),
               'comment' => $comment
           ])->getContent()
       );

       return new JsonResponse($response);
    }

     /**
      * AJAX endpoint to send back the template of a post comment 
      *
     * @Route("/post/comment/json/{id}", name="post-comment-json", requirements={"id":"\d+"})
     */

    public function jsonPost(PostComment $comment):Response
    {
       
       $response = $this->render('comment/singlePostComment.html.twig', ['comment' => $comment, 'post' => $comment->getPost()])->getContent();

       return new JsonResponse($response);
    }

    /**
     * Update an event comment
     * 
     * @Route("/event/comment/{id}/update", name="event-comment-update", requirements={"id":"\d+"})
     */

    public function updateEventComment(EventComment $comment, EntityManagerInterface $manager, Request $request): Response
    {
       $form = $this->createForm(EventCommentType::class , $comment,  [
           'action' => $this->generateUrl('event-comment-update', [ 'id'=>$comment->getId()])
       ]);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
           $manager->persist($comment);
           $manager->flush();

           $this->addFlash('success', 'La modification a bien été effectué.');

           return $this->redirectToRoute('single-event', ['id' => $comment->getEvent()->getId()]);
       }

       $response = array(
           'code' => 200,
           'response' => $this->render('comment/updateComment.html.twig', [
               'form' => $form->createView(),
               'comment' => $comment
           ])->getContent()
       );

       return new JsonResponse($response);
    }

     /**
      * AJAX endpoint to send back the template of a event comment 
      * 
     * @Route("/event/comment/json/{id}", name="event-comment-json", requirements={"id":"\d+"})
     */

    public function jsonEvent(EventComment $comment): JsonResponse
    {
       
       $response = $this->render('comment/singleEventComment.html.twig', ['comment' => $comment, 'event' => $comment->getEvent()])->getContent();

       return new JsonResponse($response);
    }

    /**
     * Update a reply on a post comment
     * 
     * @Route("/post/reply/{id}/update", name="post-reply-update", requirements={"id":"\d+"})
     */

    public function updatePostReply(PostCommentReply $reply, EntityManagerInterface $manager, Request $request):Response
    {
       $form = $this->createForm(PostCommentReplyType::class , $reply,  [
           'action' => $this->generateUrl('post-reply-update', [ 'id'=>$reply->getId()])
       ]);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
           $manager->persist($reply);
           $manager->flush();

           $this->addFlash('success', 'La modification a bien été effectué.');

           return $this->redirectToRoute('single-post', ['id' => $reply->getPostComment()->getPost()->getId()]);
       }

       $response = array(
           'code' => 200,
           'response' => $this->render('comment/updateCommentReply.html.twig', [
               'form' => $form->createView(),
               'reply' => $reply
           ])->getContent()
       );

       return new JsonResponse($response);
    }

     /**
      * Sends back the template of a comment reply 
      *
     * @Route("/post/reply/json/{id}", name="post-reply-json", requirements={"id":"\d+"})
     */

    public function jsonPostCommentReply(PostCommentReply $reply):Response
    {
       
       $response = $this->render('comment/singleCommentReply.html.twig', ['reply' => $reply])->getContent();

       return new JsonResponse($response);
    }

    /**
     * Update an event comment reply
     * 
     * @Route("/event/reply/{id}/update", name="event-reply-update", requirements={"id":"\d+"})
     */

    public function updateEventReply(EventCommentReply $reply, EntityManagerInterface $manager, Request $request):Response
    {
       $form = $this->createForm(EventCommentReplyType::class , $reply,  [
           'action' => $this->generateUrl('event-reply-update', [ 'id'=>$reply->getId()])
       ]);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
           $manager->persist($reply);
           $manager->flush();

           $this->addFlash('success', 'La modification a bien été effectué.');

           return $this->redirectToRoute('single-event', ['id' => $reply->getEventComment()->getEvent()->getId()]);
       }

       $response = array(
           'code' => 200,
           'response' => $this->render('comment/updateCommentReply.html.twig', [
               'form' => $form->createView(),
               'reply' => $reply
           ])->getContent()
       );

       return new JsonResponse($response);
    }

     /**
      * Sends back the template of an event comment reply 
      *
     * @Route("/event/reply/json/{id}", name="event-reply-json", requirements={"id":"\d+"})
     */

    public function jsonEventCommentReply(EventCommentReply $reply):Response
    {
       
       $response = $this->render('comment/singleCommentReply.html.twig', ['reply' => $reply])->getContent();

       return new JsonResponse($response);
    }

   
}
