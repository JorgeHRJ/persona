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

    public function setReadTime(Post $post): void
    {
        $word = str_word_count(strip_tags($post->getContent()));
        $m = (int) floor($word / 200);

        $post->setReadTime($m);
    }
}
