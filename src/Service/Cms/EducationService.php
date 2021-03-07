<?php

namespace App\Service\Cms;

use App\Entity\Education;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\EducationRepository;

class EducationService extends BaseService
{
    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Education::class;
    }

    /**
     * @return EducationRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
