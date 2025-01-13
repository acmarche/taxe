<?php

namespace AcMarche\Taxe\Form;

use AcMarche\Taxe\Entity\Nomenclature;
use AcMarche\Taxe\Entity\Taxe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTaxeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add(
                'nom',
                SearchType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'nomenclature',
                EntityType::class,
                [
                    'required' => false,
                    'class' => Nomenclature::class,
                ]
            );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Taxe::class,
            ]
        );
    }
}
