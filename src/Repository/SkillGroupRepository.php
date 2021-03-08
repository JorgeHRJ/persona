<?php

namespace App\Repository;

use App\Entity\SkillGroup;
use App\Library\Repository\BaseRepository;
use Doctrine\ORM\QueryBuilder;
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

    /**
     * @param string|null $filter
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return mixed
     */
    public function getAll(string $filter = null, array $orderBy = null, int $limit = null, int $offset = null)
    {
        $alias = 'g';
        $qb = $this->createQueryBuilder($alias);

        $this->setJoins($qb, $alias);
        $this->setFilter($alias, $qb, $filter);

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $dir) {
                $qb->orderBy(sprintf('%s.%s', $alias, $field), $dir);
            }
        }

        if ($limit !== null && $offset !== null) {
            $qb->setFirstResult($offset);
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->execute();
    }

    private function setJoins(QueryBuilder $qb, string $alias): void
    {
        $qb
            ->leftJoin(sprintf('%s.skills', $alias), 's')
            ->addSelect('s');
    }
}
