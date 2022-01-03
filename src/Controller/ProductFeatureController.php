<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Entity\ProductFeature;
use App\Entity\ProductFeatureGroup;
use App\Form\ProductFeatureType;
use App\Repository\ProductFeatureGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class ProductFeatureController extends ControllerBase
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    protected function getResourceType(): string
    {
        return 'product_feature';
    }

    #[Route('/groups/{group<\d+>}/features', name: 'app_product_feature')]
    public function index(ProductFeatureGroup $group): Response
    {
        return $this->render('product_feature/index.html.twig', [
            'group' => $group
        ]);
    }

    #[Route('/groups/{group<\d+>}/features/create', name: 'app_product_feature_create')]
    public function create(ProductFeatureGroup $group, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('feature', EntityType::class, [
                'class' => Feature::class
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array{feature: Feature} $formData */
            $formData = $form->getData();
            $feature = $formData['feature'];
            $productFeature = new ProductFeature();
            $productFeature
                ->setFeature($feature)
                ->setName($feature->getDefaultLabel());
            $group->addFeature($productFeature);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_product_feature_created', [
                'group' => $group->getId(),
                'feature' => $productFeature->getId()
            ]);
        }

        return $this->renderForm('product_feature/create.frame.html.twig', [
            'form' => $form,
            'group' => $group,
        ]);
    }

    #[Route('/groups/{group<\d+>}/features/{feature<\d+>}/created', name: 'app_product_feature_created')]
    public function created(ProductFeatureGroup $group, ProductFeature $feature): Response
    {
        return $this->render('product_feature/created.stream.html.twig', [
            'group' => $group,
            'feature' => $feature
        ], new TurboStreamResponse());
    }

    #[Route('/groups/{group<\d+>}/features/{feature<\d+>}', name: 'app_product_feature_show')]
    public function show(ProductFeatureGroup $group, ProductFeature $feature): Response
    {
        return $this->renderForm('product_feature/show.frame.html.twig', [
            'group' => $group,
            'feature' => $feature
        ]);
    }

    #[Route('/groups/{group<\d+>}/features/{feature<\d+>}/update', name: 'app_product_feature_update')]
    public function update(ProductFeatureGroup $group, ProductFeature $feature, Request $request): Response
    {
        $form = $this->createForm(ProductFeatureType::class, $feature);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_product_feature_updated', [
                'group' => $group->getId(),
                'feature' => $feature->getId()
            ]);
        }

        return $this->renderForm('product_feature/update.frame.html.twig', [
            'form' => $form,
            'feature' => $feature
        ]);
    }


    #[Route('/groups/{group<\d+>}/features/{feature<\d+>}/updated', name: 'app_product_feature_updated')]
    public function updated(ProductFeatureGroup $group, ProductFeature $feature): Response
    {
        return $this->render('product_feature/updated.stream.html.twig', [
            'group' => $group,
            'feature' => $feature
        ], new TurboStreamResponse());
    }

    #[Route('/groups/{group<\d+>}/features/{feature<\d+>}/delete', name: 'app_product_feature_delete')]
    public function delete(ProductFeatureGroup $group, ProductFeature $feature, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $featureId = $feature->getId();

            $feature->setPosition(-1);
            $this->entityManager->flush();
            $group->removeFeature($feature);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_product_feature_deleted', [
                'group' => $group->getId(),
                'feature' => $featureId
            ]);
        }

        return $this->renderForm('product_feature/delete.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/groups/{group<\d+>}/features/{feature<\d+>}/deleted', name: 'app_product_feature_deleted')]
    public function deleted(int $feature): Response
    {
        return $this->render('product_feature/deleted.stream.html.twig', [
            'featureId' => $feature
        ], new TurboStreamResponse());
    }

    #[Route('/groups/{group<\d+>}/features/{feature<\d+>}/move', name: 'app_product_feature_move', methods: ['POST'])]
    public function move(ProductFeatureGroup $group, ProductFeature $feature, Request $request, ProductFeatureGroupRepository $productFeatureGroupRepository): Response
    {
        /** @var array{position: int, dest: int|null} $requestContent */
        $requestContent = json_decode($request->getContent(), true);
        $position = $requestContent['position'];
        $destId = $requestContent['dest'];

        $feature->setPosition($position);
        if ($destId !== null) {
            $group->removeFeature($feature);
            $productFeatureGroupRepository->find($destId)?->addFeature($feature);
        }
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
