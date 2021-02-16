<?php

namespace App\Service;

use App\Entity\Asset;
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

            // prepare paths
            $filename = $this->getFilename($id, $entity, $type);
            $relativeDirPath = $this->getRelativeDirPath($entity, $id);
            $folder = $this->storageService->getAssetsDir($relativeDirPath);

            // remove previous files if they exist
            $this->findAndRemove($id, $entity, $type);

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

        $filename = $this->getFilename($id, $entity, $type);
        $relativeDirPath = $this->getRelativeDirPath($entity, $id);
        $assetsDir = $this->storageService->getAssetsFolder();

        $publicPath = sprintf('%s/%s/%s', $assetsDir, $relativeDirPath, $filename);

        $path = null;
        foreach (StorageService::IMAGE_EXTENSIONS as $extension) {
            $temp = sprintf('%s.%s', $publicPath, $extension);
            if (file_exists($temp)) {
                $path = $temp;
            }
        }

        return $path;
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

        $types = $this->getTypesInfo($entity);
        foreach ($types as $type => $info) {
            $this->findAndRemove($id, $entity, $type);
        }
    }

    public function getRelativeDirPath(string $entity, int $id): string
    {
        return sprintf('%s/%s', $entity, $id);
    }

    public function getFilename(int $id, string $entity, string $type): string
    {
        return sprintf('%d.%s.%s', $id, $entity, $type);
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

    private function findAndRemove(int $id, string $entity, string $type): void
    {
        // prepare paths
        $filename = $this->getFilename($id, $entity, $type);
        $relativeDirPath = $this->getRelativeDirPath($entity, $id);
        $folder = $this->storageService->getAssetsDir($relativeDirPath);

        // remove previous files if they exist
        foreach (StorageService::IMAGE_EXTENSIONS as $extension) {
            $temp = sprintf('%s%s.%s', $folder, $filename, $extension);
            $this->storageService->remove($temp);
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
