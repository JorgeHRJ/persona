<?php

namespace App\Service\Cms;

use App\Entity\Skill;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\SkillRepository;

class SkillService extends BaseService
{
    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Skill::class;
    }

    /**
     * @return SkillRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
