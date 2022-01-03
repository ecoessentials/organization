<?php

namespace App\Controller;

use App\Entity\ThirdParty;
use App\Form\ThirdPartyType;
use App\Repository\ThirdPartyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class ThirdPartyController extends ControllerBase
{
    private ThirdPartyRepository $thirdPartyRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ThirdPartyRepository $thirdPartyRepository
    )
    {
        $this->thirdPartyRepository = $thirdPartyRepository;
        $this->entityManager = $entityManager;
    }

    protected function getResourceType(): string
    {
        return 'third_party';
    }

    #[Route('/third-parties', name: 'app_third_party')]
    public function index(): Response
    {
        return $this->render('third_party/index.html.twig');
    }

    #[Route('/third-parties/list', name: 'app_third_party_list')]
    public function list(): Response
    {
        $resources = $this->thirdPartyRepository->findBy([], ['name' => 'ASC']);

        return $this->render('third_party/list.frame.html.twig', [
            'resources' => $resources
        ]);
    }

    #[Route('/third-parties/create', name: 'app_third_party_create')]
    public function create(Request $request): Response
    {
        $thirdParty = new ThirdParty();

        $form = $this->createForm(ThirdPartyType::class, $thirdParty);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($thirdParty);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_third_party_show', [
                'id' => $thirdParty->getId()
            ]);
        }

        return $this->renderForm('third_party/create.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/third-parties/{id}/update', name: 'app_third_party_update')]
    public function update(Request $request, ThirdParty $thirdParty): Response
    {
        $form = $this->createForm(ThirdPartyType::class, $thirdParty);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_third_party_updated', [
                'id' => $thirdParty->getId()
            ]);
        }

        return $this->renderForm('third_party/update.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/third-parties/{id}/updated', name: 'app_third_party_updated')]
    public function updated(ThirdParty $thirdParty): Response
    {
        return $this->render('third_party/updated.stream.html.twig', [
            'resource' => $thirdParty
        ], new TurboStreamResponse());
    }

    #[Route('/third-parties/{id}', name: 'app_third_party_show')]
    public function show(ThirdParty $thirdParty): Response
    {
        return $this->render('third_party/show.frame.html.twig', [
            'resource' => $thirdParty
        ]);
    }

    #[Route('/third-parties/{id}/delete', name: 'app_third_party_delete')]
    public function delete(ThirdParty $thirdParty, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thirdPartyId = $thirdParty->getId();

            $this->entityManager->remove($thirdParty);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_third_party_deleted', [
                'id' => $thirdPartyId
            ]);
        }

        return $this->renderForm('third_party/delete.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/third-parties/{id}/deleted', name: 'app_third_party_deleted')]
    public function deleted(int $id): Response
    {
        return $this->render('third_party/deleted.stream.html.twig', [
            'thirdPartyId' => $id
        ], new TurboStreamResponse());
    }
}
