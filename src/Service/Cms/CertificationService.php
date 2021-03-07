<?php

namespace App\Service\Cms;

use App\Entity\Certification;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\CertificationRepository;

class CertificationService extends BaseService
{
    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Certification::class;
    }

    /**
     * @return CertificationRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
