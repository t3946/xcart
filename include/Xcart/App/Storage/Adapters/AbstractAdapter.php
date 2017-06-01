<?php
namespace Xcart\App\Storage\Adapters;

abstract class AbstractAdapter implements AdapterInterface
{
    protected $config;

    public function __construct($config = []) {
        $this->config = $config;
    }

    abstract public function getAdapter();
}