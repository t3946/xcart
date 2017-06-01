<?php
namespace Xcart\App\Storage\Adapters;

use League\Flysystem\Adapter\Local;
use Xcart\App\Helpers\Paths;

class LocalAdapter extends AbstractAdapter
{
    private $adapter;

    public function getAdapter()
    {
        if (!$this->adapter) {
            $this->adapter = new Local(Paths::get($this->config['root']));
        }

        return $this->adapter;
    }
}