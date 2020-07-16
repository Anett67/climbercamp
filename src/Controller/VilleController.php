<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="ville")
     */
    public function index(Request $request, EntityManagerInterface $manager): Response
    {   
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);

        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){

            $manager->persist($ville);
            $manager->flush();

            $this->addFlash('success', 'Le nouvelle ville a bien été enregistré');

            return $this->redirectToRoute('ville');
        }


        return $this->render('ville/newVille.html.twig', [
            'villeForm' => $villeForm->createView()
        ]);
    }
}
