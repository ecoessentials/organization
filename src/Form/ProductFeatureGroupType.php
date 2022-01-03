<?php

namespace App\Form;

use App\Entity\ProductFeatureGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFeatureGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Libellé',
                'attr' => [
                    'placeholder' => 'Nouveau groupe de caractéristiques'
                ]
            ])
            ->add('position', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFeatureGroup::class,
        ]);
    }
}
