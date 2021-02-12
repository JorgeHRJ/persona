<?php

namespace App\Service\Cms;

use App\Entity\Project;
use App\Library\Service\BaseService;

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
}
