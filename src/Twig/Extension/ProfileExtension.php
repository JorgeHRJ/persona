<?php

namespace App\Twig\Extension;

use App\Entity\Profile;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProfileExtension extends AbstractExtension
{
    const TWITTER_URL_PATTERN = 'https://twitter.com/%s';
    const LINKEDIN_URL_PATTERN = 'https://www.linkedin.com/in/%s';
    const GITHUB_URL_PATTERN = 'https://github.com/%s';
    const INSTAGRAM_URL_PATTERN = 'https://www.instagram.com/%s';
    const STACKOVERFLOW_URL_PATTERN = 'https://stackoverflow.com/users/%s';

    public function getFunctions()
    {
        return [
            new TwigFunction('get_social_items', [$this, 'getSocialItems'])
        ];
    }

    public function getSocialItems(Profile $profile): array
    {
        $socials = [
            [
                'social' => $profile->getTwitter(),
                'pattern' => self::TWITTER_URL_PATTERN,
                'icon' => 'fa-twitter',
                'network' => 'twitter'
            ],
            [
                'social' => $profile->getLinkedin(),
                'pattern' => self::LINKEDIN_URL_PATTERN,
                'icon' => 'fa-linkedin',
                'network' => 'linkedin'
            ],
            [
                'social' => $profile->getGithub(),
                'pattern' => self::GITHUB_URL_PATTERN,
                'icon' => 'fa-github',
                'network' => 'github'
            ],
            [
                'social' => $profile->getInstagram(),
                'pattern' => self::INSTAGRAM_URL_PATTERN,
                'icon' => 'fa-instagram',
                'network' => 'instagram'
            ],
            [
                'social' => $profile->getStackoverflow(),
                'pattern' => self::STACKOVERFLOW_URL_PATTERN,
                'icon' => 'fa-stackover-flow',
                'network' => 'stackoverflow'
            ],
        ];

        return $this->processSocialItems($socials);
    }

    private function processSocialItems(array $socials): array
    {
        $items = [];

        foreach ($socials as $social) {
            $data = $social['social'];
            if ($data !== null && trim($data) !== '') {
                $url = filter_var($data, FILTER_VALIDATE_URL)
                    ? $data
                    : sprintf($social['pattern'], str_replace('@', '', $data));
                $items[] = ['url' => $url, 'icon' => $social['icon'], 'network' => $social['network']];
            }
        }

        return $items;
    }
}
