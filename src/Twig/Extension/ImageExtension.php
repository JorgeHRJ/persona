<?php

namespace App\Twig\Extension;

use App\Service\ImageService;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImageExtension extends AbstractExtension
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_image_types', [$this, 'getTypes']),
            new TwigFunction('get_image', [$this, 'getImage'])
        ];
    }

    public function getTypes(string $entity): array
    {
        return $this->imageService->getTypes($entity);
    }

    public function getImage(string $type, object $model): ?string
    {
        return $this->imageService->getImage($type, $model);
    }
}
