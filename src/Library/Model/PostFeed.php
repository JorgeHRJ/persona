<?php

namespace App\Library\Model;

class PostFeed
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $category;

    /**
     * @var \DateTimeInterface
     */
    private $pubDate;

    public function __construct(
        string $title,
        string $slug,
        string $description,
        string $author,
        string $category,
        \DateTimeInterface $pubDate
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->author = $author;
        $this->category = $category;
        $this->pubDate = $pubDate;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPubDate(): \DateTimeInterface
    {
        return $this->pubDate;
    }

    /**
     * @param \DateTimeInterface $pubDate
     */
    public function setPubDate(\DateTimeInterface $pubDate): void
    {
        $this->pubDate = $pubDate;
    }
}
