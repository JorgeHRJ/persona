<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StorageService
{
    const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'gif', 'png'];

    /** @var string */
    private $publicFolder;

    /** @var string */
    private $assetsFolder;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(string $publicFolder, string $assetsFolder, LoggerInterface $logger)
    {
        $this->publicFolder = $publicFolder;
        $this->assetsFolder = $assetsFolder;
        $this->logger = $logger;
    }

    /**
     * Save a file in the filesystem
     *
     * @param UploadedFile $file
     * @param string $folder Folder where save the file
     * @param string $filename
     *
     * @throws \Exception
     */
    public function save(UploadedFile $file, string $folder, string $filename): void
    {
        if (!file_exists($folder)) {
            mkdir($folder, 0764, true);
        }

        try {
            $file->move($folder, $filename);
        } catch (FileException $e) {
            $this->logger->error(sprintf('Error when saving file "%s". Error: %s', $filename, $e->getMessage()));

            throw $e;
        }
    }

    /**
     * Save a file in the filesystem from a url
     *
     * @param string $url
     * @param string $folder
     * @param string $filename
     * @throws \Exception
     */
    public function saveFromUrl(string $url, string $folder, string $filename): void
    {
        if (!file_exists($folder)) {
            mkdir($folder, 0764, true);
        }

        try {
            file_put_contents(sprintf('%s/%s', $folder, $filename), file_get_contents($url));
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Error when saving file "%s". Error: %s', $filename, $e->getMessage()));

            throw $e;
        }
    }

    /**
     * Save base64 image string
     *
     * @param string $data
     * @param string $folder
     * @param string $filename
     * @return array The filename and extension
     * @throws \Exception
     */
    public function saveBase64Image(string $data, string $folder, string $filename): array
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]);

            if (!in_array($type, self::IMAGE_EXTENSIONS)) {
                throw new \Exception('Invalid image type');
            }

            $data = str_replace(' ', '+', $data);

            /** @var string|bool $data */
            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('Base64 decode failed!');
            }

            $filename = sprintf('%s.%s', $filename, $type);

            if (!file_exists($folder)) {
                mkdir($folder, 0764, true);
            }

            file_put_contents(sprintf('%s/%s', $folder, $filename), $data);

            return [$filename, $type];
        }

        throw new \Exception('Wrong base64 string: no image data found!');
    }

    /**
     * @param string $path
     */
    public function remove(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    public function assetExists(string $path): bool
    {
        return file_exists(sprintf('%s/%s', $this->publicFolder, $path));
    }

    /**
     * @param string $toClonePath
     * @param string $newPath
     */
    public function clone(string $toClonePath, string $newPath): void
    {
        copy($toClonePath, $newPath);
    }

    /**
     * @return string
     */
    public function getPublicFolder(): string
    {
        return $this->publicFolder;
    }

    /**
     * @return string
     */
    public function getAssetsFolder(): string
    {
        return $this->assetsFolder;
    }

    /**
     * @param string $relativePath
     * @return string
     */
    public function getAssetsDir(string $relativePath): string
    {
        return sprintf('%s/%s/%s/', $this->publicFolder, $this->assetsFolder, $relativePath);
    }

    /**
     * @param string $relativePath
     * @param string $filename
     * @return string
     */
    public function getFilePath(string $relativePath, string $filename): string
    {
        return sprintf('%s/%s/%s', $this->assetsFolder, $relativePath, $filename);
    }
}
