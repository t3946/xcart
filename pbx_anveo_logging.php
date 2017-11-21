<?php

use Modules\PBX\Models\PbxAnveoModel;
use Modules\Order\Models\OrderUserActivityModel;
use Modules\Order\Models\OrderUserLastActivityModel;
use Modules\User\Models\PbxOptionsModel;
use Modules\User\Models\UserModel;

define("CIDEV_CRON_START", "CRON");

require_once "top.inc.php";
require_once "init.php";


$now = date('Y-m-d H:i:s');

if (!empty($_GET['incoming_flow_start']) || !empty($_GET['outgoing_flow_start']))
{
    $session = getDecodedString($_GET['ss']);

    if (!empty($_GET['outgoing_flow_start'])) {
        $session = getDecodedString($_GET['ss']);

        (new PbxAnveoModel(['session' => $session, 'start_at' => $now, 'is_outgoing' => true, 'e164' => $_GET['ee']]))->save();

    } else {
        $session = getDecodedString($_GET['ss']);

        $mass = explode('-', $session);
        $anveo_account = $mass[4];

        $e164 = getDecodedString($_GET['ee']);
        (new PbxAnveoModel(['session' => $session, 'start_at' => $now, 'e164' => $e164]))->save();
    }
}

if (!empty($_GET['incoming_flow_end']) || !empty($_GET['outgoing_flow_end']))
{
    $session = getDecodedString($_GET['ss']);
    if ($model = PbxAnveoModel::objects()->get(['session' => $session])) {
        $model->end_at = $now;
        $model->save();
    }
}

if (!empty($_GET['lost_call']))
{
    sleep(3);
    $session = getDecodedString($_GET['ss']);
    if ($model = PbxAnveoModel::objects()->get(['session' => $session])){

        $model->is_lost = true;

        if (!empty($_GET['ee'])){
            $ee = getDecodedString($_GET['ee']);
            $model->e164 = $ee;

        }
        if (!empty($_GET['rdnis'])){
            $rdnis = getDecodedString($_GET['rdnis']);
            $model->rdnis = $rdnis;
        }
        if (!empty($_GET['cname'])){
            $cname = getDecodedString($_GET['cname']);
            $model->cname = $cname;
        }

        $model->save();

    } else {

        (new PbxAnveoModel(['session' => $session,
                            'is_lost' => true,
                            'e164' => getDecodedString($_GET['ee']),
                            'rdnis' => getDecodedString($_GET['rdnis']),
                            'cname' => getDecodedString($_GET['cname'])
                        ]))->save();
    }
}

if (!empty($_POST['ss']))
{
    $session = getDecodedString($_POST['ss']);
    if ($model = PbxAnveoModel::objects()->get(['session' => $session])){

        if (!empty($_POST['file']))
        {
            $file = getDecodedString($_POST['file']);
            $model->file = $file;
        }

        if (!empty($_POST['uacc'])) {
            $uacc = getDecodedString($_POST['uacc']);
            $model->anveo_account = $uacc;
        }
        if (!empty($_POST['cnam'])) {
            $cname = getDecodedString($_POST['cnam']);
            $model->cname = $cname;
        }
        if (!empty($_POST['ee'])) {
            $e164 = getDecodedString($_POST['ee']);
            $model->e164 = $e164;
        }

        $model->save();
    }
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

        $now = date('Y-m-d-H-i-s');
        $file_name = "./Anveo/Anveo_log.txt";
        $handle = fopen($file_name, "a");
        $string = "Time: {$now}\n---\n";
        $string .= implode("\n---\n", $getArray);
        $string .= "\n____________________________________________________________\n";
        fwrite($handle, $string);
        fclose($handle);

        file_put_contents("./Anveo/{$now}.txt", implode("\n---\n", $getArray));
    }
}

