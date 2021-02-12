<?php

namespace App\Service\Cms;

use App\Entity\Certification;
use App\Library\Service\BaseService;

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
}
