<?php

namespace App\Service\Cms;

use App\Service\StorageService;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\UrlHelper;

class UploadService
{
    const UPLOADS_FOLDER = 'uploads';
    const POST_IMAGE_ORIGIN = 'post-images';

    private $assetHelper;
    private $urlHelper;
    private $storageService;

    public function __construct(Packages $assetHelper, UrlHelper $urlHelper, StorageService $storageService)
    {
        $this->assetHelper = $assetHelper;
        $this->urlHelper = $urlHelper;
        $this->storageService = $storageService;
    }

    /**
     * Upload file and return file uploaded data
     *
     * @param UploadedFile $uploadedFile
     * @param string $origin
     * @return array
     * @throws \Exception
     */
    public function upload(UploadedFile $uploadedFile, string $origin): array
    {
        $filename = explode('.', $uploadedFile->getClientOriginalName());
        $name = (string) preg_replace('/[^\w\-\.]/', '', $filename[0]);
        $filename = sprintf(
            '%s.%s',
            sprintf('%s_%s', $name, md5((string) time())),
            $uploadedFile->getClientOriginalExtension()
        );

        $relativePath = sprintf('%s/%s/', self::UPLOADS_FOLDER, $origin);
        $folder = $this->storageService->getAssetsDir($relativePath);
        $this->storageService->save($uploadedFile, $folder, $filename);

        $path = sprintf('/%s/%s%s', $this->storageService->getAssetsFolder(), $relativePath, $filename);
        return [
            'name' => $name,
            'path' => $this->urlHelper->getAbsoluteUrl($this->assetHelper->getUrl($path))
        ];
    }
}
