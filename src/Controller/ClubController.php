<?php

namespace App\Controller;

use App\Entity\ClimbingClub;
use App\Form\ClubType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClimbingClubRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ClubController extends AbstractController
{
    /**
     * This page shows the list of the climbing clubs
     * 
     * @Route("/local-clubs", name="local-clubs")
     */
    public function localClubs(ClimbingClubRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $ville = $this->getUser()->getVille();

        if($ville){
            $clubs = $paginator->paginate(
                $repository->findByVille($ville), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                3 /*limit per page*/
            );
        }else{
            $clubs = $paginator->paginate(
                $repository->findAllWithPagination(), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                3 /*limit per page*/
            );
        }

        return $this->render('club/clubs.html.twig', [
            'clubs' => $clubs,
            'hideLocalClubsButton' => true
        ]);
    }

    /**
     * This page shows the clubs saved by the user as his favorites
     * 
     * @Route("/clubs/saved-clubs", name="saved-clubs")
     */
    public function savedClubs(ClimbingClubRepository $repo, PaginatorInterface $paginator, Request $request): Response
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
     * Page to create a new club
     * 
     * @Route("/clubs/new", name="club-new")
     * 
     * @IsGranted("ROLE_ADMIN")
     */ 
    
    public function createClub(EntityManagerInterface $manager, Request $request): Response
    {

        $club = new ClimbingClub();

        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $club->setUpdatedAt(new DateTime('now'));
            $manager->persist($club);
            $manager->flush();

            $this->addFlash('success', 'La salle a été enregistrée avec succès.');

            return $this->redirectToRoute('local-clubs');
        }

        return $this->render('club/newClub.html.twig',[
            'form' => $form->createView()
        ]);
     }

    /**
     * Page which shows infromation of a climbing club
     * 
     * @Route("/club/{id}", name="single-club", requirements={"id":"\d+"})
     */
    public function singleClub(ClimbingClub $club): Response
    {
        return $this->render('club/singleClub.html.twig', [
            'club' => $club
        ]);
    }

    /**
     * This page shows the users who saved this club
     * 
     * @Route("/clubs/users/{id}", name="club-users", requirements={"id":"\d+"})
     */
    public function clubUsers(ClimbingClub $club): Response
    {
        $users = $club->getUsers();

        return $this->render('user/users.html.twig', [
            'users' => $users,
            'club' => true,
            'search' => false
        ]);
    }

    /**
     * This is the action called when the user saves a club
     * 
     * @Route("/club/save/{id}", name="club-save", requirements={"id":"\d+"})
     */

    public function saveClub(ClimbingClub $club, EntityManagerInterface $manager): Response
    {

        $club->addUser($this->getUser());

        $manager->persist($club);
        $manager->flush();

        $this->addFlash('success', 'Vous avez bien enregistré cet salle dans vos favoris');

        return $this->redirectToRoute('local-clubs');

    }

    /**
     * This is the action called when the user removes a club form the list of his favorites
     * 
     * @Route("/club/remove/{id}", name="club-remove", requirements={"id":"\d+"})
     */

    public function removeClub(ClimbingClub $club, EntityManagerInterface $manager): Response
    {
        $club->removeUser($this->getUser());

        $manager->persist($club);
        $manager->flush();

        $this->addFlash('success', "La salle a bien été supprimé de votre liste");

        return $this->redirectToRoute('local-clubs');

    }

     /**
      * Delete a climbing club from the database 
      *
     * @Route("/profil/club/{id}/delete", name="club-delete", requirements={"id":"\d+"}, methods="delete")
     * 
     * @IsGranted("ROLE_SUPERADMIN")
     */

    public function clubDelete(ClimbingClub $club, EntityManagerInterface $manager, Request $request): Response
    {

        if($this->isCsrfTokenValid('SUP' . $club->getId(), $request->get('_token'))){
            $manager->remove($club);
            $manager->flush();
            $this->addFlash("success",  "La suppression a été effectuée");
        }

        return $this->redirectToRoute('local-clubs');
    }

    /**
     * Update a climbing club
     * 
     * @Route("/profil/club/{id}/update", name="club-update", requirements={"id":"\d+"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */

    public function clubUpdate(ClimbingClub $club, EntityManagerInterface $manager, Request $request): Response
    {

        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($club);
            $manager->flush();

            $this->addFlash('success', 'La modification a bien été effectuée.');

            return $this->redirectToRoute('local-clubs');
        }

        return $this->render('club/newClub.html.twig', [
            'form' => $form->createView(),
            'club' => $club
        ]);

    }
}
