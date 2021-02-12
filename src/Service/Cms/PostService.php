<?php

namespace App\Service\Cms;

use App\Entity\Post;
use App\Library\Service\BaseService;

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
}
