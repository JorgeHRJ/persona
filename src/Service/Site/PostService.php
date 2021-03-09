<?php

namespace App\Service\Site;

use App\Entity\Post;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\PostRepository;

class PostService extends BaseService
{
    const LAST_LIMIT = 6;
    const RELATED_LIMIT = 3;

    /**
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getPublished(int $limit, int $page): array
    {
        $offset = ($page - 1) * $limit;

        return $this->getRepository()->getPublished($limit, $offset);
    }

    /**
     * @return int
     */
    public function countPublished(): int
    {
        return $this->getRepository()->countPublished();
    }

    /**
     * @return Post[]|array
     */
    public function getLast(): array
    {
        return $this->getRepository()->getPublished(self::LAST_LIMIT);
    }

    /**
     * @param string $slug
     * @return Post|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBySlug(string $slug): ?Post
    {
        return $this->getRepository()->getBySlug($slug);
    }

    /**
     * @param Post $post
     * @return Post[]|array
     */
    public function getRelated(Post $post): array
    {
        return $this->getRepository()->getRelated($post, self::RELATED_LIMIT);
    }

    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Post::class;
    }

    /**
     * @return PostRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
