<?php
namespace Xcart\App\Cache\Drivers;

use Xcart\App\Cache\CacheDriver;
use Xcart\App\Helpers\Paths;
use Xcart\App\Traits\CacheFilesTrait;

class File extends CacheDriver
{
    use CacheFilesTrait;

    public function cleanUp($force = false)
    {
        $this->gc(true, !$force);
    }
}