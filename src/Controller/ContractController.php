<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Employee;
use App\Repository\ContractRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contract')]
final class ContractController extends AbstractController
{
    #[Route('/', name: 'app_contract_index')]
    public function index(ContractRepository $contractRepository): Response
    {
        $contracts = $contractRepository->findAllSortedById();
        return $this->render('contract/index.html.twig', [
            'contracts' => $contracts,
        ]);
    }

    #[Route('/{id}', name: 'app_contract_show', requirements: ['id' => '\d+'])]
    public function show(Contract $contract): Response
    {
        return $this->render('contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    public function getForm(Contract $contract, EntityManagerInterface $entityManager): FormInterface
    {
        return $this
            ->createFormBuilder($contract)
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

    #[Route('/new', name: 'app_contract_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contract = new Contract();
        $form = $this->getForm($contract, $entityManager);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contract);
            $entityManager->flush();
            return $this->redirectToRoute('app_contract_index');
        }
        return $this->render('contract/new.html.twig', [
            'form' => $form->createView(),
            'back_route_name' => 'app_contract_index',
            'back_route_params' => [],
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contract_edit')]
    public function edit(Request $request, Contract $contract, EntityManagerInterface $entityManager): Response
    {
        $form = $this->getForm($contract, $entityManager);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contract);
            $entityManager->flush();
            return $this->redirectToRoute('app_contract_show', ['id' => $contract->getId()]);
        }
        return $this->render('contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form->createView(),
            'back_route_name' => 'app_contract_show',
            'back_route_params' => ['id' => $contract->getId()],
        ]);
    }

    #[Route('/{id}/delete', name: 'app_contract_delete')]
    public function delete(Contract $contract, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($contract);
            $entityManager->flush();
            return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
        } catch (ConstraintViolationException) {
            $this->addFlash('danger', 'Нельзя удалить договор, потому что он находится указан в других сущностях.');
            return $this->redirectToRoute('app_contract_show', [['id' => $contract->getId()]], Response::HTTP_SEE_OTHER);
        }
    }
}
