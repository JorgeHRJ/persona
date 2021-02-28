<?php

namespace App\Repository;

use App\Entity\User;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getAdmin(): User
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->addSelect('p')
            ->join('u.profile', 'p')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_ADMIN%')
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
