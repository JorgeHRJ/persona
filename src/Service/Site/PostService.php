<?php

namespace App\Service\Site;

use App\Entity\Post;
use App\Library\Service\BaseService;

class PostService extends BaseService
{
    const LAST_LIMIT = 6;
    const RELATED_LIMIT = 3;

    /**
     * @return Post[]|array
     */
    public function getLast(): array
    {
        return $this->repository->getAll(null, ['publishedAt' => 'DESC'], self::LAST_LIMIT);
    }

    public function getBySlug(string $slug): ?Post
    {
        return $this->repository->getBySlug($slug);
    }

    /**
     * @param Post $post
     * @return Post[]|array
     */
    public function getRelated(Post $post): array
    {
        return $this->repository->getRelated($post, self::RELATED_LIMIT);
    }

    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Post::class;
    }
}
