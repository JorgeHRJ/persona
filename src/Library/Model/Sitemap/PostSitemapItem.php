<?php

namespace App\Library\Model\Sitemap;

class PostSitemapItem extends SitemapItem
{
    protected function getItemFrequency(): string
    {
        return self::MONTHLY_FREQ;
    }

    protected function getItemPriority(): float
    {
        return 0.7;
    }
}
