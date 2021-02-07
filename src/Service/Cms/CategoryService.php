<?php

namespace App\Service\Cms;

use App\Entity\Category;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class CategoryService extends BaseService
{
    /** @var CategoryRepository */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        parent::__construct($entityManager, $logger);
        $this->repository = $entityManager->getRepository(Category::class);
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
