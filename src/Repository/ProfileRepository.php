<?php

namespace App\Repository;

use App\Entity\Profile;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProfileRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
