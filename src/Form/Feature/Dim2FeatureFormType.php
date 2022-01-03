<?php

namespace App\Form\Feature;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class Dim2FeatureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configuration = $options['configuration'];

        foreach (['x', 'y'] as $field) {
            $builder->add($field, LengthFeatureFormType::class, [
                'label' => false,
                'configuration' => $configuration
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('configuration');
    }

    public function getBlockPrefix(): string
    {
        return 'feature_type_dim2';
    }
}