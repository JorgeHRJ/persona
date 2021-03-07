<?php

namespace App\Service\Cms;

use App\Entity\Experience;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\ExperienceRepository;

class ExperienceService extends BaseService
{
    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Experience::class;
    }

    /**
     * @return ExperienceRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
