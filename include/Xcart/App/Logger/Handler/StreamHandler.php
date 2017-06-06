<?php

namespace Xcart\App\Logger\Handler;

use Mindy\Helper\Alias;
use Monolog\Handler\StreamHandler as MonoStreamHandler;
use Xcart\App\Helpers\Paths;

/**
 * Class StreamHandler
 * @package Xcart\App\Logger
 */
class StreamHandler extends ProxyHandler
{
    /**
     * @var string path to file or proxy to stdout: php://stdout
     */
    public $stream;

    public $alias = 'base.runtime.application';

    public $filePermission;

    public function init()
    {
        $this->stream = Paths::get($this->alias) . '.log';
        parent::init();
    }

    public function getHandler()
    {
        return new MonoStreamHandler($this->stream, $this->getLevel(), $this->bubble, $this->filePermission);
    }
}

