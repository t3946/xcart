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
                str_replace('{{orderid}}', $order->orderid, $notification->customer_subject),
                'mail/invoice.tpl',
                ['order' => $order],
                ['from' => $config['orders_department']]
            );

            if ($send_copy) {
                Xcart::app()->mail->template(
                    $config['orders_department'],
                    str_replace( '{{orderid}}', $order->orderid, $notification->copy_subject),
                    'mail/invoice.tpl',
                    ['order' => $order],
                    [
                        'from' => [$config['orders_department'] => $order->firstname],
                        'reply_to' => [$order->email => $order->firstname],
                        'bcc' => 'romann@s3stores.com',
                        'headers' => [
                            'X-Xcart-Label' => 'order-status-init'
                        ]
                    ]
                );
            }
        }
    }
}