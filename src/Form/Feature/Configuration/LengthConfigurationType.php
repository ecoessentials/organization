<?php

namespace App\Form\Feature\Configuration;

use App\Service\FeatureTypeRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LengthConfigurationType extends AbstractType
{
    private FeatureTypeRegistry $featureTypeRegistry;

    public function __construct(FeatureTypeRegistry $featureTypeRegistry)
    {
        $this->featureTypeRegistry = $featureTypeRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $units = array_flip($this->featureTypeRegistry->get('length')->getUnits());

        $builder
            ->add('unit', ChoiceType::class, [
                'label' => 'Unité',
                'choices' => $units
            ])
            ->add('precision', ChoiceType::class, [
                'label' => 'Précision',
                'choices' => $units
            ])
            ->add('min', IntegerType::class, [
                'label' => 'Minimum (en µ)',
                'required' => false,
            ])
            ->add('max', IntegerType::class, [
                'label' => 'Maximum (en µ)',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }

}
