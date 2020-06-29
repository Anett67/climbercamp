<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $passwordCrypte = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordCrypte);
            $user->setRoles("ROLE_USER");
            $user->setUpdatedAt(new DateTime('now'));
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('login');

        }

        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
            

        ]);
    }

    /**
     * @Route("/login", name="login")
     */

     public function login(AuthenticationUtils $util){

        return $this->render('security/login.html.twig', [
            'lastUserName' => $util->getLastUsername(),
            "error" =>$util->getLastAuthenticationError()
        ]);

    }

    /**
     * @Route("/deconnexion", name="logout")
     */

    public function logout(){

    }
}
