<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventCommentRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/saved-events", name="saved-events")
     */
    public function savedEvents()
    {
        return $this->render('event/events.html.twig', [
            
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
            'events' => $events
        ]);
    }

    /**
     * @Route("/event-search", name="event-search")
     */
    public function eventSearch()
    {
        return $this->render('event/events.html.twig', [
            
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
}
