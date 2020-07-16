<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Resource_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * The page which allows the user to edit his profile
     * 
     * @Route("/profil", name="profil")
     */
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $user->setImageFile(NULL);

            $this->addFlash('success', 'Votre profil a bien été mis à jour.');

            return $this->redirectToRoute('profil');
        }

        return $this->render('profil/profil.html.twig', [
           'form' => $form->createView(),
           'hideProfilButton' => true
        ]);
    }


}
