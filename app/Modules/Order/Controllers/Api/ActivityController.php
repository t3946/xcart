<?php


namespace Modules\Order\Controllers\Api;


use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class ActivityController extends Controller
{
    public function hook($order_id, $action)
    {
        Xcart::app()->event->trigger('order:view', ['order_id' => $order_id, 'action' => $action]);
    }
}