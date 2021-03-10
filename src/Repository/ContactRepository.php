<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Library\Repository\BaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class ContactRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function countUnread(): int
    {
        $qb = $this->createQueryBuilder('c')->select('count(c.id)');
        $qb
            ->where('c.status = :unread')
            ->setParameter('unread', Contact::STATUS_UNREAD);

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
