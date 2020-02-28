<?php


namespace Modules\Order\Controllers\Api;


use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class AfterShipController extends Controller
{
    public function webHook()
    {
        $request = $this->getRequest();
        $json = key($request->request->all());
        if ($params = json_decode($json, true)) {
            Xcart::app()->logger->debug($params, [], 'afterShip');
        }
    }
}