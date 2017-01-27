<?php
use Xcart\App\Main\VarDumper;

function d($data)
{
    echo VarDumper::dump($data);
    die();
}