<?php

namespace App\Repository;

use App\Entity\Certification;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class CertificationRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certification::class);
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
