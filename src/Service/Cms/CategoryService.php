<?php

namespace App\Service\Cms;

use App\Entity\Category;
use App\Library\Service\BaseService;

class CategoryService extends BaseService
{
    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Category::class;
    }
}
