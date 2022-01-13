<?php

namespace AcMarche\Taxe\Form;

use AcMarche\Taxe\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = ['ROLE_TAXE_ADMIN', 'ROLE_PATRIMOINE_ADMIN', 'ROLE_ENQUETE_ADMIN'];
        $builder
            ->remove('plainPassword')
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => array_combine($roles, $roles),
                    'multiple' => true,
                    'expanded' => true,
                ]
            );
    }

    public function getParent(): ?string
    {
        return UserType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
