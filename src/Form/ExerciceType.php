<?php

namespace AcMarche\Taxe\Form;

use AcMarche\Taxe\Entity\Exercice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ExerciceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'annee',
                TextType::class,
                [
                ]
            )
            ->add(
                'file',
                VichFileType::class,
                [
                    'label' => 'Fichier',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Exercice::class,
            ]
        );
    }
}
