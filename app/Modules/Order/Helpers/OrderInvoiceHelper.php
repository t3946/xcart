<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class OrderInvoiceHelper
{
    public static function sendOrderStatusNotification(OrderModel $order, bool $send_copy = true): void
    {

        if ($notification = $order->notification) {

            /** @var SiteModel $site */
            $site = Xcart::app()->getModule('Sites')->getSite();
            $config = $site->getGlobalConfig();

            Xcart::app()->mail->template(
                $order->email,
                str_replace($notification->customer_subject, '{{orderid}}', $order->orderid),
                'mail/invoice.tpl',
                ['order' => $order],
                ['from' => $config['orders_department']]
            );

            if ($send_copy) {
                Xcart::app()->mail->template(
                    $config['orders_department'],
                    str_replace($notification->copy_subject, '{{orderid}}', $order->orderid),
                    'mail/invoice.tpl',
                    ['order' => $order],
                    [
                        'from' => "{$order->firstname} <{$config['orders_department']}>",
                        'reply_to' => "{$order->firstname} <{$order->email}>",
                        'headers' => [
                            'X-Xcart-Label' => 'order-status-init'
                        ]
                    ]
                );
            }
        }
    }
}