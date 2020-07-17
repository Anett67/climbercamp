<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Event;
use App\Form\EventType;
use App\Entity\EventSearch;
use App\Entity\EventComment;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EventController extends AbstractController
{
    /**
     * Shows the events saved by the current user
     * 
     * @Route("/user/events", name="saved-events", requirements={"id":"\d+"})
     */
    public function savedEvents(EventRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {       
        $user = $this->getUser();

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
     * Shows local events or all events if the user has not defined his town
     * 
     * @Route("/local-events", name="local-events")
     */
    public function localEvents(EventRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $ville = $this->getUser()->getVille();

        if($ville){
           $events = $paginator->paginate(
                $repository->findByVille($ville), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                5 /*limit per page*/
            ); 
        }else{
            $events = $paginator->paginate(
                $repository->findAllWithPagination(), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                5 /*limit per page*/
            );  
        }
        
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
            'localEventsPage' => true
        ]);
    }

    /**
     * Creation of a new event
     * 
     * @Route("/event/new", name="event-new")
     * 
     * @IsGranted("ROLE_ADMIN")
     */

    public function newEvent(Request $request, EntityManagerInterface $manager): Response
    {

        $event = new Event();
        
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted()  && $form->isValid()){
            
            $event->setPostedBy($this->getUser());
            $event->setUpdatedAt(new DateTime('now'));

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
     * The action to save an event to the users "interesting events" list
     * 
     * @Route("/event/save/{id}", name="event-save", requirements={"id":"\d+"})
     */

    public function saveEvent(Event $event, EntityManagerInterface $manager): Response
    {

        $event->addInterestedUser($this->getUser());

        $manager->persist($event);
        $manager->flush();

        $this->addFlash('success', 'Vous avez bien enregistré cet évènement');

        return $this->redirectToRoute('local-events');

    }

    /**
     * Remove an event from the users list
     * 
     * @Route("/event/remove/{id}", name="event-remove", requirements={"id":"\d+"})
     */

    public function removeEvent(Event $event, EntityManagerInterface $manager): Response
    {

        $event->removeInterestedUser($this->getUser());

        $manager->persist($event);
        $manager->flush();

        $this->addFlash('success', "L'évènement a été supprimé de votre liste");

        return $this->redirectToRoute('local-events');

    }


    /**
     * Shows an event with its comments
     * 
     * @Route("/event/{id}", name="single-event", requirements={"id":"\d+"})
     */
    public function singleEvent(Event $event, EventCommentRepository $repo, Request $request, EntityManagerInterface $manager, PaginatorInterface $paginator): Response
    {     

        $comments = $paginator->paginate(
            $repo->findCommentsPagination($event), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

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
     * Shows users interested in the event
     * 
     * @Route("/event/users/{id}", name="event-users", requirements={"id":"\d+"})
     */
    public function eventUsers(Event $event): Response
    {
        $users = $event->getInterestedUsers();

        return $this->render('user/users.html.twig', [
            'users' => $users,
            'event' => true,
        ]);
    }

    /**
     * Shows events created by the current user
     * 
     * @Route("/profil/events", name="my-events")
     * 
     * @IsGranted("ROLE_ADMIN")
     */

     public function myEvents(EventRepository $repository, Request $request, PaginatorInterface $paginator): Response
     {

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
      * Delete an event on "My events" page
      *
     * @Route("/profil/event/{id}/delete", name="event-delete", requirements={"id":"\d+"}, methods="delete")
     * 
     * @IsGranted("ROLE_ADMIN")
     */

    public function postDelete(Event $event, EntityManagerInterface $manager, Request $request): Response
    {

        if($this->isCsrfTokenValid('SUP' . $event->getId(), $request->get('_token'))){
            $manager->remove($event);
            $manager->flush();
            $this->addFlash("success",  "La suppression a été effectuée");
        }

        return $this->redirectToRoute('local-events');
    }

    /**
     * Update an event on "My events" page
     * 
     * @Route("/profil/event/{id}/update", name="event-update", requirements={"id":"\d+"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */

    public function updatePost(Event $event, EntityManagerInterface $manager, Request $request):Response
    {   

       $form = $this->createForm(EventType::class, $event,  [
           'action' => $this->generateUrl('event-update', [ 'id'=> $event->getId()])
       ]);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

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
     * Load the template of an event on "My events" page
     * 
    * @Route("/profil/event/json/{id}", name="event-json", requirements={"id":"\d+"})
    *
    * @IsGranted("ROLE_ADMIN")
    */

    public function jsonEvent(Event $event):Response
    {

       $response = $this->render('event/myEvent.html.twig', ['event' => $event])->getContent();

       return new JsonResponse($response);
    }

    
}
