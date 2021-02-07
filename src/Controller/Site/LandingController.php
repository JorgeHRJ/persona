<?php

namespace App\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="landing_")
 */
class LandingController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('site/landing/index.html.twig', []);
    }
}
