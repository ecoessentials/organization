<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Form\FeatureType;
use App\Repository\FeatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class FeatureController extends ControllerBase
{
    private FeatureRepository $featureRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(FeatureRepository $featureRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->featureRepository = $featureRepository;
    }

    protected function getResourceType(): string
    {
        return 'feature';
    }

    #[Route('/features', name: 'app_feature')]
    public function index(): Response
    {
        return $this->render('feature/index.html.twig');
    }

    #[Route('/features/list', name: 'app_feature_list')]
    public function list(): Response
    {
        $resources = $this->featureRepository->findBy([], ['name' => 'ASC']);

        return $this->render('feature/list.frame.html.twig', [
            'resources' => $resources
        ]);
    }

    #[Route('/features/create/{type}', name: 'app_feature_create_type')]
    public function create(Request $request, string $type): Response
    {
        $feature = new Feature();
        $feature->setType($type);
        $form = $this->createForm(FeatureType::class, $feature);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($feature);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_feature_show', [
                'id' => $feature->getId()
            ]);
        }

        return $this->renderForm('feature/create.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/features/{id}/update', name: 'app_feature_update')]
    public function update(Feature $feature, Request $request): Response
    {
        $form = $this->createForm(FeatureType::class, $feature);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_feature_updated', [
                'id' => $feature->getId()
            ]);
        }

        return $this->renderForm('feature/update.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/features/{id}/updated', name: 'app_feature_updated')]
    public function updated(Feature $feature): Response
    {
        return $this->render('feature/updated.stream.html.twig', [
            'resource' => $feature
        ], new TurboStreamResponse());
    }

    #[Route('/features/{id}', name: 'app_feature_show')]
    public function show(Feature $feature): Response
    {
        return $this->renderForm('feature/show.frame.html.twig', [
            'resource' => $feature
        ]);
    }

    #[Route('/features/{id}/delete', name: 'app_feature_delete')]
    public function delete(Feature $feature, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $featureId = $feature->getId();

            $this->entityManager->remove($feature);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_feature_deleted', [
                'id' => $featureId
            ]);
        }

        return $this->renderForm('feature/delete.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/features/{id}/deleted', name: 'app_feature_deleted')]
    public function deleted(int $id): Response
    {
        return $this->render('feature/deleted.stream.html.twig', [
            'featureId' => $id
        ], new TurboStreamResponse());
    }
}
