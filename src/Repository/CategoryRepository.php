<?php

namespace App\Repository;

use App\Entity\Category;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
