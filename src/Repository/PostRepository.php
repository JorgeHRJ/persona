<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
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

        return $qb->getQuery()->getResult();
    }

    public function getRelated(Post $post, int $limit): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where('p.category = :categoryId')
            ->setParameter('categoryId', $post->getCategory())
        ;

        if (!$post->getTags()->isEmpty()) {
            $tagsIds = array_map(function (Tag $tag) {
                return $tag->getId();
            }, $post->getTags()->toArray());
            $qb
                ->leftJoin('p.tags', 't')
                ->orWhere('t.id IN (:tagsIds)')
                ->setParameter('tagsIds', $tagsIds);
        }

        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $slug
     * @return Post|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBySlug(string $slug): ?Post
    {
        $qb = $this->createQueryBuilder('p');
        $this->setJoins('p', $qb);

        $qb
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getFilterFields(): array
    {
        return [];
    }

    private function setJoins(string $alias, QueryBuilder $qb): void
    {
        $qb
            ->addSelect('a')
            ->addSelect('c')
            ->addSelect('t')
            ->addSelect('pr')
            ->join(sprintf('%s.author', $alias), 'a')
            ->join(sprintf('%s.category', $alias), 'c')
            ->leftJoin(sprintf('%s.tags', $alias), 't')
            ->leftJoin('a.profile', 'pr')
        ;
    }
}
