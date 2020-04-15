<?php


namespace Modules\Order\Controllers\Api;


use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class AfterShipController extends Controller
{
    public function webHook()
    {
        $json = file_get_contents('php://input');
        Xcart::app()->logger->debug($json, [], 'afterShip');
        if ($params = json_decode($json, true)) {
            Xcart::app()->logger->debug($params, [], 'afterShip');
        }
    }
}