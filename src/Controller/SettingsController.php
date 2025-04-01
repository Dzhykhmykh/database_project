<?php

namespace App\Controller;

use App\Entity\DaysOffType;
use App\Entity\WorkingStatus;
use App\Repository\DaysOffTypeRepository;
use App\Repository\WorkingStatusRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/settings')]
final class SettingsController extends AbstractController
{
    #[Route('/', name: 'app_settings_index', methods: ['GET'])]
    public function index(WorkingStatusRepository $workingStatusRepository, DaysOffTypeRepository $daysOffTypeRepository): Response
    {
        $workingStatuses = $workingStatusRepository->findAll();
        $daysOffTypes = $daysOffTypeRepository->findAll();
        return $this->render('settings/index.html.twig', [
            'working_statuses' => $workingStatuses,
            'days_off_types' => $daysOffTypes,
        ]);
    }

    #[Route('/new-working-status', name: 'app_settings_working_status_new', methods: ['GET', 'POST'])]
    #[Route('/new-days-off-type', name: 'app_settings_days_off_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->attributes->get('_route') === 'app_settings_working_status_new') {
            $entity = new WorkingStatus();
        } else {
            $entity = new DaysOffType();
        }
        $form = $this
            ->createFormBuilder($entity)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();
            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($request->attributes->get('_route') === 'app_settings_working_status_new') {
            return $this->render('settings/new_working_status.html.twig', [
                'form' => $form,
                'back_route_name' => 'app_settings_index',
                'back_route_params' => [],
            ]);
        }
        return $this->render('settings/new_days_off_type.html.twig', [
            'form' => $form,
            'back_route_name' => 'app_settings_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/edit-working-status/{id}', name: 'app_settings_working_status_edit', methods: ['GET', 'POST'])]
    public function editWorkingStatus(Request $request, WorkingStatus $workingStatus, EntityManagerInterface $entityManager): Response
    {
        $form = $this
            ->createFormBuilder($workingStatus)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($workingStatus);
            $entityManager->flush();
            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('settings/edit_working_status.html.twig', [
            'form' => $form,
            'working_status' => $workingStatus,
            'back_route_name' => 'app_settings_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/edit-days-off-type/{id}', name: 'app_settings_days_off_type_edit', methods: ['GET', 'POST'])]
    public function editDaysOffType(Request $request, DaysOffType $daysOffType, EntityManagerInterface $entityManager): Response
    {
        $form = $this
            ->createFormBuilder($daysOffType)
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextType::class, ['label' => 'Описание', 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($daysOffType);
            $entityManager->flush();
            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('settings/edit_days_off_type.html.twig', [
            'form' => $form,
            'days_off_type' => $daysOffType,
            'back_route_name' => 'app_settings_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/delete-working-status/{id}', name: 'app_settings_working_status_delete', methods: ['POST'])]
    public function deleteWorkingStatus(WorkingStatus $workingStatus, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($workingStatus);
            $entityManager->flush();
            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить вид рабочего статуса, потому что он находится указан в других сущностях.');
            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/delete-days-off-type/{id}', name: 'app_settings_days_off_type_delete', methods: ['POST'])]
    public function deleteDaysOffType(DaysOffType $daysOffType, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($daysOffType);
            $entityManager->flush();
            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить вид рабочего статуса, потому что он находится указан в других сущностях.');
            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }
    }
}
