<?php

namespace App\Service\Cms;

use App\Entity\Tag;
use App\Library\Service\BaseService;

class TagService extends BaseService
{
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

    public function getEntityClass(): string
    {
        return Tag::class;
    }
}
