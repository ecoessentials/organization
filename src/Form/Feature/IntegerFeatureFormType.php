<?php

namespace App\Form\Feature;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class IntegerFeatureFormType extends AbstractType
{
    public function getParent()
    {
        return IntegerType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label' => false,
                'constraints' => [
                    new NotNull()
                ]
            ])
            ->setRequired('configuration')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'feature_type_integer';
    }
}