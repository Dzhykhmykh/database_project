<?php

namespace App\Controller;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/department')]
final class DepartmentController extends AbstractController
{
    #[Route('/', name: 'app_department_index')]
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();
        return $this->render('department/index.html.twig', [
            'departments' => $departments,
        ]);
    }

    #[Route('/{id}', name: 'app_department_show', requirements: ['id' => '\d+'])]
    public function show(Department $department): Response
    {
        return $this->render('department/show.html.twig', [
           'department' => $department,
        ]);
    }

    #[Route('/new', name: 'app_department_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $department = new Department();
        $form = $this
            ->createFormBuilder($department)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->add('budget', IntegerType::class, ['label' => 'Бюджет, ₽', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();
            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('department/new.html.twig', [
            'form' => $form,
            'back_route_name' => 'app_department_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_department_edit')]
    public function edit(Request $request, Department $department, EntityManagerInterface $entityManager): Response
    {
        $form = $this
            ->createFormBuilder($department)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->add('budget', IntegerType::class, ['label' => 'Бюджет, ₽', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();
            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('department/edit.html.twig', [
            'form' => $form,
            'department' => $department,
            'back_route_name' => 'app_department_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/{id}/delete', name: 'app_department_delete')]
    public function delete(Department $department, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($department);
            $entityManager->flush();
            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить отдел, потому что он находится указан в других сущностях.');
            return $this->redirectToRoute('app_department_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
