<?php

namespace App\Service\Site;

use App\Entity\Experience;
use Doctrine\ORM\EntityManagerInterface;

class ExperienceService
{
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Experience::class);
    }

    public function get(): array
    {
        return $this->repository->findAll();
    }
}
