<?php

namespace App\Repository;

use App\Entity\Education;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class EducationRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Education::class);
    }
    public function getFilterFields(): array
    {
        return [];
    }
}
