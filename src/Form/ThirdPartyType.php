<?php

namespace App\Form;

use App\Entity\ThirdParty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThirdPartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('email', null, [
                'label' => 'E-mail'
            ])
            ->add('telephone', null, [
                'label' => 'Téléphone'
            ])
            ->add('website', null, [
                'label' => 'Site web'
            ])
            ->add('customer', null, [
                'label' => 'Client'
            ])
            ->add('supplier', null, [
                'label' => 'Fournisseur'
            ])
            ->add('postalAddress', PostalAddressType::class, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ThirdParty::class,
        ]);
    }
}
