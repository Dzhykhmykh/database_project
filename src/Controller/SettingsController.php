<?php

namespace App\Controller;

use App\Repository\DaysOffTypeRepository;
use App\Repository\WorkingStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
