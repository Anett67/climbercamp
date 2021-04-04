<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Level;
use App\Entity\Ville;
use App\Entity\ClimbingCategorie;
use App\Repository\VilleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('lastName', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('imageFile', FileType::class, ['required' => false, 'label' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'onchange' => 'openFile(event)'
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'label' => 'Présentation',
                'required' => false
            ])
            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'choice_label' => 'nom',
                'query_builder' => function(VilleRepository $re){
                    return $re->createQueryBuilder('v')
                        ->orderBy('v.nom', 'ASC');
                }
            ])
            ->add('climbingCategorie', EntityType::class, [
                'label' => "Types d'escalade",
                'class' => ClimbingCategorie::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'label'
            ])
            ->add('level', EntityType::class, [
                'label' => 'Niveau',
                'class' => Level::class,
                'choice_label' => 'label'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
