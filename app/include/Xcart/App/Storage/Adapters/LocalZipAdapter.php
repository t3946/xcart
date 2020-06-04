<?php
namespace Xcart\App\Storage\Adapters;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Config;
use League\Flysystem\Util;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use SplFileInfo;
use Xcart\App\Helpers\Paths;
use ZipArchive;

class LocalZipAdapter extends ZipArchiveAdapter implements AdapterExtInterface
{
    private $config = [];
    private $relativeBase;

    protected static $permissions = [
        'file' => [
            'public' => 0664,
            'private' => 0600,
        ],
        'dir' => [
            'public' => 0775,
            'private' => 0700,
        ]
    ];

    public function __construct($config = [])
    {
        $this->config = $config;

        $permissions = self::$permissions;
        if (!empty($config['permissions'])) {
            $permissions = array_replace_recursive($permissions, $config['permissions']);
        }

        $base = Paths::get($config['root']);
        $www = Paths::get('www');

        if (strpos($base, $www) === 0) {
            $this->relativeBase = substr($base, strlen($www));
        }

        //parent::__construct($base);
    }

    public function getUrl($path)
    {
        if ($this->relativeBase)
        {
            return $this->relativeBase .'/'. $path;
        }

        return '/'. $path;
    }

    public function has($path)
    {
        $location = $this->applyPathPrefix($path);

        return file_exists($location);
    }

    public function write($location, $contents, Config $config)
    {
        if (!isset($this->archive)) {
            $this->setArchive(new ZipArchive());
            $this->openArchive($location);
            $www = Paths::get('www');
            $this->setPathPrefix($this->getUrl($www));
        }
        return parent::write($location, $contents, $config);
    }
}