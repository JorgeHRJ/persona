<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AnalyticsExtension extends AbstractExtension
{
    private $gtmId;

    public function __construct(string $gtmId)
    {
        $this->gtmId = $gtmId;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_gtm_id', [$this, 'getGtmId'])
        ];
    }

    public function getGtmId(): string
    {
        return $this->gtmId;
    }
}
