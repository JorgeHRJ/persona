<?php

namespace App\Service\Cms;

use App\Entity\SkillGroup;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\SkillGroupRepository;

class SkillGroupService extends BaseService
{
    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return SkillGroup::class;
    }

    /**
     * @return SkillGroupRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
