<?php

namespace App\Repository;

use App\Entity\SkillGroup;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class SkillGroupRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkillGroup::class);
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
