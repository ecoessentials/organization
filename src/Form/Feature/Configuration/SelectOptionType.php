<?php

namespace App\Form\Feature\Configuration;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SelectOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('order', HiddenType::class)
            ->add('label', TextType::class, [
                'label' => 'Libellé'
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
           $option = $event->getData();
           if (!isset($option['id'])) {
                $option['id'] = Uuid::uuid4();
           }
           $event->setData($option);
        });
    }
}