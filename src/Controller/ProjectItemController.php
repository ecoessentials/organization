<?php

namespace App\Controller;

use App\Entity\FeatureValue;
use App\Entity\Product;
use App\Entity\Project;
use App\Entity\ProjectItem;
use App\Entity\ProjectItemModel;
use App\Entity\ProjectItemProduct;
use App\Entity\ProjectItemSupplierEstimate;
use App\Entity\ThirdParty;
use App\Form\ProjectItemProductType;
use App\Form\ProjectItemType;
use App\Service\FeatureTypeRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class ProjectItemController extends ControllerBase
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/projects/{project}/items', name: 'app_project_item')]
    public function index(Project $project): Response
    {
        return $this->render('project_item/index.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/projects/{project}/items/create', name: 'app_project_item_create')]
    public function create(Project $project, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('product', EntityType::class, [
                'class' => Product::class
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{product: Product} $data */
            $data = $form->getData();
            $product = $data['product'];

            return $this->redirectToRoute('app_project_item_create_product', [
                'project' => $project->getId(),
                'product' => $product->getId()
            ]);
        }

        return $this->renderForm('project_item/create.frame.html.twig', [
            'form' => $form,
            'project' => $project
        ]);
    }

    #[Route('/projects/{project}/items/create/{product}', name: 'app_project_item_create_product')]
    public function createProduct(Project $project, Product $product, Request $request, FeatureTypeRegistry $featureTypeRegistry): Response
    {
        $item = $this->makeItem($product, $featureTypeRegistry);

        $form = $this->createForm(ProjectItemType::class, $item);

        $form->handleRequest($request);

        $response = $this->handleModelActions($form, $item);

        if ($response) {
            return $response;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($item->getModels() as $model) {
                $model->setProjectItem($item);
            }
            $project->addItem($item);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project_item_created', [
                'project' => $project->getId(),
                'item' => $item->getId()
            ]);
        }

        dump(iterator_to_array($form->getErrors(true)));

        return $this->renderForm('project_item/create_product.frame.html.twig', [
            'form' => $form,
            'project' => $project,
            'item' => $item,
            'product' => $product
        ]);
    }

    #[Route('/projects/{project}/items/{item}/created', name: 'app_project_item_created')]
    public function created(Project $project, ProjectItem $item): Response
    {
        return $this->render('project_item/created.stream.html.twig', [
            'project' => $project,
            'item' => $item
        ], new TurboStreamResponse());
    }

    #[Route('/projects/{project}/items/{item}/add', name: 'app_project_item_add')]
    public function add(Project $project, ProjectItem $item, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('product', EntityType::class, [
                'class' => Product::class
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{product: Product} $data */
            $data = $form->getData();
            $product = $data['product'];

            return $this->redirectToRoute('app_project_item_add_product', [
                'project' => $project->getId(),
                'item' => $item->getId(),
                'product' => $product->getId()
            ]);
        }

        return $this->renderForm('project_item/add.frame.html.twig', [
            'form' => $form,
            'project' => $project,
            'item' => $item
        ]);
    }

    #[Route('/projects/{project}/items/{item}/add/{product}', name: 'app_project_item_add_product')]
    public function addProduct(Project $project, ProjectItem $item, Product $product, Request $request, FeatureTypeRegistry $featureTypeRegistry): Response
    {
        $itemProduct = $this->getItemProduct($product, $featureTypeRegistry);

        $form = $this->createForm(ProjectItemProductType::class, $itemProduct);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->addProduct($itemProduct);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project_item_created', [
                'project' => $project->getId(),
                'item' => $item->getId()
            ]);
        }

        return $this->renderForm('project_item/add_product.frame.html.twig', [
            'form' => $form,
            'project' => $project,
            'item' => $item,
            'product' => $product,
            'itemProduct' => $itemProduct
        ]);
    }

    #[Route('/projects/{project}/items/{item}', name: 'app_project_item_show')]
    public function show(Project $project, ProjectItem $item): Response
    {
        return $this->renderForm('project_item/show.frame.html.twig', [
            'project' => $project,
            'item' => $item
        ]);
    }

    #[Route('/projects/{project}/items/{item}/move', name: 'app_project_item_move', methods: ['POST'])]
    public function move(ProjectItem $item, Request $request): Response
    {
        /** @var array{position: int} $data */
        $data = json_decode($request->getContent(), true);
        $position = $data['position'];

        $item->setPosition($position);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/projects/{project}/items/{item}/products/{product}/move', name: 'app_project_item_product_move', methods: ['POST'])]
    public function moveProduct(ProjectItemProduct $product, Request $request): Response
    {
        /** @var array{position: int} $data */
        $data = json_decode($request->getContent(), true);
        $position = $data['position'];

        $product->setPosition($position);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/projects/{project}/items/{item}/update', name: 'app_project_item_update')]
    public function update(Project $project, ProjectItem $item, Request $request): Response
    {
        $form = $this->createForm(ProjectItemType::class, $item);

        $form->handleRequest($request);

        $response = $this->handleModelActions($form, $item);

        if ($response) {
            return $response;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($item->getModels() as $model) {
                $model->setProjectItem($item);
            }
            $this->entityManager->flush();
            return $this->redirectToRoute('app_project_item_updated', [
                'project' => $project->getId(),
                'item' => $item->getId()
            ]);
        }

        return $this->renderForm('project_item/update.frame.html.twig', [
            'form' => $form,
            'project' => $project,
            'item' => $item
        ]);
    }

    #[Route('/projects/{project}/items/{item}/updated', name: 'app_project_item_updated')]
    public function updated(Project $project, ProjectItem $item): Response
    {
        return $this->render('project_item/updated.stream.html.twig', [
            'project' => $project,
            'item' => $item
        ], new TurboStreamResponse());
    }

    #[Route('/projects/{project}/items/{item}/delete', name: 'app_project_item_delete')]
    public function delete(Project $project, ProjectItem $item, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemId = $item->getId();

            $item->setPosition(-1);
            $this->entityManager->flush();
            $project->removeItem($item);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project_item_deleted', [
                'project' => $project->getId(),
                'item' => $itemId
            ]);
        }

        return $this->renderForm('product_feature/delete.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/projects/{project}/items/{item}/deleted', name: 'app_project_item_deleted')]
    public function deleted(int $item): Response
    {
        return $this->render('project_item/deleted.stream.html.twig', [
            'itemId' => $item
        ], new TurboStreamResponse());
    }

    #[Route('/projects/{project}/items/{item}/products/{product}/delete', name: 'app_project_item_product_delete')]
    public function deleteProduct(Project $project, ProjectItem $item, ProjectItemProduct $product, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $productId = $product->getId();
            $product->setPosition(-1);
            $this->entityManager->flush();
            $item->removeProduct($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project_item_product_deleted', [
                'project' => $project->getId(),
                'item' => $item->getId(),
                'product' => $productId
            ]);
        }

        return $this->renderForm('project_item/delete_product.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/projects/{project}/items/{item}/products/{product}/deleted', name: 'app_project_item_product_deleted')]
    public function productDeleted(int $product): Response
    {
        return $this->render('project_item/product_deleted.stream.html.twig', [
            'productId' => $product
        ], new TurboStreamResponse());
    }

    #[Route('/projects/{project}/items/{item}/create-estimate', name: 'app_project_item_create_estimate')]
    public function createEstimate(Project $project, ProjectItem $item, Request $request): Response
    {
        $estimateIds = implode(
            ',',
            array_map(
                fn (ProjectItemSupplierEstimate $estimate) => $estimate->getSupplier()?->getId(),
                $item->getSupplierEstimates()->toArray()
            )
        );

        $form = $this->createFormBuilder()
            ->add('supplier', EntityType::class, [
                'class' => ThirdParty::class,
                'query_builder' => function (EntityRepository $er) use ($estimateIds) {
                    $qb = $er
                        ->createQueryBuilder('t')
                        ->where('t.supplier = true');
                    if ($estimateIds) {
                        $qb->andWhere("t.id NOT IN ($estimateIds)");
                    }
                    return $qb->orderBy('t.name');
                }
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{supplier: ThirdParty} $data */
            $data = $form->getData();
            $supplier = $data['supplier'];

            $estimate = new ProjectItemSupplierEstimate();
            $estimate->setSupplier($supplier);

            $item->addSupplierEstimate($estimate);

            $this->entityManager->persist($estimate);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project_item_estimate_created', [
                'project' => $project->getId(),
                'item' => $item->getId(),
                'estimate' => $estimate->getId()
            ]);
        }

        return $this->renderForm('project_item/create_estimate.frame.html.twig', [
            'form' => $form,
            'project' => $project,
            'item' => $item
        ]);
    }

    #[Route('/projects/{project}/items/{item}/estimates/{estimate}/created', name: 'app_project_item_estimate_created')]
    public function estimateCreated(Project $project, ProjectItem $item, ProjectItemSupplierEstimate $estimate): Response
    {
        return $this->render('project_item/estimate_created.stream.html.twig', [
            'project' => $project,
            'item' => $item,
            'estimate' => $estimate
        ], new TurboStreamResponse());
    }

    #[Route('/projects/{project}/items/{item}/estimates/{estimate}/delete', name: 'app_project_item_estimate_delete')]
    public function deleteEstimate(Project $project, ProjectItem $item, ProjectItemSupplierEstimate $estimate, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estimateId = $estimate->getId();

            $item->removeSupplierEstimate($estimate);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project_item_estimate_deleted', [
                'project' => $project->getId(),
                'item' => $item->getId(),
                'estimate' => $estimateId
            ]);
        }

        return $this->renderForm('project_item/delete_estimate.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/projects/{project}/items/{item}/estimates/{estimate}/deleted', name: 'app_project_item_estimate_deleted')]
    public function estimateDeleted(Project $project, ProjectItem $item, int $estimate): Response
    {
        return $this->render('project_item/estimate_deleted.stream.html.twig', [
            'project' => $project,
            'item' => $item,
            'estimateId' => $estimate
        ], new TurboStreamResponse());
    }

    protected function getResourceType(): string
    {
        return 'project_item';
    }

    /**
     * @param Product $product
     * @param FeatureTypeRegistry $featureTypeRegistry
     * @return ProjectItemProduct
     */
    protected function getItemProduct(Product $product, FeatureTypeRegistry $featureTypeRegistry): ProjectItemProduct
    {
        $itemProduct = new ProjectItemProduct();

        foreach ($product->getFeatureGroups() as $group) {
            foreach ($group->getFeatures() as $feature) {
                $featureValue = new FeatureValue();
                $featureType = $featureTypeRegistry->get($feature->getFeature()->getType());
                $featureValue
                    ->setProductFeature($feature)
                    ->setStorageType($featureType->getStorageType());
                if ($featureType->getStorageType() === 'compound') {
                    foreach ($featureType->getChildren() as $childName => $typeName) {
                        $type = $featureTypeRegistry->get($typeName);
                        $featureValueChild = new FeatureValue();
                        $featureValueChild
                            ->setChildName($childName)
                            ->setProductFeature($feature)
                            ->setStorageType($type->getStorageType());
                        $featureValue->addChild($featureValueChild);
                    }
                }
                $itemProduct->addFeatureValue($featureValue);
            }
        }

        $itemProduct
            ->setProduct($product)
            ->setName($product->getName());

        return $itemProduct;
    }

    /**
     * @param Product $product
     * @param FeatureTypeRegistry $featureTypeRegistry
     * @return ProjectItem
     */
    protected function makeItem(Product $product, FeatureTypeRegistry $featureTypeRegistry): ProjectItem
    {
        $item = new ProjectItem();

        $itemProduct = $this->getItemProduct($product, $featureTypeRegistry);

        $item
            ->setName($product->getName())
            ->addProduct($itemProduct)
            ->addModel(new ProjectItemModel());
        return $item;
    }

    private function handleModelActions(FormInterface $form, ProjectItem $item): ?Response
    {
        /** @var string $action */
        $action = $form->get('action')->getData();

        if ($action === 'add_model') {
            $model = new ProjectItemModel();
            foreach ($item->getProducts() as $product) {
                foreach ($product->getFeatureValues() as $featureValue) {
                    if ($featureValue->isModelSpecific() && $featureValue->getModel() === null) {
                        $modelFeatureValue = clone $featureValue;
                        $modelFeatureValue->setModelSpecific(false);
                        $model->addFeatureValue($modelFeatureValue);
                    }
                }
            }
            $item->addModel($model);
            $form = $this->createForm(ProjectItemType::class, $item);
            return $this->render('project_item/models.stream.html.twig', [
                'form' => $form->createView(),
                'item' => $item
            ], new TurboStreamResponse());
        } elseif (str_starts_with($action,'remove_model_')) {
            $modelIndex = (int) substr($action, strlen('remove_model_'));
            $item->removeModel($item->getModels()->get($modelIndex));
            $form = $this->createForm(ProjectItemType::class, $item);
            return $this->render('project_item/models.stream.html.twig', [
                'form' => $form->createView(),
                'item' => $item
            ], new TurboStreamResponse());
        } elseif ($action === 'model_specific') {
            foreach ($item->getProducts() as $product) {
                foreach ($product->getFeatureValues() as $featureValue) {
                    if ($featureValue->isModelSpecific()) {
                        foreach ($item->getModels() as $model) {
                            foreach ($model->getFeatureValues() as $modelFeatureValue) {
                                if ($modelFeatureValue->getModel() === $model) {
                                    break 2;
                                }
                            }
                            $modelFeatureValue = clone $featureValue;
                            $modelFeatureValue->setModelSpecific(false);
                            $model->addFeatureValue($modelFeatureValue);
                        }
                    }
                }
            }
            return $this->render('project_item/create_product.frame.html.twig', [
                'form' => $form->createView(),
                'project' => $item->getProject(),
                'item' => $item,
                'product' => $product
            ]);
        } elseif (str_starts_with($action, 'model_specific_')) {
            $featureValueId = (int) substr($action, strlen('model_specific_'));
            foreach ($item->getProducts() as $product) {
                foreach ($product->getFeatureValues() as $featureValue) {
                    if ($featureValue->getId() === $featureValueId) {
                        if ($featureValue->isModelSpecific()) {
                            $featureValue->setModelSpecific(false);
                        } else {
                            $featureValue->setModelSpecific(true);
                            foreach ($item->getModels() as $model) {
                                $modelFeatureValue = clone $featureValue;
                                $modelFeatureValue->setModelSpecific(false);
                                $model->addFeatureValue($modelFeatureValue);
                            }
                        }
                        $form = $this->createForm(ProjectItemType::class, $item);
                        return $this->render('project_item/update.frame.html.twig', [
                            'form' => $form->createView(),
                            'project' => $item->getProject(),
                            'item' => $item
                        ]);
                    }
                }
            }
        }
        return null;
    }
}
