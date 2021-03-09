<?php

namespace App\Service\Cms;

use App\Entity\Post;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PostService extends BaseService
{
    const CACHE_EXPIRATION = 3600;

    private $cache;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        CacheInterface $cache
    ) {
        parent::__construct($entityManager, $logger);
        $this->cache = $cache;
    }

    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Post::class;
    }

    /**
     * @return int
     */
    public function countPublished(): int
    {
        return $this->cache->get('published_articles', function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_EXPIRATION);
            return $this->getRepository()->countPublished();
        });
    }

    /**
     * @return int
     */
    public function countUnpublished(): int
    {
        return $this->cache->get('unpublished_articles', function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_EXPIRATION);
            return $this->getRepository()->countUnpublished();
        });
    }

    /**
     * @return int
     */
    public function countPendingPublish(): int
    {
        return $this->cache->get('pending_publish_articles', function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_EXPIRATION);
            return $this->getRepository()->countPendingPublish();
        });
    }

    /**
     * @return PostRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }

    public function setReadTime(Post $post): void
    {
        $word = str_word_count(strip_tags($post->getContent()));
        $m = (int) floor($word / 200);

        $post->setReadTime($m);
    }
}
