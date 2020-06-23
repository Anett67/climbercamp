<?php

namespace App\Controller;

use App\Entity\ClubSearch;
use App\Entity\ClimbingClub;
use App\Form\ClubSearchType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClimbingClubRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClubController extends AbstractController
{
    /**
     * @Route("/local-clubs", name="local-clubs")
     */
    public function localClubs(ClimbingClubRepository $repository, Request $request, PaginatorInterface $paginator)
    {

        $ville = $this->getUser()->getVille();

        $clubs = $repository->findByVille($ville);

        $clubs = $paginator->paginate(
            $repository->findByVille($ville), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        $clubSearch = new ClubSearch();

        $form = $this->createForm(ClubSearchType::class, $clubSearch);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //$clubs = $repository->findWithSearch($clubSearch);

            $clubs = $paginator->paginate(
                $repository->findWithSearch($clubSearch), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                5 /*limit per page*/
            );

            return $this->render('club/clubs.html.twig', [
                'clubs' => $clubs,
                'form' => $form->createView()
            ]);

        }

        return $this->render('club/clubs.html.twig', [
            'clubs' => $clubs,
            'form' => $form->createView(),
            'hideLocalClubsButton' => true
        ]);
    }

    /**
     * @Route("/clubs/saved-clubs", name="saved-clubs")
     */
    public function savedClubs(ClimbingClubRepository $repo, PaginatorInterface $paginator, Request $request)
    {   
        $user = $this->getUser();

        $clubs = $paginator->paginate(
            $repo->findSavedClubs($user), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

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
        return $this->render('club/singleClub.html.twig', [
            'club' => $club
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
