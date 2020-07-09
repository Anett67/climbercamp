<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userRole', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Changer le rôle',
                'choices' => [
                    'Abonné'            => 'ROLE_USER',
                    'Editeur'           => 'ROLE_ADMIN',
                    'Administrateur'    => 'ROLE_SUPERADMIN'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
