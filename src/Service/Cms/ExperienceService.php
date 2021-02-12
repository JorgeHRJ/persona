<?php

namespace App\Service\Cms;

use App\Entity\Experience;
use App\Library\Service\BaseService;

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
}
