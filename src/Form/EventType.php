<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Ville;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TypeTextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Détails',
                'required' => false
            ])
            ->add('location', TypeTextType::class, [
                'label' => 'Addresse',
                'required' => true            
            ])
            ->add('eventDate', DateTimeType::class, [
                'label' => 'Date et heure',
                'years' => range(2020, 2025),
                'required' => false
            ])
            ->add('ville', EntityType::class, ['class' => Ville::class, 'choice_label' => 'nom', 'required' => true ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
