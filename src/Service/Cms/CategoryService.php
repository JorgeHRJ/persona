<?php

namespace App\Service\Cms;

use App\Entity\Category;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\CategoryRepository;

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

    /**
     * @return CategoryRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
