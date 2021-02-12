<?php

namespace App\Controller\Cms;

use App\Library\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="dashboard_")
 */
class DashboardController extends BaseController
{
    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('cms/dashboard/index.html.twig', []);
    }
}
