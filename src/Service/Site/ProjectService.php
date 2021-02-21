<?php

namespace App\Service\Site;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Project::class);
    }

    /**
     * @return Project[]|array
     */
    public function get(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param string $slug
     * @return Project|null
     */
    public function getBySlug(string $slug): ?Project
    {
        return $this->repository->findOneBy(['slug' => $slug]);
    }
}
