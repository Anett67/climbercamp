<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'users' => $users
        ]);
    }

    /**
     * @Route("/user-search", name="user-search")
     */
    public function userSearch()
    {
        return $this->render('user/users.html.twig', [
            
        ]);
    }

}
