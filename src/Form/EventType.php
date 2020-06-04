<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Ville;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('location')
            ->add('eventDate', DateTimeType::class, [
                'years' => range(2020, 2025),
            ])
            ->add('ville', EntityType::class, ['class' => Ville::class, 'choice_label' => 'nom', 'required' => true ])
            ->add('imageFile', FileType::class, ['required' => false, 'label' => false,
            'attr' => [
                'accept' => 'image/*',
                'onchange' => 'openFile(event)'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
