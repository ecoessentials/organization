<?php

namespace App\Form;

use App\Entity\ProjectItemModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectItemModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('count', null, [
                'label' => 'Nb de modèles'
            ])
            ->add('quantities', TextType::class, [
                'label' => 'Quantité',
//                'help' => 'Pour indiquer plusieurs quantités, séparez les par des ",", ajoutez un "+" pour les quantités marginales, ex : 100,500,1000,100+',
            ])
            ->add('reference', null, [
                'label' => 'Référence'
            ])
            ->add('featureValues', CollectionType::class, [
                'label' => false,
                'entry_type' => FeatureValueType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'label' => false
                ]
            ])
        ;

        $builder->get('quantities')->addModelTransformer(new CallbackTransformer(
            function (?array $quantitiesAsArray) {
                if ($quantitiesAsArray === null) {
                    return '';
                }
                return implode(
                    ', ',
                    array_map(
                        static function ($item) {
                            return $item < 0 ? (-$item) . '+' : $item;
                        },
                        $quantitiesAsArray
                    )
                );
            },
            function (?string $quantitiesAsString) {
                if ($quantitiesAsString === null) {
                    return [];
                }
                $quantities = array_map(
                    static function ($item) {
                        $item = trim($item);
                        if (str_contains($item, '+')) {
                            return -(int)trim($item, '+');
                        }
                        return (int)$item;
                    },
                    explode(',', $quantitiesAsString)
                );
                usort($quantities, static function (int $a, int $b) {
                    if ($a > 0 && $b > 0) {
                        return $a - $b;
                    }
                    return $b - $a;
                });
                return array_filter(array_unique($quantities), static function (int $item) {
                    return $item !== 0;
                });
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectItemModel::class,
        ]);
    }
}
