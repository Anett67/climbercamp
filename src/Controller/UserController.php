<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserSearch;
use App\Form\UserRoleType;
use App\Form\UserSearchType;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Shows local users or all users if the current user does not have a town defined
     * 
     * @Route("/local-users", name="local-users")
     */
    public function localUsers(UserRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $ville = $this->getUser()->getVille();

        $usersPerPage = 9;

        if($ville){
            $totalUsers = count($repository->findByVille($ville));
            $users = $paginator->paginate(
                $repository->findByVilleWithPagination($ville), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                $usersPerPage /*limit per page*/
            );
        }else{
            $totalUsers = count($repository->findAll());
            $users = $paginator->paginate(
                $repository->findAllWithPagination($ville), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                $usersPerPage /*limit per page*/
            );
        }

        $lastUsersPage = ceil($totalUsers/$usersPerPage);

        $userSearch = new UserSearch();

        $form = $this->createForm(UserSearchType::class, $userSearch);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $totalSearchedUsers = count($repository->findWithSearch($userSearch));
            $SearchedUsers = $repository->findWithSearch($userSearch);

            return $this->render('user/userSearch.html.twig', [
                'users' => $SearchedUsers,
                'form' => $form->createView(),
                'totalSearchedUsers' => $totalSearchedUsers
            ]); 
        }

        return $this->render('user/users.html.twig', [
            'users' => $users,
            'ville' => $ville,
            'form' => $form->createView(),
            'localUsersPage' => true,
            'lastUsersPage' => $lastUsersPage
        ]);
    }

    /**
     * The action that loads more users if the current user scrolls on the bottom of the page
     * 
     * @Route("/users/{page}", name="scroll-user", requirements={"page":"\d+"})
     */

    public function scroll($page, PaginatorInterface $paginator, UserRepository $repository, Request $request): JsonResponse
    {

        $ville = $this->getUser()->getVille();
        $usersPerPage = 9;

        if($ville){
            $users = $paginator->paginate(
                $repository->findByVilleWithPagination($ville), /* query NOT result */
                $request->query->getInt('page', $page), /*page number*/
                $usersPerPage /*limit per page*/
            );
        }else{
            $users = $paginator->paginate(
                $repository->findAllWithPagination($ville), /* query NOT result */
                $request->query->getInt('page', $page), /*page number*/
                $usersPerPage /*limit per page*/
            );
        }

        $response = $this->render('user/user.html.twig', ['users' => $users])->getContent();
        return new JsonResponse($response);
    }



    /**
     * The profil of a user
     * 
     * @Route("/user/{id}", name="single-user", requirements={"id":"\d+"})
     */
    public function singleUser(User $user, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(UserRoleType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $role = $form['userRole']->getData();
            $user->setRoles($role);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('single-user', ['id' => $user->getId()]);
        }

        return $this->render('user/singleUser.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
     
    /**
     * Shows the events saved by the current user
     * 
     * @Route("/user/events/{id}", name="saved-events", requirements={"id":"\d+"})
     */
    public function savedEvents(User $user,EventRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {       
        $events = $paginator->paginate(
            $repository->getFutureSavedEvents($user), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );

        return $this->render('event/events.html.twig', [
            'events' => $events,
            'user' => $user,
            'savedEventsPage' => true
        ]);
    }

}
