<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/saved-events", name="saved-events")
     */
    public function savedEvents()
    {
        return $this->render('event/savedEvents.html.twig', [
            
        ]);
    }

    /**
     * @Route("/local-events", name="local-events")
     */
    public function localEvents()
    {
        return $this->render('event/localEvents.html.twig', [
            
        ]);
    }

    /**
     * @Route("/event-search", name="event-search")
     */
    public function eventSearch()
    {
        return $this->render('event/eventSearch.html.twig', [
            
        ]);
    }
}
