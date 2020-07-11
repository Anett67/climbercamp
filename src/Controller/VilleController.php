<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Entity\Country;
use App\Form\VilleType;
use App\Form\CountryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="ville")
     */
    public function index(Request $request, EntityManagerInterface $manager)
    {   
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);

        // $country = new Country();
        // $countryForm = $this->createForm(CountryType::class, $country);

        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){

            $manager->persist($ville);
            $manager->flush();

            $this->addFlash('success', 'Le nouvelle ville a bien été enregistré');

            return $this->redirectToRoute('ville');
        }

        // $countryForm->handleRequest($request);

        // if($countryForm->isSubmitted() && $countryForm->isValid()){
        //     $manager->persist($country);
        //     $manager->flush();

        //     $this->addFlash('success', 'Le pays a buen été enregistré');

        //     return $this->redirectToRoute('ville');
        // }

        return $this->render('ville/newVille.html.twig', [
            'villeForm' => $villeForm->createView()
        ]);
    }
}
