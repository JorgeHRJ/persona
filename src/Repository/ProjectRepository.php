<?php

namespace App\Repository;

use App\Entity\Project;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
