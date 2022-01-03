<?php

namespace App\Form\Feature;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;

class SelectFeatureFormType extends AbstractType
{
    public function getParent()
    {
        return ChoiceType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (is_array($options['configuration'])
            && isset($options['configuration']['multiple'])
            && !$options['configuration']['multiple']) {
            $builder->addModelTransformer(new CallbackTransformer(
                function ($array) {
                    if (is_array($array) && count($array) > 0) {
                        return $array[0];
                    }

                    return null;
                },
                function ($string): array {
                    if (null !== $string) {
                        return [$string];
                    }

                    return [];
                }
            ));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label' => false,
                'required' => false,
                'constraints' => [
                    new Count(min: 1),
                ]
            ])
            ->setRequired('configuration')
            ->setNormalizer('choices', function (Options $options) {
                if (is_array($options['configuration'])
                    && isset($options['configuration']['options'])
                    && is_array($options['configuration']['options'])) {
                    $choices = [];

                    foreach ($options['configuration']['options'] as $option) {
                        $choices[$option->getName()] = $option->getId();
                    }

                    return $choices;
                }

                return [];
            })
            ->setNormalizer('multiple', function (Options $options) {
                if (is_array($options['configuration']) && isset($options['configuration']['multiple'])) {
                    return $options['configuration']['multiple'];
                }

                return false;
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'feature_type_select';
    }
}