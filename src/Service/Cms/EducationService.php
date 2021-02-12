<?php

namespace App\Service\Cms;

use App\Entity\Education;
use App\Library\Service\BaseService;

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
}
