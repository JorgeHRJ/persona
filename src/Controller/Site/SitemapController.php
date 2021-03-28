<?php

namespace App\Controller\Site;

use App\Service\SitemapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    private $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * @Route("/sitemap.xml", name="sitemap")
     *
     * @return Response
     */
    public function sitemap(): Response
    {
        $sitemap = $this->sitemapService->get();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        $response->setContent($sitemap);

        return $response;
    }
}
