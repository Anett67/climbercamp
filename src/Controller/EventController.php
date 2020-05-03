<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventSearch;
use App\Entity\User;
use App\Form\EventSearchType;
use App\Form\EventType;
use App\Repository\EventCommentRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/user/events/{id}", name="saved-events")
     */
    public function savedEvents(User $user, EventRepository $repository)
    {

        $events = $user->getSavedEvents();

        return $this->render('event/events.html.twig', [
            'events' => $events,
            'user' => $user,
            'search' => false
        ]);
    }

    /**
     * @Route("/local-events", name="local-events")
     */
    public function localEvents(EventRepository $repository)
    {
        $ville = $this->getUser()->getVille();

        $events =$repository->findByVille($ville);

        return $this->render('event/events.html.twig', [
            'events' => $events,
            'ville' => $ville,
            'search' =>false
        ]);
    }

    /**
     * @Route("/events/search", name="event-search")
     */
    public function eventSearch(EventRepository $repository, Request $request)
    {   
        $eventSearch = new EventSearch();

        $form = $this->createForm(EventSearchType::class, $eventSearch);

        $form->handleRequest($request);

        $events = $repository->findWithSearch($eventSearch);

        return $this->render('event/events.html.twig', [
            'events' => $events,
            'form' => $form->createView(),
            'search' => true
        ]);
    }

    /**
     * @Route("/event/new", name="event-new")
     */

    public function newEvent(Request $request, EntityManagerInterface $manager){

        $event = new Event();

        $form =  $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $event->setPostedBy($this->getUser);
            $manager->persist($event);
            $manager->flush;

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
    public function singleEvent(Event $event, EventCommentRepository $repo)
    {     
        $comments = $repo->findComments($event);

        return $this->render('event/singleEvent.html.twig', [
            'event' => $event,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/event/users/{id}", name="event-users")
     */
    public function eventUsers(Event $event)
    {
        $users = $event->getInterestedUsers();
        dump($users);
        return $this->render('user/users.html.twig', [
            'users' => $users,
            'event' => true,
            'search' => false
        ]);
    }
}
