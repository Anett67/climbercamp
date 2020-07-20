<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
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

            return $this->redirectToRoute('inscription-success');

        }

        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
            

        ]);
    }
    /**
     * @Route("/inscription/success", name="inscription-success")
     */

    public function success(){

        return $this->render('security/successfulSignUp.html.twig');

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
     * @Route("/profil/password", name="password-change")
     */

    public function changePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){

        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $form['password']->getData();
            $passwordCrypte = $encoder->encodePassword($user, $password);
            $user->setPassword($passwordCrypte)->setUpdatedAt(new DateTime('now'));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès');

            return $this->redirectToRoute('profil');
        }

        return $this->render('profil/passwordChange.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/deconnexion", name="logout")
     */

    public function logout(){

    }
}
