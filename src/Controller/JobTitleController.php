<?php

namespace App\Controller;

use App\Entity\JobResponsibility;
use App\Entity\JobTitle;
use App\Repository\JobResponsibilityRepository;
use App\Repository\JobTitleRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job-title')]
final class JobTitleController extends AbstractController
{
    #[Route('/', name: 'app_job_title_index', methods: ['GET'])]
    public function index(JobTitleRepository $jobTitleRepository): Response
    {
        $jobTitles = $jobTitleRepository->findAll();
        return $this->render('job_title/index.html.twig', [
            'job_titles' => $jobTitles,
        ]);
    }

    #[Route('/{id}', name: 'app_job_title_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(JobTitle $jobTitle): Response
    {
        return $this->render('job_title/show.html.twig', [
            'job_title' => $jobTitle,
        ]);
    }

    #[Route('/new', name: 'app_job_title_new', methods: ['GET', 'POST'])]
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

    #[Route('/{id}/edit', name: 'app_job_title_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JobTitle $jobTitle, EntityManagerInterface $entityManager): Response
    {
        $form = $this
            ->createFormBuilder($jobTitle)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->add('salary', IntegerType::class, ['label' => 'Зарплата, ₽', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobTitle);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_title_show', ['id' => $jobTitle->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('job_title/edit.html.twig', [
            'form' => $form,
            'job_title' => $jobTitle,
            'back_route_name' => 'app_job_title_show',
            'back_route_params' => ['id' => $jobTitle->getId()],
        ]);
    }

    #[Route('/{id}/edit-job-responsibilities', name: 'app_job_title_edit_job_responsibilities', methods: ['GET', 'POST'])]
    public function editJobResponsibility(
        Request $request, JobTitle $jobTitle,
        EntityManagerInterface $entityManager,
        JobResponsibilityRepository $jobResponsibilityRepository,
    ): Response
    {
        $form = $this
            ->createFormBuilder($jobTitle)
            ->add('jobResponsibilities', EntityType::class, [
                'class' => JobResponsibility::class,
                'choices' => $jobResponsibilityRepository->findAll(),
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Должности',
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobTitle);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_title_show', ['id' => $jobTitle->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job_title/edit.html.twig', [
            'job_title' => $jobTitle,
            'form' => $form,
            'back_route_name' => 'app_job_title_show',
            'back_route_params' => ['id' => $jobTitle->getId()],
        ]);
    }

    #[Route('/{jobTitle}/remove-job-responsibility/{jobResponsibility}', name: 'app_job_title_remove_job_responsibility', methods: ['POST'])]
    public function removeJobResponsibility(
        JobTitle $jobTitle,
        JobResponsibility $jobResponsibility,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $jobTitle->removeJobResponsibility($jobResponsibility);
        $entityManager->persist($jobTitle);
        $entityManager->flush();
        return $this->redirectToRoute('app_job_title_show', ['id' => $jobTitle->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete', name: 'app_job_title_delete', methods: ['GET', 'POST'])]
    public function deleteJobTitle(JobTitle $jobTitle, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($jobTitle);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_title_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить должность, потому что она указана в других сущностях.');
            return $this->redirectToRoute('app_job_title_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
