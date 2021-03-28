<?php

namespace App\Library\Model\Sitemap;

class SitemapItem
{
    const ALWAYS_FREQ = 'always';
    const HOURLY_FREQ = 'hourly';
    const DAILY_FREQ = 'daily';
    const WEEKLY_FREQ = 'weekly';
    const MONTHLY_FREQ = 'monthly';
    const YEARLY_FREQ = 'yearly';
    const NEVER_FREQ = 'never';

    /** @var string */
    private $loc;

    /** @var string */
    private $frequency;

    /** @var float */
    private $priority;

    /** @var \DateTimeInterface|null */
    private $lastModification;

    public function __construct(
        string $loc,
        string $frequency = null,
        float $priority = null,
        \DateTimeInterface $lastModification = null
    ) {
        $this->loc = $loc;
        $this->frequency = $frequency !== null ? $frequency : $this->getItemFrequency();
        $this->priority = $priority !== null ? $priority : $this->getItemPriority();
        $this->lastModification = $lastModification;
    }

    /**
     * @return string
     */
    protected function getItemFrequency(): string
    {
        return self::DAILY_FREQ;
    }

    /**
     * @return float
     */
    protected function getItemPriority(): float
    {
        return 0.8;
    }

    /**
     * @return string
     */
    public function getLoc(): string
    {
        return $this->loc;
    }

    /**
     * @param string $loc
     */
    public function setLoc(string $loc): void
    {
        $this->loc = $loc;
    }

    /**
     * @return string
     */
    public function getFrequency(): string
    {
        return $this->frequency;
    }

    /**
     * @param string $frequency
     */
    public function setFrequency(string $frequency): void
    {
        $this->frequency = $frequency;
    }

    /**
     * @return float
     */
    public function getPriority(): float
    {
        return $this->priority;
    }

    /**
     * @param float $priority
     */
    public function setPriority(float $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastModification(): ?\DateTimeInterface
    {
        return $this->lastModification;
    }

    /**
     * @param \DateTimeInterface|null $lastModification
     */
    public function setLastModification(?\DateTimeInterface $lastModification): void
    {
        $this->lastModification = $lastModification;
    }
}
