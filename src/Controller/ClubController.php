<?php

namespace App\Controller;

use App\Entity\ClubSearch;
use App\Entity\ClimbingClub;
use App\Form\ClubSearchType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClimbingClubRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            'clubs' => $clubs,
            'search' => false
        ]);
    }

    /**
     * @Route("/clubs/saved-clubs", name="saved-clubs")
     */
    public function savedClubs()
    {   

        $user = $this->getUser();

        $clubs = $user->getClimbingClub();

        return $this->render('club/clubs.html.twig', [
            'search' => false,
            'clubs' => $clubs,
            'saved' => true
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
     * @Route("/clubs/search", name="club-search")
     */
    public function clubSearch(ClimbingClubRepository $repository, Request $request)
    {
        $clubSearch = new ClubSearch();

        $form = $this->createForm(ClubSearchType::class, $clubSearch);

        $form->handleRequest($request);

        $clubs = $repository->findWithSearch($clubSearch);

        return $this->render('club/clubs.html.twig', [
            'clubs' => $clubs,
            'form' => $form->createView(),
            'search' => true
        ]);
    }

    /**
     * @Route("/clubs/users/{id}", name="club-users")
     */
    public function clubUsers(ClimbingClub $club)
    {
        $users = $club->getUsers();

        return $this->render('user/users.html.twig', [
            'users' => $users,
            'club' => true,
            'search' => false
        ]);
    }

    /**
     * @Route("/club/save/{id}", name="club-save")
     */

    public function saveClub(ClimbingClub $club, EntityManagerInterface $manager){

        $club->addUser($this->getUser());

        $manager->persist($club);
        $manager->flush();

        $this->addFlash('success', 'Vous avez bien enregistré cet salle dans vos favoris');

        return $this->redirectToRoute('local-clubs');

    }

    /**
     * @Route("/club/remove/{id}", name="club-remove")
     */

    public function removeClub(ClimbingClub $club, EntityManagerInterface $manager){

        $club->removeUser($this->getUser());

        $manager->persist($club);
        $manager->flush();

        $this->addFlash('success', "La salle a bien été supprimé de votre liste");

        return $this->redirectToRoute('local-clubs');

    }

}
