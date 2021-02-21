<?php

namespace App\Service\Site;

use App\Entity\Education;
use Doctrine\ORM\EntityManagerInterface;

class EducationService
{
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Education::class);
    }

    /**
     * @return Education[]|array
     */
    public function get(): array
    {
        return $this->repository->findAll();
    }
}
