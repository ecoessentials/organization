<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class ProductController extends ControllerBase
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }
    
    protected function getResourceType(): string
    {
        return 'product';
    }

    #[Route('/products', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig');
    }

    #[Route('/products/list', name: 'app_product_list')]
    public function list(): Response
    {
        $resources = $this->productRepository->findBy([], ['name' => 'ASC']);

        return $this->render('product/list.frame.html.twig', [
            'resources' => $resources
        ]);
    }

    #[Route('/products/create', name: 'app_product_create')]
    public function create(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_product_show', [
                'id' => $product->getId()
            ]);
        }

        return $this->renderForm('product/create.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/products/{id}/update', name: 'app_product_update')]
    public function update(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_product_updated', [
                'id' => $product->getId()
            ]);
        }

        return $this->renderForm('product/update.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/products/{id}/updated', name: 'app_product_updated')]
    public function updated(Product $product): Response
    {
        return $this->render('product/updated.stream.html.twig', [
            'resource' => $product
        ], new TurboStreamResponse());
    }

    #[Route('/products/{id}', name: 'app_product_show')]
    public function show(Product $product): Response
    {
        return $this->renderForm('product/show.frame.html.twig', [
            'resource' => $product
        ]);
    }

    #[Route('/products/{id}/delete', name: 'app_product_delete')]
    public function delete(Product $product, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productId = $product->getId();

            $this->entityManager->remove($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_product_deleted', [
                'id' => $productId
            ]);
        }

        return $this->renderForm('product/delete.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/products/{id}/deleted', name: 'app_product_deleted')]
    public function deleted(int $id): Response
    {
        return $this->render('product/deleted.stream.html.twig', [
            'productId' => $id
        ], new TurboStreamResponse());
    }
}
