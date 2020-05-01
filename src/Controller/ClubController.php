<?php

namespace App\Controller;

use App\Entity\ClimbingClub;
use App\Repository\ClimbingClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    /**
     * @Route("/local-clubs", name="local-clubs")
     */
    public function localClubs(ClimbingClubRepository $repository)
    {

        $ville = $this->getUser()->getVille();

        $clubs = $repository->findByVille($ville);

        return $this->render('club/clubs.html.twig', [
            'clubs' => $clubs
        ]);
    }

    /**
     * @Route("/club/saved-clubs", name="saved-clubs")
     */
    public function savedClubs()
    {
        return $this->render('club/clubs.html.twig', [
            
        ]);
    }

    /**
     * @Route("/club/{id}", name="single-club")
     */
    public function singleClub(ClimbingClub $club)
    {
        $users = $club->getUsers();

        return $this->render('club/singleClub.html.twig', [
            'users' => $users,
            'club' => $club
        ]);
    }

    /**
     * @Route("/club/search", name="club-search")
     */
    public function clubSearch()
    {
        return $this->render('club/clubs.html.twig', [
            
        ]);
    }

}
