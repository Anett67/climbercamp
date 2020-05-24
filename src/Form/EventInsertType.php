<?php

namespace App\Form;

use App\Entity\Ville;
use App\Entity\EventInsert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventInsertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('eventDate', DateTimeType::class, [
                'years' => range(2020, 2025),
            ])
            ->add('location')
            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'choice_label' => 'nom'
            ])
            ->add('imageFile', FileType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventInsert::class,
        ]);
    }
}
