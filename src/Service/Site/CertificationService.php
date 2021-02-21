<?php

namespace App\Service\Site;

use App\Entity\Certification;
use Doctrine\ORM\EntityManagerInterface;

class CertificationService
{
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Certification::class);
    }

    public function get(): array
    {
        return $this->repository->findAll();
    }
}
