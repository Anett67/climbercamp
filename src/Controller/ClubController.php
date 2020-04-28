<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    /**
     * @Route("/clubs", name="clubs")
     */
    public function index()
    {
        return $this->render('club/clubs.html.twig', [
            
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
