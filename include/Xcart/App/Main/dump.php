<?php

use Xcart\App\Main\VarDumper;
use Xcart\App\Cli\Cli;

function d()
{
    $debug = debug_backtrace();
    $args = func_get_args();
    $data = array(
        'data' => $args,
        'debug' => array(
            'file' => isset($debug[0]['file']) ? $debug[0]['file'] : null,
            'line' => isset($debug[0]['line']) ? $debug[0]['line'] : null,
        )
    );
    if (Xcart\App\Cli\Cli::isCli()) {
        print_r($data);
    }
    else {
        echo VarDumper::dump($data);
    }
    die();
}

function dd()
{
    $debug = debug_backtrace();
    $args = func_get_args();
    $data = array(
        'data' => $args,
        'debug' => array(
            'file' => isset($debug[0]['file']) ? $debug[0]['file'] : null,
            'line' => isset($debug[0]['line']) ? $debug[0]['line'] : null,
        )
    );
    if (Xcart\App\Cli\Cli::isCli()) {
        print_r($data);
    }
    else {
        echo VarDumper::dump($data, 10, false);
    }
    die();
}
