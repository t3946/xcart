<?php

namespace Xcart\App\Logger;

use Xcart\App\Helpers\Paths;
use Xcart\App\Traits\CacheFilesTrait;

class DebugLogger extends Logger
{
    use CacheFilesTrait;

    public $cachePatch = 'root.log.debug_cache';
    public $randomWork = true;

    public function __construct(string $name, $handlers = array(), $processors = array())
    {
        $this->path = $this->cachePatch;
        $this->directoryLevel = 2;

        parent::__construct($name, $handlers, $processors);
    }

    public function addRecord($level, $message, array $context = array())
    {
        if ($this->randomWork && (rand(0,5) < 3)) {
            return false;
        }

        if (!$this->checkContext($context)) {
            return parent::addRecord($level, $message, $context);
        }

        $lines = $this->get($this->genKey($level, $message, $context)) ?: [];
        $lkey = md5($message) . $context['line'];

        if (!in_array($lkey, $lines)) {
            $lines[] = $lkey;

            $this->set($this->genKey($level, $message, $context), $lines, 86400);

            return parent::addRecord($level, $message, $context);
        }

        return false;
    }

    private function checkContext(array $context = [])
    {
        return (!empty($context['file']) && !empty($context['line']));
    }

    private function genKey($level, $message, array $context = [])
    {
        return $level . md5($context['file']);
    }


    public function serialize($value)
    {
        return serialize($value);
    }

    public function unserialize($value)
    {
        return unserialize($value);
    }
}
