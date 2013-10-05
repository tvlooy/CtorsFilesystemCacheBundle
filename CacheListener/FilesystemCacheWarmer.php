<?php

namespace Ctors\FilesystemCacheBundle\CacheListener;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class FilesystemCacheWarmer implements CacheWarmerInterface
{
    private $cacheDir;

    /** @var \Symfony\Component\Filesystem\Filesystem */
    private $fileSystem;

    public function __construct($cacheDir, $fileSystem)
    {
        $this->cacheDir = $cacheDir;
        $this->fileSystem = $fileSystem;
    }

    /**
     * {@inheritdoc}
     */
    public function warmUp($cacheDir)
    {
        $this->fileSystem->mkdir($cacheDir . $this->cacheDir);
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return true;
    }
}
