<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    /**
     * @Route("/local-clubs", name="local-clubs")
     */
    public function localClubs()
    {
        return $this->render('club/localClubs.html.twig', [
            
        ]);
    }

    /**
     * @Route("/club/saved-clubs", name="saved-clubs")
     */
    public function savedClubs()
    {
        return $this->render('club/savedClubs.html.twig', [
            
        ]);
    }

    /**
     * @Route("/club/search", name="club-search")
     */
    public function clubSearch()
    {
        return $this->render('club/clubSearch.html.twig', [
            
        ]);
    }
}
