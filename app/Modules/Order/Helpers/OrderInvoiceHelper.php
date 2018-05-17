<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderModel;
use Xcart\App\Main\Xcart;

class OrderInvoiceHelper
{
    public static function sendOrderStatusNotification(OrderModel $order): void
    {

        /*Xcart::app()->mail->template(
            $order->email,
            str_replace($order->notification->customer_subject, '{{orderid}}', $order->orderid),
            'mail/invoice.tpl',
            ['order' => $order]
        );*/

        $result = Xcart::app()->mail::renderTemplate(
            'mail/invoice.tpl',
            ['order' => $order]
        );
        echo $result;
        exit;
    }
}