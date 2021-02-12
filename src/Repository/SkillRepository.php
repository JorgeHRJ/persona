<?php

namespace App\Repository;

use App\Entity\Skill;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class SkillRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skill::class);
    }
    public function getFilterFields(): array
    {
        return [];
    }
}
