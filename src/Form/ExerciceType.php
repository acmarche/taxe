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
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
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

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Exercice::class,
            ]
        );
    }
}
