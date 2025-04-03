<?php

namespace App\Controller;

use App\Entity\JobResponsibility;
use App\Repository\JobResponsibilityRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job-responsibility')]
final class JobResponsibilityController extends AbstractController
{
    #[Route('/', name: 'app_job_responsibility_index')]
    public function index(JobResponsibilityRepository $jobResponsibilityRepository): Response
    {
        $jobResponsibilities = $jobResponsibilityRepository->findAll();
        return $this->render('job_responsibility/index.html.twig', [
            'job_responsibilities' => $jobResponsibilities,
        ]);
    }

    #[Route('/{id}', name: 'app_job_responsibility_show', requirements: ['id' => '\d+'])]
    public function show(JobResponsibility $jobResponsibility): Response
    {
        return $this->render('job_responsibility/show.html.twig', [
            'job_responsibility' => $jobResponsibility,
        ]);
    }

    #[Route('/new', name: 'app_job_responsibility_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobResponsibility = new JobResponsibility();
        $form = $this
            ->createFormBuilder($jobResponsibility)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobResponsibility);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_responsibility_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('job_responsibility/new.html.twig', [
            'form' => $form,
            'back_route_name' => 'app_job_responsibility_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_responsibility_edit')]
    public function edit(Request $request, JobResponsibility $jobResponsibility, EntityManagerInterface $entityManager): Response
    {
        $form = $this
            ->createFormBuilder($jobResponsibility)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobResponsibility);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_responsibility_show', ['id' => $jobResponsibility->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('job_responsibility/edit.html.twig', [
            'form' => $form,
            'job_responsibility' => $jobResponsibility,
            'back_route_name' => 'app_job_responsibility_show',
            'back_route_params' => ['id' => $jobResponsibility->getId()],
        ]);
    }

    #[Route('/{id}/delete', name: 'app_job_responsibility_delete')]
    public function delete(JobResponsibility $jobResponsibility, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($jobResponsibility);
            $entityManager->flush();
            return $this->redirectToRoute('app_job_responsibility_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить обязанность, потому что она указана в других сущностях.');
            return $this->redirectToRoute('app_job_responsibility_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
