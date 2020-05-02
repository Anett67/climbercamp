<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventCommentRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/user/events/{id}", name="saved-events")
     */
    public function savedEvents(User $user)
    {

        $events = $user->getSavedEvents();

        return $this->render('event/events.html.twig', [
            'events' => $events,
            'user' => $user
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
            'ville' => $ville
        ]);
    }

    /**
     * @Route("/event/search", name="event-search")
     */
    public function eventSearch()
    {
        return $this->render('event/events.html.twig', [
            'search' => true
        ]);
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
