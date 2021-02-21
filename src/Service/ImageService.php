<?php

namespace App\Service;

use App\Entity\Asset;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Form\FormInterface;

class ImageService
{
    private $config;
    private $assetService;
    private $storageService;

    public function __construct(array $config, AssetService $assetService, StorageService $storageService)
    {
        $this->config = $config;
        $this->assetService = $assetService;
        $this->storageService = $storageService;
    }

    /**
     * @param FormInterface $form
     * @param object $model
     * @throws \Exception
     */
    public function handleRequest(FormInterface $form, object $model): void
    {
        list(, , $entity) = explode('\\', get_class($model));
        $entity = strtolower($entity);
        if (!method_exists($model, 'getId')) {
            throw new \Exception('Object passed as argument is not an entity!');
        }

        $id = $model->getId();

        $types = $this->getTypesInfo($entity);
        foreach ($types as $type => $info) {
            $data = $form->get($type)->getData();
            if (!$data) {
                continue;
            }

            // remove previous files if they exist
            $this->findAndRemove($id, $entity, $type);

            // prepare paths
            $filename = $this->getFilename($id, $entity, $type);
            $relativeDirPath = $this->getRelativeDirPath($entity, $id);
            $folder = $this->storageService->getAssetsDir($relativeDirPath);

            // save file
            list($filename, $extension) = $this->storageService->saveBase64Image($data, $folder, $filename);
            $path = $this->storageService->getFilePath($relativeDirPath, $filename);

            $this->assetService->create($filename, $extension, $path, Asset::IMAGE_TYPE, Asset::ENTITY_SOURCE);
        }
    }

    public function getImage(string $type, object $model): ?string
    {
        $entity = $this->getEntityFromObject($model);
        if (!method_exists($model, 'getId')) {
            throw new \Exception('Object passed as argument is not an entity!');
        }

        $id = $model->getId();
        if ($id === null) {
            return null;
        }

        $filenamePattern = $this->getFilenamePattern($id, $entity, $type);
        $relativeDirPath = $this->getRelativeDirPath($entity, $id);
        $assetsDir = $this->storageService->getAssetsDir($relativeDirPath);

        if (!file_exists($assetsDir)) {
            return null;
        }

        $assetsPath = $this->storageService->getAssetsFolder();

        $finder = new Finder();
        $files = $finder->files()->in($assetsDir)->name($filenamePattern);
        if (!$files->hasResults()) {
            return null;
        }

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            return sprintf('%s/%s/%s', $assetsPath, $relativeDirPath, $file->getFilename());
        }

        return null;
    }

    public function removeEntityImages(object $model): void
    {
        $entity = $this->getEntityFromObject($model);
        if (!method_exists($model, 'getId')) {
            throw new \Exception('Object passed as argument is not an entity!');
        }

        $id = $model->getId();
        if ($id === null) {
            return;
        }

        $this->findAndRemove($id, $entity);
    }

    public function getRelativeDirPath(string $entity, int $id): string
    {
        return sprintf('%s/%s', $entity, $id);
    }

    public function getFilename(int $id, string $entity, string $type): string
    {
        return sprintf('%d.%s.%s.%s', $id, $entity, $type, md5(uniqid((string) rand(), true)));
    }

    public function getFilenamePattern(int $id, string $entity, string $type): string
    {
        return sprintf('%d.%s.%s.*', $id, $entity, $type);
    }

    public function getSizes(string $entity, string $type): array
    {
        $entityInfo = $this->getEntityInfo($entity);

        if (!isset($entityInfo[$type])) {
            throw new \Exception(sprintf('type %s from entity %s not found!', $type, $entity));
        }

        list($width, $height) = explode('x', $this->config['entites'][$entity][$type]['size']);

        return ['width' => $width, 'height' => $height];
    }

    public function getTypesInfo(string $entity): array
    {
        return $this->getEntityInfo($entity);
    }

    public function getTypes(string $entity): array
    {
        return array_keys($this->getEntityInfo($entity));
    }

    private function findAndRemove(int $id, string $entity, string $type = null): void
    {
        // prepare paths
        $relativeDirPath = $this->getRelativeDirPath($entity, $id);
        $folder = $this->storageService->getAssetsDir($relativeDirPath);

        // find
        if (!file_exists($folder)) {
            return;
        }

        $finder = new Finder();
        $files = $finder->files()->in($folder);

        if ($type !== null) {
            $filenamePattern = $this->getFilenamePattern($id, $entity, $type);
            $files->name($filenamePattern);
        }

        // remove
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $this->storageService->remove($file->getRealPath());
        }
    }

    private function getEntityFromObject(object $model): string
    {
        list(, , $entity) = explode('\\', get_class($model));
        return strtolower($entity);
    }

    private function getEntityInfo(string $entity): array
    {
        if (!isset($this->config['entities'][$entity])) {
            throw new \Exception(sprintf('Entity %s not found!', $entity));
        }

        return $this->config['entities'][$entity];
    }
}
