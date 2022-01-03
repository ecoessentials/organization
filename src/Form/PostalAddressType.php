<?php

namespace App\Form;

use App\Entity\PostalAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostalAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', null, [
                'label' => 'Rue'
            ])
            ->add('postalCode', null, [
                'label' => 'Code postal',
                'required' => false
            ])
            ->add('city', null, [
                'label' => 'Ville'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostalAddress::class,
        ]);
    }
}
