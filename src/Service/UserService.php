<?php

namespace App\Service;

use App\Entity\User;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\UserRepository;

class UserService extends BaseService
{
    public function getAdmin(): User
    {
        return $this->getRepository()->getAdmin();
    }

    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * @return UserRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
