<?php

namespace App\Library\Maker\Utils;

use Symfony\Component\Filesystem\Filesystem;

class FileManager
{
    private $projectDir;
    private $fs;

    public function __construct(string $projectDir, Filesystem $fs)
    {
        $this->projectDir = $projectDir;
        $this->fs = $fs;
    }

    public function dumpFile(string $filename, string $content): void
    {
        $absolutePath = $this->absolutizePath($filename);

        $this->fs->dumpFile($absolutePath, $content);
    }

    public function absolutizePath(string $path): string
    {
        if (0 === strpos($path, '/')) {
            return $path;
        }

        // support windows drive paths: C:\ or C:/
        if (1 === strpos($path, ':\\') || 1 === strpos($path, ':/')) {
            return $path;
        }

        return sprintf('%s/%s', $this->projectDir, $path);
    }

    public function fileExists(string $path): bool
    {
        return file_exists($this->absolutizePath($path));
    }

    public function getFileContents(string $path): string
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('Cannot find file "%s"', $path));
        }

        return file_get_contents($path);
    }
}
