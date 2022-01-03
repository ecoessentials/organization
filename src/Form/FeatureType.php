<?php

namespace App\Form;

use App\Entity\Feature;
use App\Service\FeatureTypeRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeatureType extends AbstractType
{
    private FeatureTypeRegistry $featureTypeRegistry;

    public function __construct(FeatureTypeRegistry $featureTypeRegistry)
    {
        $this->featureTypeRegistry = $featureTypeRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Feature $data */
        $data = $options['data'];
        $configurationFormClass = $this->featureTypeRegistry->get($data->getType())->getConfigurationFormClass();

        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('type', HiddenType::class)
            ->add('defaultLabel', null, [
                'label' => 'Libellé par défaut',
                'required' => false
            ])
            ->add('configuration', $configurationFormClass, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feature::class,
        ]);
    }
}
