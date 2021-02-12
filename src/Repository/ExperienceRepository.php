<?php

namespace App\Repository;

use App\Entity\Experience;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExperienceRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experience::class);
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
