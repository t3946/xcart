<?php
namespace Xcart\App\Cli;

class Cli
{
    /**
     * @return bool
     */
    public static function isCli()
    {
        return PHP_SAPI === 'cli';
    }
}