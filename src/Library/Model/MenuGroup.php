<?php

namespace App\Library\Model;

class MenuGroup
{
    private $title;

    /** @var MenuItem[] */
    private $items;

    public function __construct(string $title, array $items)
    {
        $this->title = $title;
        $this->items = $items;
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
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
