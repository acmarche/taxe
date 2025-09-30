<?php

namespace AcMarche\Taxe\Form;

use AcMarche\Taxe\Entity\Exercice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
                    'label' => 'Année de l\'exercice',
                    'help' => '',
                    'required' => false,
                ]
            )
            ->add('url', UrlType::class, [
                'label' => 'Url pour www.deliberations.be',
                'required' => false,
                'help' => 'Si une url est remplie, elle prend le dessus par rapport à la pièce jointe'
            ])
            ->add(
                'file',
                VichFileType::class,
                [
                    'required' => false,
                    'label' => 'Pièce jointe',
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
