<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
use App\Library\Model\PostFeed;
use App\Library\Repository\BaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
     * @return Post[]|array
     */
    public function getAll(string $filter = null, array $orderBy = null, int $limit = null, int $offset = null)
    {
        $alias = 'p';
        $qb = $this->createQueryBuilder($alias);

        if ($limit === null && $offset === null) {
            return $this->getAllNoPaginated($qb, $alias, $filter, $orderBy);
        }

        $qb->select(sprintf('%s.id', $alias));
        $this->setFilter($alias, $qb, $filter);

        if ($offset !== null) {
            $qb->setFirstResult($offset);
        }

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        $ids = $qb->getQuery()->getArrayResult();
        $ids = array_column($ids, 'id');

        $qb = $this->createQueryBuilder($alias)->select($alias);
        $this->setJoins($alias, $qb);

        $qb
            ->andWhere(sprintf('%s.id IN (:ids)', $alias))
            ->setParameter('ids', $ids);

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $dir) {
                $qb->orderBy(sprintf('%s.%s', $alias, $field), $dir);
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return Post[]|array
     */
    public function getPublished(int $limit = null, int $offset = null): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p.id');
        $this->setPublishedRestriction('p', $qb);

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        if ($offset !== null) {
            $qb->setFirstResult($offset);
        }

        $ids = $qb->getQuery()->getArrayResult();
        $ids = array_column($ids, 'id');

        $qb = $this->createQueryBuilder('p')->select('p');
        $this->setJoins('p', $qb);

        $qb
            ->andWhere('p.id IN (:ids)')
            ->setParameter('ids', $ids);
        $qb->orderBy('p.publishedAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function countPublished(): int
    {
        $qb = $this->createQueryBuilder('p')->select('count(p.id)');
        $this->setPublishedRestriction('p', $qb);

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function countUnpublished(): int
    {
        $qb = $this->createQueryBuilder('p')->select('count(p.id)');
        $qb
            ->where('p.publishedAt > :now')
            ->setParameter('now', new \DateTime());

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @return int
     */
    public function countPendingPublish(): int
    {
        $qb = $this->createQueryBuilder('p')->select('count(p.id)');
        $qb->where('p.publishedAt IS NULL');

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @param Post $post
     * @param int $limit
     * @return array
     */
    public function getRelated(Post $post, int $limit): array
    {
        $qb = $this->createQueryBuilder('p');
        $this->setPublishedRestriction('p', $qb);
        $qb
            ->andWhere('p.category = :categoryId')
            ->andWhere('p.id <> :postId')
            ->setParameter('categoryId', $post->getCategory())
            ->setParameter('postId', $post->getId())
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

    /**
     * @param int $limit
     * @return PostFeed[]|array
     */
    public function getForFeed(int $limit): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->select(sprintf('NEW %s(p.title, p.slug, p.summary, a.email, c.name, p.publishedAt)', PostFeed::class))
            ->join('p.category', 'c')
            ->join('p.author', 'a');
        $this->setPublishedRestriction('p', $qb);

        $qb->orderBy('p.publishedAt', 'DESC')->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function getFilterFields(): array
    {
        return [];
    }

    private function getAllNoPaginated(
        QueryBuilder $qb,
        string $alias,
        string $filter = null,
        array $orderBy = null
    ): array {
        $qb->select($alias);

        $this->setJoins($alias, $qb);
        $this->setFilter($alias, $qb, $filter);

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $dir) {
                $qb->orderBy(sprintf('%s.%s', $alias, $field), $dir);
            }
        }

        return $qb->getQuery()->getResult();
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

    private function setPublishedRestriction(string $alias, QueryBuilder $qb): void
    {
        $qb
            ->where(sprintf('%s.publishedAt <= :now', $alias))
            ->setParameter('now', new \DateTime());
    }
}
