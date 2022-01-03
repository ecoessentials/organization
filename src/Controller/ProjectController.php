<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class ProjectController extends ControllerBase
{

    private ProjectRepository $projectRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProjectRepository $projectRepository, EntityManagerInterface $entityManager)
    {
        $this->projectRepository = $projectRepository;
        $this->entityManager = $entityManager;
    }

    protected function getResourceType(): string
    {
        return 'project';
    }

    #[Route('/projects', name: 'app_project')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig');
    }

    #[Route('/projects/list', name: 'app_project_list')]
    public function list(): Response
    {
        $resources = $this->projectRepository->findBy([], ['name' => 'ASC']);

        return $this->render('project/list.frame.html.twig', [
            'resources' => $resources
        ]);
    }

    #[Route('/projects/create', name: 'app_project_create')]
    public function create(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($project);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId()
            ]);
        }

        return $this->renderForm('project/create.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/projects/{id}/update', name: 'app_project_update')]
    public function update(Project $project, Request $request): Response
    {
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_project_updated', [
                'id' => $project->getId()
            ]);
        }

        return $this->renderForm('project/update.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/projects/{id}/updated', name: 'app_project_updated')]
    public function updated(Project $project): Response
    {
        return $this->render('project/updated.stream.html.twig', [
            'resource' => $project
        ], new TurboStreamResponse());
    }

    #[Route('/projects/{id}', name: 'app_project_show')]
    public function show(Project $project): Response
    {
        return $this->renderForm('project/show.frame.html.twig', [
            'resource' => $project
        ]);
    }

    #[Route('/projects/{id}/delete', name: 'app_project_delete')]
    public function delete(Project $project, Request $request): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectId = $project->getId();

            $this->entityManager->remove($project);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project_deleted', [
                'id' => $projectId
            ]);
        }

        return $this->renderForm('project/delete.frame.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/projects/{id}/deleted', name: 'app_project_deleted')]
    public function deleted(int $id): Response
    {
        return $this->render('project/deleted.stream.html.twig', [
            'projectId' => $id
        ], new TurboStreamResponse());
    }

}
