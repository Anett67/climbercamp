<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Event;
use App\Form\EventType;
use App\Entity\EventInsert;
use App\Entity\EventSearch;
use App\Entity\EventComment;
use App\Form\EventInsertType;
use App\Form\EventSearchType;
use App\Form\EventCommentType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventCommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/user/events/{id}", name="saved-events")
     */
    public function savedEvents(User $user, EventRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $events = $paginator->paginate(
            $repository->getFutureSavedEvents($user), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );

        return $this->render('event/events.html.twig', [
            'events' => $events,
            'user' => $user
        ]);
    }

    /**
     * @Route("/local-events", name="local-events")
     */
    public function localEvents(EventRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $ville = $this->getUser()->getVille();

        //$events = $repository->findByVille($ville);

        $events = $paginator->paginate(
            $repository->findByVille($ville), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        $eventSearch = new EventSearch();

        $form = $this->createForm(EventSearchType::class, $eventSearch);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $events = $repository->findWithSearch($eventSearch);

            return $this->render('event/events.html.twig', [
                'events' => $events,
                'ville' => $ville,
                'form' => $form->createView()
            ]);
        }

        return $this->render('event/events.html.twig', [
            'events' => $events,
            'ville' => $ville,
            'form' => $form->createView(),
            'hideLocalEventsButton' => true
        ]);
    }

    /**
     * @Route("/event/new", name="event-new")
     */

    public function newEvent(Request $request, EntityManagerInterface $manager){

        $eventInsert = new EventInsert();

        $form = $this->createForm(EventInsertType::class, $eventInsert);

        $form->handleRequest($request);

        $event = new Event();

        if($form->isSubmitted()  && $form->isValid()){
            
            $event->setTitle($eventInsert->getTitle())
                ->setDescription($eventInsert->getDescription())
                ->setEventDate($eventInsert->getEventDate())
                ->setVille($eventInsert->getVille())
                ->setPostedBy($this->getUser())
                ->setLocation($eventInsert->getLocation())
                ->setImageFile($eventInsert->getImageFile())
                ;

            $manager->persist($event);
            $manager->flush();

            $this->addFlash('success', 'L\'évènement a bien été enregistré');

            return $this->redirectToRoute('local-events');

        }

        return $this->render('event/newEvent.html.twig' , [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/event/save/{id}", name="event-save")
     */

    public function saveEvent(Event $event, EntityManagerInterface $manager){

        $event->addInterestedUser($this->getUser());

        $manager->persist($event);
        $manager->flush();

        $this->addFlash('success', 'Vous avez bien enregistré cet évènement');

        return $this->redirectToRoute('local-events');

    }

    /**
     * @Route("/event/remove/{id}", name="event-remove")
     */

    public function removeEvent(Event $event, EntityManagerInterface $manager){

        $event->removeInterestedUser($this->getUser());

        $manager->persist($event);
        $manager->flush();

        $this->addFlash('success', "L'évènement a été supprimé de votre liste");

        return $this->redirectToRoute('local-events');

    }


    /**
     * @Route("/event/{id}", name="single-event")
     */
    public function singleEvent(Event $event, EventCommentRepository $repo, Request $request, EntityManagerInterface $manager)
    {     
        $comments = $repo->findComments($event);

        $eventComment = new EventComment();

        $form = $this->createForm(EventCommentType::class, $eventComment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $eventComment->setEvent($event)
                        ->setPostedBy($this->getUser())
                        ->setPostedAt(new DateTime('now'));
            $manager->persist($eventComment);
            $manager->flush();

            return $this->redirectToRoute('single-event', ['id' => $event->getId()]);

        }

        return $this->render('event/singleEvent.html.twig', [
            'event' => $event,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/users/{id}", name="event-users")
     */
    public function eventUsers(Event $event)
    {
        $users = $event->getInterestedUsers();

        return $this->render('user/users.html.twig', [
            'users' => $users,
            'event' => true,
        ]);
    }

    /**
     * @Route("/profil/events", name="my-events")
     */

     public function myEvents(EventRepository $repository, Request $request, PaginatorInterface $paginator){

        $user = $this->getUser();

        $myEvents = $paginator->paginate(
            $repository->findCurrentUserFutureEvents($user), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('event/myEvents.html.twig', [
            'myEvents' => $myEvents,
            'hideMyEventsButton' => true
        ]);
     }

     /**
     * @Route("/profil/event/{id}/delete", name="event-delete")
     */

    public function postDelete(Event $event, EntityManagerInterface $manager, Request $request){

        if($this->isCsrfTokenValid('SUP' . $event->getId(), $request->get('_token'))){
            $manager->remove($event);
            $manager->flush();
            $this->addFlash("success",  "La suppression a été effectuée");
        }

        return $this->redirectToRoute('local-events');
    }

    /**
     * @Route("/profil/event/{id}/update", name="event-update")
     */

    public function updatePost(Event $event, EntityManagerInterface $manager, Request $request):Response
    {   
        $eventInsert = new EventInsert();

        $eventInsert->setTitle($event->getTitle())
                    ->setDescription($event->getDescription())
                    ->setEventDate($event->getEventDate())
                    ->setLocation($event->getLocation())
                    ->setVille($event->getVille())
                    ->setImage($event->getImage());

       $form = $this->createForm(EventInsertType::class, $eventInsert,  [
           'action' => $this->generateUrl('event-update', [ 'id'=> $event->getId()])
       ]);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
            $event->setTitle($eventInsert->getTitle())
                    ->setDescription($eventInsert->getDescription())
                    ->setEventDate($eventInsert->getEventDate())
                    ->setLocation($eventInsert->getLocation())
                    ->setVille($eventInsert->getVille())
                    ->setImageFile($eventInsert->getImageFile());

           $manager->persist($event);
           $manager->flush();

           $this->addFlash('success', 'La modification a bien été effectué.');

           return $this->redirectToRoute('my-events');
       }

       $response = array(
           'code' => 200,
           'response' => $this->render('event/updateEvent.html.twig', [
               'form' => $form->createView(),
               'event' => $event
           ])->getContent()
       );

       return new JsonResponse($response);
    }

    /**
    * @Route("/profil/event/json/{id}", name="event-json")
    */

    public function jsonEvent(Event $event):Response
    {

       $response = $this->render('event/myEvent.html.twig', ['event' => $event])->getContent();

       return new JsonResponse($response);
    }

    
}
