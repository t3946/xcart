<?php

namespace Xcart\App\Traits;


trait Singleton
{
    protected static $instance;

    final public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static);
    }

    final private function __construct() {
        $this->init();
    }

    protected function init() {}
    public function __wakeup() {}
    private function __clone() {}
}