<?php

namespace App\Service;

class ContextService
{
    const CMS = 0;
    const SITE = 1;

    /** @var int */
    private $context;

    public function setContext(int $context): void
    {
        $this->context = $context;
    }

    public function getContext(): int
    {
        return $this->context;
    }

    public function isCMS(): bool
    {
        return $this->context === self::CMS;
    }

    public function isSite(): bool
    {
        return $this->context === self::SITE;
    }
}
