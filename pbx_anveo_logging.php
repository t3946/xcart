<?php
require_once "top.inc.php";
require_once "init.php";

$now = date('Y-m-d H:i:s');

if (!empty($_GET['incoming_flow_start']) || !empty($_GET['start_out']))
{
    $direction = (!empty($_GET['incoming_flow_start'])) ? 0 : 1; // 0 - in | 1 - out
    $session = getDecodedString($_GET['session']);
    db_query("INSERT INTO anveo_calls (`session`, `start_at`) VALUES ('{$session}', '{$now}')");
}

if (!empty($_GET['incoming_flow_end']) || !empty($_GET['end_out']))
{
    $session = getDecodedString($_GET['session']);
    db_query("UPDATE anveo_calls SET `end_at` = '{$now}' WHERE `session` = '{$session}'");
}

if (!empty($_POST['incoming_call_saved']) || !empty($_POST['record_out']))
{
    $session = getDecodedString($_POST['session']);

    if (!empty($_POST['incoming_call_saved']))
    {
        $file = getDecodedString($_POST['incoming_call_saved']);
    }
    elseif (!empty($_POST['record_filename'])) {
        $file = getDecodedString($_POST['record_filename']);
    }

    db_query("UPDATE anveo_calls SET `file` = '{$file}' WHERE `session` = '{$session}'");
}

saveToLog();

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

function saveToLog()
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

        $getArray[] = "GET|{$key}: {$value}";
    }

    foreach ($_POST as $key => $value)
    {
        if (!empty($value)) {
            $value = getClearValue($value);
        }
        else {
            $value = '!EMPTY!';
        }

        $getArray[] = "POST|{$key}: {$value}";
    }

    foreach ($_FILES as $key => $values)
    {
        if (!empty($value)) {
            $value = getClearValue($value);
        }
        else {
            $value = '!EMPTY!';
        }

        $getArray[] = "FILES|{$key}: {$value}";
    }


    if (!empty($getArray)) {
        func_backprocess_log('anveo_logging', implode("\n---\n", $getArray));
    }
}