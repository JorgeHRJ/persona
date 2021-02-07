<?php

namespace App\Repository;

use App\Entity\Post;
use App\Library\Repository\BaseRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
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
        $alias = 'p';
        $qb = $this->createQueryBuilder($alias)->select($alias);

        $this->setJoins($alias, $qb);
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

    public function getFilterFields(): array
    {
        return [];
    }

    private function setJoins(string $alias, QueryBuilder $qb): void
    {
        $qb
            ->addSelect('c')
            ->addSelect('t')
            ->leftJoin(sprintf('%s.category', $alias), 'c')
            ->leftJoin(sprintf('%s.tags', $alias), 't');
    }
}
