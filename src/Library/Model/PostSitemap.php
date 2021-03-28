<?php

namespace App\Library\Model;

class PostSitemap
{
    /** @var string */
    private $slug;

    /** @var \DateTimeInterface */
    private $modifiedAt;

    public function __construct(string $slug, \DateTimeInterface $modifiedAt)
    {
        $this->slug = $slug;
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getModifiedAt(): \DateTimeInterface
    {
        return $this->modifiedAt;
    }

    /**
     * @param \DateTimeInterface $modifiedAt
     */
    public function setModifiedAt(\DateTimeInterface $modifiedAt): void
    {
        $this->modifiedAt = $modifiedAt;
    }
}
