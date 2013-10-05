<?php

namespace Ctors\FilesystemCacheBundle\CacheProvider;

use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\Process\Process;

class FilesystemCache extends CacheProvider
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
    protected function doFetch($id)
    {
        return @file_get_contents($this->getCachePath($id));
    }

    /**
     * {@inheritdoc}
     */
    protected function doContains($id)
    {
        return file_exists($this->getCachePath($id));
    }

    /**
     * {@inheritdoc}
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        $this->fileSystem->mkdir($this->cacheDir);

        return file_put_contents($this->getCachePath($id), $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function doDelete($id)
    {
        return $this->fileSystem->remove($this->getCachePath($id));
    }

    /**
     * {@inheritdoc}
     */
    protected function doFlush()
    {
        return $this->fileSystem->remove($this->cacheDir);
    }

    /**
     * {@inheritdoc}
     */
    protected function doGetStats()
    {
        $process = new Process('find ' . $this->cacheDir . ' -type f | wc -l');
        $process->run();
        $objects = trim($process->getOutput(), "\n");

        $process = new Process('du -hs ' . $this->cacheDir . ' | cut -f1');
        $process->run();
        $size = trim($process->getOutput(), "\n");

        return array(
            'objects' => $objects,
            'size' => $size,
        );
    }

    private function getCachePath($id)
    {
        return $this->cacheDir . sha1($id);
    }
}
