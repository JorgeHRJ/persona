<?php

namespace App\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class RobotsController extends AbstractController
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @Route("/robots.txt", name="robots")
     *
     * @return Response
     */
    public function robots(): Response
    {
        $sitemapUrl = $this->router->generate('site_sitemap', [], RouterInterface::ABSOLUTE_URL);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');

        return $this->render(
            'site/robots/robots.txt.twig',
            ['sitemap_url' => $sitemapUrl],
            $response
        );
    }
}
