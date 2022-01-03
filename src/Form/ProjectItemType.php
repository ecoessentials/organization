<?php

namespace App\Form;

use App\Entity\ProjectItem;
use App\Entity\ProjectItemProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('products', CollectionType::class, [
                'label' => false,
                'entry_type' => ProjectItemProductType::class,
                'entry_options' => [
                    'label' => false
                ]
            ])
            ->add('action', HiddenType::class, [
                'mapped' => false
            ])
            ->add('note', null, [
                'label' => 'Notes générales'
            ])
            ->add('customerNote', null, [
                'label' => 'Notes à destination du client'
            ])
            ->add('supplierNote', null, [
                'label' => 'Notes à destination des fournisseurs'
            ])
            ->add('internalNote', null, [
                'label' => 'Notes internes'
            ])
            ->add('models', CollectionType::class, [
                'label' => false,
                'entry_type' => ProjectItemModelType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectItem::class
        ]);
    }
}
