<?php

namespace App\Service;

use App\Entity\User;
use App\Library\Service\BaseService;

class UserService extends BaseService
{
    public function getAdmin(): User
    {
        return $this->repository->getAdmin();
    }

    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return User::class;
    }
}
