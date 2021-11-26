<?php

namespace App\Form\Feature\Configuration;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('multiple', CheckboxType::class, [
                'label' => 'Choix multiple',
            ])
            ->add('options', CollectionType::class, [
                'label' => 'Options',
                'entry_type' => SelectOptionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'empty_data' => []
            ])
        ;

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $options = $data['options'];
            usort($options, function ($a, $b) { return (int) $a['order'] - (int) $b['order']; });
            foreach ($options as &$option) {
                unset($option['order']);
            }
            $data['options'] = $options;
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
