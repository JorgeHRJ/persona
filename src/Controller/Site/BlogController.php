<?php

namespace App\Controller\Site;

use App\Entity\Post;
use App\Library\Controller\BaseController;
use App\Service\Site\PostService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", name="blog_")
 */
class BlogController extends BaseController
{
    const LIST_LIMIT = 10;

    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"})
     *
     * @param int|null $page
     * @return Response
     */
    public function index(int $page = null): Response
    {
        if ($page === null) {
            $page = 1;
        }

        list($total, $posts) = array_values(
            $this->postService->getAll(null, $page, self::LIST_LIMIT, 'publishedAt', 'DESC')
        );
        $pages = ceil($total / self::LIST_LIMIT);

        $featured = !empty($posts) ? array_shift($posts) : null;

        return $this->render('site/blog/index.html.twig', [
            'featured' => $featured,
            'posts' => $posts,
            'previous_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $pages ? $page + 1 : null,
        ]);
    }

    /**
     * @Route("/{slug}", name="post", requirements={"slug"="[a-zA-Z1-9\-_\/]+"})
     *
     * @param string $slug
     * @return Response
     */
    public function post(string $slug): Response
    {
        $post = $this->postService->getBySlug($slug);
        if (!$post instanceof Post) {
            throw new NotFoundHttpException();
        }

        $related = $this->postService->getRelated($post);

        return $this->render('site/blog/post.html.twig', [
            'post' => $post,
            'related' => $related
        ]);
    }
}
