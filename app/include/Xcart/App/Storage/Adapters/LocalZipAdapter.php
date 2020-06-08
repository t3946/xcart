<?php
namespace Xcart\App\Storage\Adapters;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Config;
use League\Flysystem\Exception;
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
        $this->setArchive(new ZipArchive());

        //parent::__construct($base);
    }

    public function getUrl($path)
    {
        $path_info = pathinfo($path);
        $dirname = Util::dirname($path);
        $path = $dirname . "/" . rtrim($path_info['basename'], "." . $path_info['extension']) . '.zip';

        if ($this->relativeBase)
        {
            return $this->relativeBase .'/'. $path;
        }

        return Paths::get($this->config['root']) . '/'. $path;
    }

    public function getMetadata($path)
    {
        $path_info = pathinfo($path);
        $dirname = Util::dirname($path);
        $this->openArchive($this->getUrl($path));
        $path_info = pathinfo($path);
        return parent::getMetadata($path_info['basename']);
    }

    /*public function has($path)
    {
        $location = $this->applyPathPrefix($this->getUrl($path));

        return file_exists($location);
    }*/

    protected function ensureDirectory($root)
    {
        if ( ! is_dir($root)) {
            $umask = umask(0);

            if ( ! @mkdir($root, $this->permissionMap['dir']['public'], true)) {
                $mkdirError = error_get_last();
            }

            umask($umask);
            clearstatcache(false, $root);

            if ( ! is_dir($root)) {
                $errorMessage = isset($mkdirError['message']) ? $mkdirError['message'] : '';
                throw new Exception(sprintf('Impossible to create the root directory "%s". %s', $root, $errorMessage));
            }
        }
    }

    public function write($location, $contents, Config $config)
    {
        $path_info = pathinfo($location);
        $dirname = Util::dirname($location);
        $this->ensureDirectory($dirname);
        $this->setArchive(new ZipArchive());
        $this->openArchive($dirname . "/" . rtrim($path_info['basename'], "." . $path_info['extension']) . '.zip');
        $res = parent::write($path_info['basename'], $contents, $config);
        $this->archive->close();
        return $res;
    }
}