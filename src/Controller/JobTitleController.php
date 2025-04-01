<?php

namespace App\Controller;

use App\Entity\JobTitle;
use App\Repository\JobTitleRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job-title')]
final class JobTitleController extends AbstractController
{
    #[Route('/', name: 'app_job_title_index')]
    public function index(JobTitleRepository $jobTitleRepository): Response
    {
        $jobTitles = $jobTitleRepository->findAll();
        return $this->render('job_title/index.html.twig', [
            'job_titles' => $jobTitles,
        ]);
    }

    #[Route('/{id}', name: 'app_job_title_show', requirements: ['id' => '\d+'])]
    public function show(JobTitle $jobTitle): Response
    {
        return $this->render('job_title/show.html.twig', [
            'job_title' => $jobTitle,
        ]);
    }

    #[Route('/new', name: 'app_job_title_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobTitle = new JobTitle();
        $form = $this
            ->createFormBuilder($jobTitle)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('salary', IntegerType::class, ['label' => 'Зарплата, ₽', 'required' => true])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobTitle);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_title_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('job_title/new.html.twig', [
            'form' => $form,
            'back_route_name' => 'app_job_title_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_title_edit')]
    public function edit(Request $request, JobTitle $jobTitle, EntityManagerInterface $entityManager): Response
    {
        $form = $this
            ->createFormBuilder($jobTitle)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('salary', IntegerType::class, ['label' => 'Зарплата, ₽', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobTitle);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_title_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('job_title/edit.html.twig', [
            'form' => $form,
            'job_title' => $jobTitle,
            'back_route_name' => 'app_job_title_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/{id}/delete', name: 'app_job_title_delete')]
    public function deleteJobTitle(JobTitle $jobTitle, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($jobTitle);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_title_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить отдел, потому что он находится указан в других сущностях.');
            return $this->redirectToRoute('app_job_title_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
