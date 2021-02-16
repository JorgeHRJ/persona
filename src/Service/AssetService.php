<?php

namespace App\Service;

use App\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;

class AssetService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(string $filename, string $extension, string $path, string $type, string $source): void
    {
        $asset = new Asset();
        $asset
            ->setFilename($filename)
            ->setExtension($extension)
            ->setPath($path)
            ->setType($type)
            ->setSource($source);

        $this->entityManager->persist($asset);
        $this->entityManager->flush();
    }
}
