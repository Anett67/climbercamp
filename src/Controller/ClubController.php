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

class ClubController extends AbstractController
{
    /**
     * @Route("/local-clubs", name="local-clubs")
     */
    public function localClubs(ClimbingClubRepository $repository, Request $request, PaginatorInterface $paginator)
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
     * @Route("/clubs/new", name="club-new")
     */ 
    
    public function createClub(EntityManagerInterface $manager, Request $request){

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
     * @Route("/club/{id}", name="single-club", requirements={"id":"\d+"})
     */
    public function singleClub(ClimbingClub $club)
    {
        return $this->render('club/singleClub.html.twig', [
            'club' => $club
        ]);
    }

    /**
     * @Route("/clubs/users/{id}", name="club-users", requirements={"id":"\d+"})
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
     * @Route("/club/save/{id}", name="club-save", requirements={"id":"\d+"})
     */

    public function saveClub(ClimbingClub $club, EntityManagerInterface $manager){

        $club->addUser($this->getUser());

        $manager->persist($club);
        $manager->flush();

        $this->addFlash('success', 'Vous avez bien enregistré cet salle dans vos favoris');

        return $this->redirectToRoute('local-clubs');

    }

    /**
     * @Route("/club/remove/{id}", name="club-remove", requirements={"id":"\d+"})
     */

    public function removeClub(ClimbingClub $club, EntityManagerInterface $manager){

        if($this->getUser()){
            $club->removeUser($this->getUser());

            $manager->persist($club);
            $manager->flush();

            $this->addFlash('success', "La salle a bien été supprimé de votre liste");

            return $this->redirectToRoute('local-clubs');
        }else{
            return $this->redirectToRoute('login');
        }
    }

     /**
     * @Route("/profil/club/{id}/delete", name="club-delete", requirements={"id":"\d+"}, methods="delete")
     */

    public function clubDelete(ClimbingClub $club, EntityManagerInterface $manager, Request $request){

        if($this->isCsrfTokenValid('SUP' . $club->getId(), $request->get('_token'))){
            $manager->remove($club);
            $manager->flush();
            $this->addFlash("success",  "La suppression a été effectuée");
        }

        return $this->redirectToRoute('local-clubs');
    }

    /**
     * @Route("/profil/club/{id}/update", name="club-update", requirements={"id":"\d+"})
     */

    public function clubUpdate(ClimbingClub $club, EntityManagerInterface $manager, Request $request){

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
