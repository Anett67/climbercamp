<?php

namespace App\Form;

use App\Entity\Ville;
use App\Entity\ClimbingClub;
use App\Entity\ClimbingCategorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('imageFile', FileType::class, ['required' => false, 'label' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'onchange' => 'openFile(event)'
                ]])
            ->add('email')
            ->add('telephone')
            ->add('addresse')
            ->add('ville', EntityType::class, ['class' => Ville::class, 'choice_label' => 'nom', 'required' => true ])
            ->add('climbingCategories', EntityType::class, [
                'class' => ClimbingCategorie::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClimbingClub::class,
        ]);
    }
}
