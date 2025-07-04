<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\EmployeePosition;
use App\Entity\JobTitle;
use App\Entity\WorkingStatus;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employee-position')]
final class EmployeePositionController extends AbstractController
{
    protected function getForm(EmployeePosition $employeePosition, EntityManagerInterface $entityManager): FormInterface
    {
        return $this
            ->createFormBuilder($employeePosition)
            ->add('employee', EntityType::class, [
                'label' => 'Сотрудник',
                'required' => true,
                'class' => Employee::class,
                'data' => $employeePosition->getEmployee(),
                'disabled' => true,
            ])
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
            ->add('salary', IntegerType::class, ['label' => 'Зарплата', 'required' => false])
            ->add('dateFrom', DateType::class, ['label' => 'Дата начала', 'required' => true])
            ->add('dateTo', DateType::class, ['label' => 'Дата конца', 'required' => false])
            ->getForm()
        ;
    }

    #[Route('/new/{id}', name: 'app_employee_position_new')]
    public function new(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        $employeePosition = new EmployeePosition();
        $employeePosition->setEmployee($employee);
        $form = $this->getForm($employeePosition, $entityManager);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $employeePosition->setEmployee($employee);
            $entityManager->persist($employeePosition);
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_show', ['id' => $employee->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('employee_position/new.html.twig', [
            'form' => $form,
            'back_route_name' => 'app_employee_show',
            'back_route_params' => ['id' => $employee->getId()],
        ]);
    }

    #[Route('/edit/{id}', name: 'app_employee_position_edit')]
    public function edit(Request $request, EmployeePosition $employeePosition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->getForm($employeePosition, $entityManager);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_show', ['id' => $employeePosition->getEmployee()->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('employee_position/new.html.twig', [
            'form' => $form,
            'back_route_name' => 'app_employee_show',
            'back_route_params' => ['id' => $employeePosition->getEmployee()->getId()],
        ]);
    }

    #[Route('/delete/{id}', name: 'app_employee_position_delete')]
    public function delete(EmployeePosition $employeePosition, EntityManagerInterface $entityManager): Response
    {
        try {
            $employee = $employeePosition->getEmployee();
            $entityManager->remove($employeePosition);
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_show', ['id' => $employee->getId()], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить договор, потому что он находится указан в других сущностях.');
            return $this->redirectToRoute('app_employee_show', ['id' => $employee->getId()], Response::HTTP_SEE_OTHER);
        }
    }
}
