Filesystem cache - Symfony 2 Bundle
===================================

This is a Symfony 2 Bundle that adds a filesystem cache.

## Installing via Composer

```json
{
    "require": {
        "ctors/filesystem-cache-bundle": "dev-master"
    }
}
```

## Using and Setting Up

### AppKernel.php
```php
public function registerBundles() {
    $bundles = array(
        new Ctors\FilesystemCacheBundle\CtorsFilesystemCacheBundle(),
    );
}
```
### Service usage
The cache service's name is ctors.cache. You can alias it in your project's config.yml:

```yaml
service:
    cache:
        alias: ctors.cache
```

The cache implements the Doctrine CacheProvider interface. Simple usage example:

```php
/** @var \Doctrine\Common\Cache\CacheProvider $cache */
$cache = $this->get('cache');

if ($cache->contains($searchKey)) {
    $value = unserialize($cache->fetch($searchKey));
} else {
    $value = new SomeValue();
    $cache->save($searchKey, serialize($value));
}

### Command usage
There is a command that prints the cache usage:

```shell
$ app/console ctors:filesystemcache:stats 
Filesystem cache statistics (05/10/2013 15:07:09)
  - number of objects 3
  - disk usage 28K
```

You can also watch the cache, it will update any time a resource is added or removed:

```shell
$ app/console ctors:filesystemcache:stats -w
Filesystem cache statistics (05/10/2013 15:07:11)
  - number of objects 3
  - disk usage 28K
Filesystem cache statistics (05/10/2013 15:07:18)
  - number of objects 4
  - disk usage 44K
Filesystem cache statistics (05/10/2013 15:07:22)
  - number of objects 5
  - disk usage 48K
```

