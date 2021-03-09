<?php

namespace App\Controller\Cms;

use App\Library\Controller\BaseController;
use App\Service\Cms\StatsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="dashboard_")
 */
class DashboardController extends BaseController
{
    private $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index(): Response
    {
        $stats = $this->statsService->getDashboard();

        return $this->render('cms/dashboard/index.html.twig', [
            'stats' => $stats
        ]);
    }
}
