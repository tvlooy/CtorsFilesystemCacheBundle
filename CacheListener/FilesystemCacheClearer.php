<?php

namespace Ctors\FilesystemCacheBundle\CacheListener;

use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class FilesystemCacheClearer implements CacheClearerInterface
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
    public function clear($cacheDir)
    {
        $this->fileSystem->remove($cacheDir . $this->cacheDir);
    }
}
