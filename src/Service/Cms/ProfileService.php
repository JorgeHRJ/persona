<?php

namespace App\Service\Cms;

use App\Entity\Profile;
use App\Library\Service\BaseService;

class ProfileService extends BaseService
{
    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Profile::class;
    }
}
