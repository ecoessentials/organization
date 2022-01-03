<?php

namespace App\Form\Feature;

use App\Service\FeatureTypeRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class LengthFeatureFormType extends AbstractType
{

    private FeatureTypeRegistry $featureTypeRegistry;

    public function __construct(FeatureTypeRegistry $featureTypeRegistry)
    {
        $this->featureTypeRegistry = $featureTypeRegistry;
    }

    public function getParent()
    {
        return MoneyType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label' => false,
                'rounding_mode' => \NumberFormatter::ROUND_UP,
                'currency' => false,
                'constraints' => [
                    new NotNull()
                ],
            ])
            ->setRequired('configuration')
            ->setNormalizer('scale', function (Options $options) {
                $configuration = $options['configuration'];
                return $configuration['unit'] - $configuration['precision'];
            })
            ->setNormalizer('divisor', function (Options $options) {
                $configuration = $options['configuration'];
                return pow(10, $configuration['unit']);
            })
        ;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $configuration = $options['configuration'];
        $view->vars['unit'] = $this->featureTypeRegistry->get('length')->decodeUnit($configuration['unit']);
    }


    public function getBlockPrefix(): string
    {
        return 'feature_type_length';
    }
}