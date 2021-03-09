<?php

namespace App\Twig\Extension;

use App\Entity\Post;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PostExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_post_status', [$this, 'getPostStatus'])
        ];
    }

    public function getPostStatus(Post $post): array
    {
        if (!$post->getPublishedAt() instanceof \DateTimeInterface) {
            return [
                'label' => 'Borrador',
                'badge' => 'info'
            ];
        }

        if ($post->getPublishedAt() > (new \DateTime())) {
            return [
                'label' => 'Pendiente de publicar',
                'badge' => 'warning'
            ];
        }

        return [
            'label' => 'Publicado',
            'badge' => 'success'
        ];
    }
}
