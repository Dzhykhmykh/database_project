<?php

namespace App\Controller;

use App\Entity\DaysOff;
use App\Entity\DaysOffType;
use App\Entity\Employee;
use App\Repository\DaysOffRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/days-off')]
final class DaysOffController extends AbstractController
{
    #[Route('/', name: 'app_days_off_index')]
    public function index(DaysOffRepository $daysOffRepository): Response
    {
        $daysOffs = $daysOffRepository->findAllSortedById();
        return $this->render('days_off/index.html.twig', [
            'days_offs' => $daysOffs,
        ]);
    }

    #[Route('/{id}', name: 'app_days_off_show', requirements: ['id' => '\d+'])]
    public function show(DaysOff $daysOff): Response
    {
        return $this->render('days_off/show.html.twig', [
            'days_off' => $daysOff,
        ]);
    }

    public function getForm(DaysOff $daysOff, EntityManagerInterface $entityManager): FormInterface
    {
        return $this
            ->createFormBuilder($daysOff)
            ->add('days_off_type', EntityType::class, [
                'label' => 'Вид отгула',
                'required' => true,
                'class' => DaysOffType::class,
                'choice_label' => 'name',
            ])
            ->add('name', TextType::class, ['label' => 'Название', 'required' => true])
            ->add('description', TextAreaType::class, ['label' => 'Описание', 'required' => false])
            ->add('employee', EntityType::class, [
                'label' => 'Сотрудник',
                'required' => true,
                'class' => Employee::class,
                'choices' => $entityManager->getRepository(Employee::class)->findAll(),
            ])
            ->add('dateFrom', DateType::class, ['label' => 'Дата начала', 'required' => true])
            ->add('dateTo', DateType::class, ['label' => 'Дата начала', 'required' => true])
            ->getForm()
        ;
    }

    #[Route('/new', name: 'app_days_off_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $daysOff = new DaysOff();
        $form = $this->getForm($daysOff, $entityManager);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($daysOff);
            $entityManager->flush();
            return $this->redirectToRoute('app_days_off_index');
        }
        return $this->render('days_off/new.html.twig', [
            'form' => $form->createView(),
            'back_route_name' => 'app_days_off_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_days_off_edit')]
    public function edit(Request $request, DaysOff $daysOff, EntityManagerInterface $entityManager): Response
    {
        $form = $this->getForm($daysOff, $entityManager);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($daysOff);
            $entityManager->flush();
            return $this->redirectToRoute('app_days_off_show', ['id' => $daysOff->getId()]);
        }
        return $this->render('days_off/edit.html.twig', [
            'days_off' => $daysOff,
            'form' => $form->createView(),
            'back_route_name' => 'app_days_off_show',
            'back_route_params' => ['id' => $daysOff->getId()],
        ]);
    }

    #[Route('/{id}/delete', name: 'app_days_off_delete')]
    public function delete(DaysOff $daysOff, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($daysOff);
            $entityManager->flush();
            return $this->redirectToRoute('app_days_off_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить отгул, потому что он находится указан в других сущностях.');
            return $this->redirectToRoute('app_days_off_show', [['id' => $daysOff->getId()]], Response::HTTP_SEE_OTHER);
        }
    }
}
