<?php

namespace AcMarche\Taxe\Form;

use AcMarche\Taxe\Entity\Nomenclature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NomenclatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add('nom')
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => Nomenclature::class,
        ]);
    }
}
