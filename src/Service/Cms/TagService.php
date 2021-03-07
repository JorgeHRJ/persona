<?php

namespace App\Service\Cms;

use App\Entity\Tag;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\TagRepository;

class TagService extends BaseService
{
    /**
     * @param array $names
     * @return Tag[]|array
     */
    public function getByNames(array $names): array
    {
        return $this->getRepository()->getByNames($names);
    }

    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Tag::class;
    }

    /**
     * @return TagRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
