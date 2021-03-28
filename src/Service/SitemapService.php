<?php

namespace App\Service;

use App\Library\Model\Sitemap\PostSitemapItem;
use App\Library\Model\Sitemap\SitemapItem;
use App\Service\Site\PostService;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class SitemapService
{
    const SITEMAP_POSTS_LIMIT = 100;

    private $templating;
    private $router;
    private $postService;

    public function __construct(Environment $templating, RouterInterface $router, PostService $postService)
    {
        $this->templating = $templating;
        $this->router = $router;
        $this->postService = $postService;
    }

    public function get(): string
    {
        $items = [];

        $homeUrl = $this->router->generate('site_about_index', [], RouterInterface::ABSOLUTE_URL);
        $homeItem = new SitemapItem($homeUrl, SitemapItem::DAILY_FREQ, 1.0);
        $items[] = $homeItem;

        $blogUrl = $this->router->generate('site_blog_index', [], RouterInterface::ABSOLUTE_URL);
        $blogItem = new SitemapItem($blogUrl, SitemapItem::WEEKLY_FREQ, 0.8);
        $items[] = $blogItem;

        $posts = $this->postService->getForSitemap(self::SITEMAP_POSTS_LIMIT);
        foreach ($posts as $post) {
            $postUrl = $this->router->generate(
                'site_blog_post',
                ['slug' => $post->getSlug()],
                RouterInterface::ABSOLUTE_URL
            );
            $postItem = new PostSitemapItem($postUrl, null, null, $post->getModifiedAt());
            $items[] = $postItem;
        }

        return $this->templating->render('site/sitemap/sitemap.xml.twig', ['items' => $items]);
    }
}
