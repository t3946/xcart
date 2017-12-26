<?php

use Xcart\App\Main\VarDumper;
use Xcart\App\Cli\Cli;

function pd($data, $depth = 10, $highlight = false)
{
    if (Xcart\App\Cli\Cli::isCli()) {
        print_r($data);
    }
    else {
        echo "<pre>";
        echo VarDumper::dump($data, $depth, $highlight);
        echo "</pre>";
    }
}

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
    pd($data, 10, true);
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
    pd($data);
    die();
}

function func_print_r()
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

    pd($data);
}

function func_dump()
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

    pd($data);
}