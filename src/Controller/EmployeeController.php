<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\EmployeePosition;
use App\Entity\JobTitle;
use App\Entity\WorkingStatus;
use App\Repository\EmployeeRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employee')]
final class EmployeeController extends AbstractController
{
    #[Route('/', name: 'app_employee_index')]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();
        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_show', requirements: ['id' => '\d+'])]
    public function show(Employee $employee): Response
    {
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    public function getForm(Employee $employee, EntityManagerInterface $entityManager): FormInterface
    {
        return $this
            ->createFormBuilder($employee)
            ->add('secondName', TextType::class, ['label' => 'Фамилия', 'required' => true])
            ->add('firstName', TextType::class, ['label' => 'Имя', 'required' => true])
            ->add('patronymic', TextType::class, ['label' => 'Отчество', 'required' => false])
            ->add('department', EntityType::class, [
                'label' => 'Отдел',
                'required' => true,
                'class' => Department::class,
                'choices' => $entityManager->getRepository(Department::class)->findAll(),
                'choice_label' => 'name',
            ])
            ->add('jobTitle', EntityType::class, [
                'label' => 'Должность',
                'required' => true,
                'class' => JobTitle::class,
                'choices' => $entityManager->getRepository(JobTitle::class)->findAll(),
                'choice_label' => 'name',
            ])
            ->add('workingStatus', EntityType::class, [
                'label' => 'Статус',
                'required' => true,
                'class' => WorkingStatus::class,
                'choices' => $entityManager->getRepository(WorkingStatus::class)->findAll(),
                'choice_label' => 'name',
            ])
            ->add('phoneNumber', TextType::class, ['label' => 'Номер телефона', 'required' => false])
            ->add('email', TextType::class, ['label' => 'Электронная почта', 'required' => false])
            ->add('note', TextareaType::class, ['label' => 'Заметка', 'required' => false])
            ->getForm()
        ;
    }

    #[Route('/new', name: 'app_employee_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employee = new Employee();
        $employeePosition = new EmployeePosition();
        $form = $this->getForm($employee, $entityManager);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->persist($employeePosition);
            $employeePosition->setEmployee($employee);
            $employee->addEmployeePosition($employeePosition);
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('employee/new.html.twig', [
            'form' => $form,
            'back_route_name' => 'app_employee_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_edit')]
    public function edit(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->getForm($employee, $entityManager);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_show', ['id' => $employee->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('employee/edit.html.twig', [
            'form' => $form,
            'employee' => $employee,
            'back_route_name' => 'app_employee_show',
            'back_route_params' => ['id' => $employee->getId()],
        ]);
    }

    #[Route('/{id}/delete', name: 'app_employee_delete')]
    public function delete(Employee $employee, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($employee);
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить отдел, потому что он находится указан в других сущностях.');
            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
