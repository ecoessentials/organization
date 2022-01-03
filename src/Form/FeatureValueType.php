<?php

namespace App\Form;

use App\Entity\FeatureValue;
use App\Entity\ProductFeature;
use App\Entity\ProjectItemProduct;
use App\Repository\ProductFeatureRepository;
use App\Repository\ProjectItemModelRepository;
use App\Repository\ProjectItemProductRepository;
use App\Service\FeatureTypeRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeatureValueType extends AbstractType implements DataMapperInterface
{
    private FeatureTypeRegistry $featureTypeRegistry;
    private ProductFeatureRepository $productFeatureRepository;
    private ProjectItemProductRepository $projectItemProductRepository;
    private ProjectItemModelRepository $projectItemModelRepository;

    public function __construct(
        FeatureTypeRegistry          $featureTypeRegistry,
        ProductFeatureRepository     $productFeatureRepository,
        ProjectItemProductRepository $projectItemProductRepository,
        ProjectItemModelRepository   $projectItemModelRepository
    )
    {
        $this->featureTypeRegistry = $featureTypeRegistry;
        $this->productFeatureRepository = $productFeatureRepository;
        $this->projectItemProductRepository = $projectItemProductRepository;
        $this->projectItemModelRepository = $projectItemModelRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('modelSpecific', CheckboxType::class, [
                'label' => 'Voir modèle'
            ])
            ->add('projectItemProduct', HiddenType::class)
            ->add('productFeature', HiddenType::class);
//            ->add('model', HiddenType::class);

//        $builder->get('modelSpecific')->addModelTransformer(new CallbackTransformer(
//            function (?bool $bool) {
//                return $bool ? 'true' : 'false';
//            },
//            function (?string $string) {
//                return $string === 'true';
//            }
//        ));

        $builder->get('projectItemProduct')->addModelTransformer(new CallbackTransformer(
            function (?ProjectItemProduct $entity) {
                return $entity?->getId();
            },
            function (?string $id) {
                return $id === null ? null : $this->projectItemProductRepository->find((int) $id);
            }
        ));

        $builder->get('productFeature')->addModelTransformer(new CallbackTransformer(
            function (?ProductFeature $entity) {
                return $entity?->getId();
            },
            function (?string $id) {
                return $id === null ? null : $this->productFeatureRepository->find((int) $id);
            }
        ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var FeatureValue $featureValue */
            $featureValue = $event->getData();

            if ($featureValue === null) {
                return;
            }

            $form = $event->getForm();

            $productFeature = $featureValue->getProductFeature();
            $feature = $productFeature->getFeature();
            $configuration = $feature->getConfiguration();
            $configuration['options'] = $feature->getOptions()->toArray();
            $options = [
                'required' => !$featureValue->isModelSpecific(),
                'auto_initialize' => false,
                'configuration' => $configuration,
                'label' => $productFeature->getName()
            ];
            if ($featureValue->isModelSpecific()) {
                $options['constraints'] = [];
            }
            $form->add('value', $this->featureTypeRegistry->get($feature->getType())->getFormClass(), $options);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if ($data === null) {
                return;
            }

            $form = $event->getForm();

            $productFeature = $this->productFeatureRepository->find((int) $data['productFeature']);
            $feature = $productFeature->getFeature();
            $configuration = $feature->getConfiguration();
            $configuration['options'] = $feature->getOptions()->toArray();
            $options = [
                'required' => !isset($data['modelSpecific']) || !$data['modelSpecific'],
                'auto_initialize' => false,
                'configuration' => $configuration,
                'label' => $productFeature->getName(),
            ];
            if (isset($data['modelSpecific']) && $data['modelSpecific']) {
                $options['constraints'] = [];
            }
            $form->add('value', $this->featureTypeRegistry->get($feature->getType())->getFormClass(), $options);
        });

        $builder->setDataMapper($this);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FeatureValue::class,
        ]);
    }

    public function mapDataToForms($viewData, \Traversable $forms)
    {
        if ($viewData === null) {
            return null;
        }

        if (!$viewData instanceof FeatureValue) {
            throw new UnexpectedTypeException($viewData, FeatureValue::class);
        }

        $forms = iterator_to_array($forms);

        if (isset($forms['value'])) {
            $forms['value']->setData($viewData->getValue());
        }
        if (isset($forms['modelSpecific'])) {
            $forms['modelSpecific']->setData($viewData->isModelSpecific());
        }
        if (isset($forms['projectItemProduct'])) {
            $forms['projectItemProduct']->setData($viewData->getProjectItemProduct());
        }
        if (isset($forms['productFeature'])) {
            $forms['productFeature']->setData($viewData->getProductFeature());
        }
    }

    public function mapFormsToData(\Traversable $forms, &$viewData)
    {
        $forms = iterator_to_array($forms);

        if (!$viewData instanceof FeatureValue) {
            $viewData = new FeatureValue();
        }

        if ($viewData->getStorageType() === null) {
            /** @var ProductFeature $productFeature */
            $productFeature = $forms['productFeature']->getData();

            $type = $productFeature->getFeature()->getType();
            $storageType = $this->featureTypeRegistry->get($type)->getStorageType();
            $viewData->setStorageType($storageType);
        }

        if (isset($forms['value'])) {
            $viewData->setValue($forms['value']->getData());
        }
        if (isset($forms['modelSpecific'])) {
            $viewData->setModelSpecific($forms['modelSpecific']->getData());
        }
        if (isset($forms['projectItemProduct'])) {
            $viewData->setProjectItemProduct($forms['projectItemProduct']->getData());
        }
        if (isset($forms['productFeature'])) {
            $viewData->setProductFeature($forms['productFeature']->getData());
        }
    }

}
