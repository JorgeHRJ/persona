<?php

namespace App\Service\Cms;

use App\Entity\Project;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\ProjectRepository;

class ProjectService extends BaseService
{
    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Project::class;
    }

    /**
     * @return ProjectRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
