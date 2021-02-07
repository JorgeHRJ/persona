<?php

namespace App\Service\Cms;

use App\Entity\Post;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PostService extends BaseService
{
    /** @var PostRepository */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        parent::__construct($entityManager, $logger);
        $this->repository = $entityManager->getRepository(Post::class);
    }

    public function getSortFields(): array
    {
        return [];
    }

    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
