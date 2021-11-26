<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Entity\Option;
use App\Entity\Product;
use App\Entity\ProductFeature;
use App\Entity\ProductFeatureGroup;
use App\Form\OptionType;
use App\Form\ProductFeatureGroupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class FeatureGroupController extends ControllerBase
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getResourceType(): string
    {
        return 'product_feature_group';
    }

    #[Route('/products/{product<\d+>}/feature-groups', name: 'app_product_feature_group')]
    public function index(Product $product): Response
    {
        return $this->render('product_feature_group/index.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/products/{product<\d+>}/groups/create', name: 'app_product_feature_group_create')]
    public function create(Product $product, Request $request): Response
    {
        $featureGroup = new ProductFeatureGroup();

        $form = $this->createForm(ProductFeatureGroupType::class, $featureGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->addFeatureGroup($featureGroup);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_product_feature_group_created', [
                'product' => $product->getId(),
                'group' => $featureGroup->getId()
            ]);
        }

        return $this->renderForm('product_feature_group/create.frame.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/products/{product<\d+>}/groups/{group<\d+>}/created', name: 'app_product_feature_group_created')]
    public function added(Product $product, ProductFeatureGroup $group)
    {
        return $this->render('product_feature_group/created.stream.html.twig', [
            'group' => $group
        ], new TurboStreamResponse());
    }

    #[Route('/products/{product<\d+>}/groups/{group<\d+>}', name: 'app_product_feature_group_show')]
    public function show(Product $product, ProductFeatureGroup $group)
    {
        return $this->renderForm('product_feature_group/show.frame.html.twig', [
            'product' => $product,
            'group' => $group
        ]);
    }

    #[Route('/products/{product<\d+>}/groups/{group<\d+>}/update', name: 'app_product_feature_group_update')]
    public function update(Product $product, ProductFeatureGroup $group, Request $request): Response
    {
        $form = $this->createForm(ProductFeatureGroupType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_product_feature_group_updated', [
                'product' => $product->getId(),
                'group' => $group->getId()
            ]);
        }

        return $this->renderForm('product_feature_group/update.frame.html.twig', [
            'form' => $form,
            'group' => $group
        ]);
    }

    #[Route('/products/{product<\d+>}/groupes/{group<\d+>}/updated', name: 'app_product_feature_group_updated')]
    public function modified(Product $product, ProductFeatureGroup $group)
    {
        return $this->render('product_feature_group/updated.stream.html.twig', [
            'group' => $group
        ], new TurboStreamResponse());
    }

    #[Route('/products/{product<\d+>}/groupes/{group<\d+>}/delete', name: 'app_product_feature_group_delete')]
    public function delete(Product $product, ProductFeatureGroup $group, Request $request)
    {
        $form = $this->createFormBuilder(null)->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupId = $group->getId();

            $product->removeFeatureGroup($group);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_product_feature_group_deleted', [
                'product' => $product->getId(),
                'group' => $groupId
            ]);
        }

        return $this->renderForm('product_feature_group/delete.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/products/{product<\d+>}/groupes/{group<\d+>}/deleted', name: 'app_product_feature_group_deleted')]
    public function deleted(Product $product, int $group) {
        return $this->render('product_feature_group/deleted.stream.html.twig', [
            'groupId' => $group
        ], new TurboStreamResponse());
    }

    #[Route('/products/{product<\d+>}/groupes/{group<\d+>}/move', name: 'app_product_feature_group_move', methods: ['POST'])]
    public function move(Product $product, ProductFeatureGroup $group, Request $request)
    {
        $position = json_decode($request->getContent(), true)['position'];

        $group->setPosition($position);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
