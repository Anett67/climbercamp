<?php

namespace App\Form;

use App\Entity\SearchPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                "required" => false,
                "label" => "Prénom de l'auteur"
            ])

            ->add('lastName', TextType::class, [
                "required" => false,
                "label" => "Nom de l'auteur"
            ])

            ->add('keyword', TextType::class, [
                "required" => false,
                "label" => "Chercher par mot de clé"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchPost::class,
        ]);
    }
}
