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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('lastName')
            ->add('firstName')
            ->add('imageFile', FileType::class, ['required' => false, 'label' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'onchange' => 'openFile(event)'
                ]
            ])
            ->add('presentation')
            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'choice_label' => 'nom',
                'query_builder' => function(VilleRepository $re){
                    return $re->createQueryBuilder('v')
                        ->orderBy('v.nom', 'ASC');
                }
            ])
            ->add('climbingCategorie', EntityType::class, [
                'class' => ClimbingCategorie::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'label'
            ])
            ->add('level', EntityType::class, [
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
