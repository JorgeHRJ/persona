<?php

namespace App\Service\Cms;

use Twig\Environment;
use App\Service\Site\PostService;

class FeedService
{
    const RSS_LIMIT = 100;

    private $templating;
    private $postService;

    public function __construct(Environment $templating, PostService $postService)
    {
        $this->templating = $templating;
        $this->postService = $postService;
    }

    public function getRss(): string
    {
        return $this->templating->render('site/feed/rss.xml.twig', [
            'posts' => $this->postService->getForFeed(self::RSS_LIMIT)
        ]);
    }
}
