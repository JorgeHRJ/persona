<?php

namespace App\Service\Cms;

use App\Entity\Tag;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class TagService extends BaseService
{
    /** @var TagRepository */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        parent::__construct($entityManager, $logger);
        $this->repository = $entityManager->getRepository(Tag::class);
    }

    /**
     * @param array $names
     * @return Tag[]|array
     */
    public function getByNames(array $names): array
    {
        return $this->repository->getByNames($names);
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
