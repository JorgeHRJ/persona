<?php

namespace App\Service\Cms;

use App\Service\StorageService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    const UPLOADS_FOLDER = 'uploads';
    const POST_IMAGE_ORIGIN = 'post-images';

    private $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Upload file and return public path
     *
     * @param UploadedFile $uploadedFile
     * @param string $origin
     * @return string
     * @throws \Exception
     */
    public function upload(UploadedFile $uploadedFile, string $origin): string
    {
        $filename = explode('.', $uploadedFile->getClientOriginalName());
        $name = (string) preg_replace('/[^\w\-\.]/', '', $filename[0]);
        $name = sprintf('%s_%s', $name, md5((string) time()));
        $filename = sprintf('%s.%s', $name, $uploadedFile->getClientOriginalExtension());

        $relativePath = sprintf('%s/%s/', self::UPLOADS_FOLDER, $origin);
        $folder = $this->storageService->getAssetsDir($relativePath);
        $this->storageService->save($uploadedFile, $folder, $filename);

        return sprintf('/%s/%s%s', $this->storageService->getAssetsFolder(), $relativePath, $filename);
    }
}
