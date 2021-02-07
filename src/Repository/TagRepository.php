<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Library\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class TagRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @param array $names
     * @return Tag[]|array
     */
    public function getByNames(array $names): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->where('t.name IN (:names)')
            ->setParameter('names', $names);

        return $qb->getQuery()->getResult();
    }

    public function getFilterFields(): array
    {
        return [];
    }
}
