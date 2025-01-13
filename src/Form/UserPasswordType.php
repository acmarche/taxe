<?php

namespace AcMarche\Taxe\Form;

use AcMarche\Taxe\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add(
                'plainPassword',
                TextType::class,
                [
                    'label' => 'Mot de pase',
                ]
            );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
