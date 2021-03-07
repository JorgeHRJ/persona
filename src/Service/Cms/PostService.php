<?php

namespace App\Service\Cms;

use App\Entity\Post;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\PostRepository;

class PostService extends BaseService
{
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
