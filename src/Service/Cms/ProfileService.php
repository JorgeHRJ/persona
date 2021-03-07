<?php

namespace App\Service\Cms;

use App\Entity\Profile;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\ProfileRepository;

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

    /**
     * @return ProfileRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
