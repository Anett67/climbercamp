<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserSearch;
use App\Form\UserSearchType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/local-users", name="local-users")
     */
    public function localUsers(UserRepository $repsitory)
    {
        $ville = $this->getUser()->getVille();

        $users = $repsitory->findByVille($ville);

        return $this->render('user/users.html.twig', [
            'users' => $users,
            'search' => false
        ]);
    }

    /**
     * @Route("/users/search", name="user-search")
     */
    public function userSearch(UserRepository $repository, Request $request)
    {
        $userSearch = new UserSearch();

        $form = $this->createForm(UserSearchType::class, $userSearch);

        $form->handleRequest($request);

        $users = $repository->findWithSearch($userSearch);

        return $this->render('user/users.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
            'search' => true 
        ]);
    }

    /**
     * @Route("/user/{id}", name="single-user")
     */
    public function singleUser(User $user)
    {
        return $this->render('user/singleUser.html.twig', [
            'user' => $user
        ]);
    }

}
