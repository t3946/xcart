<?php
require_once "top.inc.php";
require_once "init.php";

if (!empty($_GET))
{
    $getArray = [];

    foreach ($_GET as $key => $value)
    {
        if (!empty($value)) {
            $value = getClearValue($value);
        }
        else {
            $value = '!EMPTY!';
        }

        $getArray[] = "{$key}: {$value}";
    }


    if (!empty($getArray)) {
        func_backprocess_log('anveo_logging', implode("\n-\n", $getArray));
    }
}

function getClearValue($value)
{
    if (is_array($value)) {
        $t = [];

        foreach ($value as $k=>$v) {
            $t[$k] = getDecodedString($v);
        }

        $value = json_encode($t);
    }
    else {
        $value = getDecodedString($value);
    }

    return $value;
}

function getDecodedString($str)
{
    return htmlspecialchars_decode(stripslashes($str), ENT_QUOTES);
}