<?php
namespace Xcart\App\Traits;

use Xcart\App\Helpers\Paths;

trait CacheFilesTrait
{
    public $path = 'runtime.cache';

    public $extension = '.cache';

    public $keySerialization = true;

    public $autoGC = true;

    public $strongRemove = false;

    public $gcProbability = 10;

    public $directoryLevel = 1;

    public $mode = 0775;

    public function set($key, $value, $timeout = null)
    {
        if (is_null($value)) {
            if ($this->strongRemove) {
                while(is_file($this->getFileName($this->buildKey($key))) && !@unlink($this->getFileName($this->buildKey($key)))){}
            }
            else {
                @unlink($this->getFileName($this->buildKey($key)));
            }

            return true;
        }
        else {
            return $this->setValue($this->buildKey($key), $this->serialize($value), $timeout ?: $this->timeout);
        }
    }

    public function get($key, $default = null)
    {
        $value = $this->getValue($this->buildKey($key));
        return is_null($value) ? $default : $this->unserialize($value);
    }

    protected function getValue($key)
    {
        $filePath = $this->getFileName($key);

        if (is_file($filePath) && @filemtime($filePath) > time()) {
            $fp = @fopen($filePath, 'r');
            if ($fp !== false) {
//                @flock($fp, LOCK_SH);
                $cacheValue = @stream_get_contents($fp);
//                @flock($fp, LOCK_UN);
                @fclose($fp);
                return $cacheValue;
            }
        }
        return null;
    }

    protected function setValue($key, $data, $timeout)
    {
        if ($this->autoGC) {
            $this->gc();
        }

        $cacheFile = $this->getFileName($key);

        if ($this->directoryLevel > 0) {
            $dir = dirname($cacheFile);
            if (!is_dir($dir)) {
                @mkdir($dir, $this->mode, true);
            }
        }
        if (@file_put_contents($cacheFile, $data, LOCK_EX) !== false) {
            if ($timeout <= 0) {
                $timeout = $this->timeout;
            }
            return $this->setExpirationTime($cacheFile, $timeout + time());
        }
        return false;
    }

    protected function getFileName($key)
    {
        $filePath = $key;
        if ($this->directoryLevel > 0 ) {
            $base = '';
            for ($i = 0; $i < $this->directoryLevel; ++$i) {
                if (($prefix = substr($key, $i + $i, 2)) !== false) {
                    $base .= DIRECTORY_SEPARATOR . $prefix . DIRECTORY_SEPARATOR;
                }
            }
            $filePath = $base . $filePath;
        }
        return Paths::get($this->path) . $filePath . $this->extension;
    }

    public function gc($force = false, $expiredOnly = true)
    {
        if ($force || mt_rand(0, 1000000) < $this->gcProbability) {
            $this->gcRecursive(Paths::get($this->path), $expiredOnly);
        }
    }

    protected function gcRecursive($path, $expiredOnly = true)
    {
        if (($handle = opendir($path)) !== false) {
            while (($file = readdir($handle)) !== false) {
                if ($file[0] === '.') {
                    continue;
                }
                $fullPath = $path . DIRECTORY_SEPARATOR . $file;
                if (is_dir($fullPath)) {
                    $this->gcRecursive($fullPath, $expiredOnly);
                    if (!$expiredOnly) {
                        @rmdir($fullPath);
                    }
                } elseif (!$expiredOnly || $expiredOnly && $this->isExpire($fullPath)) {
                    @unlink($fullPath);
                }
            }
            closedir($handle);
        }
    }

    public function buildKey($key)
    {
        if (!is_string($key)) {
            $key = serialize($key);
        }

        return $this->keySerialization ? md5($key) : $key;
    }

    protected function setExpirationTime($fullPath, $timeout)
    {
        return @touch($fullPath, $timeout);
    }

    protected function isExpire($fullPath)
    {
        return @filemtime($fullPath) < time();
    }
}