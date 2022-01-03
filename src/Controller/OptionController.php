<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Entity\Option;
use App\Form\OptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class OptionController extends ControllerBase
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getResourceType(): string
    {
        return 'option';
    }

    #[Route('/features/{feature<\d+>}/options', name: 'app_option')]
    public function index(Feature $feature): Response
    {
        return $this->render('option/index.html.twig', [
            'feature' => $feature
        ]);
    }

    #[Route('/features/{feature<\d+>}/options/create', name: 'app_option_create')]
    public function create(Feature $feature, Request $request): Response
    {
        $option = new Option();

        $form = $this->createForm(OptionType::class, $option);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feature->addOption($option);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_option_created', [
                'feature' => $feature->getId(),
                'option' => $option->getId()
            ]);
        }

        return $this->renderForm('option/create.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/features/{feature<\d+>}/options/{option<\d+>}/created', name: 'app_option_created')]
    public function created(Option $option): Response
    {
        return $this->render('option/created.stream.html.twig', [
            'option' => $option
        ], new TurboStreamResponse());
    }

    #[Route('/features/{feature<\d+>}/options/{option<\d+>}', name: 'app_option_show')]
    public function show(Option $option): Response
    {
        return $this->renderForm('option/show.frame.html.twig', [
            'option' => $option
        ]);
    }

    #[Route('/features/{feature<\d+>}/options/{option<\d+>}/update', name: 'app_option_update')]
    public function update(Feature $feature, Option $option, Request $request): Response
    {
        $form = $this->createForm(OptionType::class, $option);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_option_updated', [
                'feature' => $feature->getId(),
                'option' => $option->getId()
            ]);
        }

        return $this->renderForm('option/update.frame.html.twig', [
            'form' => $form,
            'option' => $option
        ]);
    }

    #[Route('/features/{feature<\d+>}/options/{option<\d+>}/updated', name: 'app_option_updated')]
    public function updated(Option $option): Response
    {
        return $this->render('option/updated.stream.html.twig', [
            'option' => $option
        ], new TurboStreamResponse());
    }

    #[Route('/features/{feature<\d+>}/options/{option<\d+>}/delete', name: 'app_option_delete')]
    public function delete(Feature $feature, Option $option, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionId = $option->getId();

            $feature->removeOption($option);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_option_deleted', [
                'feature' => $feature->getId(),
                'option' => $optionId
            ]);
        }

        return $this->renderForm('option/delete.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/features/{feature<\d+>}/options/{option<\d+>}/deleted', name: 'app_option_deleted')]
    public function deleted(int $option): Response
    {
        return $this->render('option/deleted.stream.html.twig', [
            'optionId' => $option
        ], new TurboStreamResponse());
    }

    #[Route('/features/{feature<\d+>}/options/{option<\d+>}/move', name: 'app_option_move', methods: ['POST'])]
    public function move(Option $option, Request $request): Response
    {
        /** @var array{position: int} $requestContent */
        $requestContent = json_decode($request->getContent(), true);
        $position = $requestContent['position'];

        $option->setPosition($position);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
