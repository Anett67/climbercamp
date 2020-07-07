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
    public function localUsers(UserRepository $repository, Request $request)
    {
        $ville = $this->getUser()->getVille();

        $users = $repository->findByVille($ville);

        $userSearch = new UserSearch();

        $form = $this->createForm(UserSearchType::class, $userSearch);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $users = $repository->findWithSearch($userSearch);
            
            return $this->redirectToRoute('local-users', ['users' => $users, 'from' => $form->createView()]);
        }

        return $this->render('user/users.html.twig', [
            'users' => $users,
            'ville' => $ville,
            'form' => $form->createView(),
            'hideLocalUsersButton' => true
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
