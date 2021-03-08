<?php

namespace App\Service\Site;

use App\Entity\SkillGroup;
use App\Repository\SkillGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class SkillGroupService
{
    /** @var SkillGroupRepository */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(SkillGroup::class);
    }

    public function get(): array
    {
        return $this->repository->getAll();
    }
}
