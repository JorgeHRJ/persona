<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Service\Cms\TagService;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

class TagArrayToStringTransformer implements DataTransformerInterface
{
    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value): string
    {
        /* @var Tag[] $value */
        return implode(', ', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): array
    {
        if ($value === null || u($value)->isEmpty()) {
            return [];
        }

        $names = array_filter(array_unique(array_map('trim', u($value)->split(','))));

        $tags = $this->tagService->getByNames($names);
        $newNames = array_diff($names, $tags);
        foreach ($newNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $tags[] = $tag;
        }

        return $tags;
    }
}
