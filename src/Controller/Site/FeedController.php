<?php

namespace App\Controller\Site;

use App\Service\Cms\FeedService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/feed", name="feed_")
 */
class FeedController
{
    private $feedService;

    public function __construct(FeedService $feedService)
    {
        $this->feedService = $feedService;
    }

    /**
     * @Route("/rss", name="rss")
     *
     * @return Response
     */
    public function rss(): Response
    {
        $feed = $this->feedService->getRss();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');
        $response->setContent($feed);

        return $response;
    }
}
