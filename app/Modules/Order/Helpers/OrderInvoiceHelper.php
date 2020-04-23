<?php

namespace Modules\Order\Helpers;


use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusNotificationModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class OrderInvoiceHelper
{
    public static function sendOrderStatusNotification(OrderModel $order, bool $send_copy = true, $status = null): void
    {
        if ($status) {
            $notification = OrderStatusNotificationModel::objects()->get(['code' => $status]);
            $xcartLabel = 'order-status-changed';
        } else {
            $notification = $order->notification;
            $xcartLabel = 'order-status-init';
        }

        if ($notification) {

            /** @var SiteModel $site */
            $site = Xcart::app()->getModule('Sites')->getSite();
            $config = $site->getGlobalConfig();

            $cs_email = $config['orders_department'] ?? 'orders@s3stores.com';

            try {
                Xcart::app()->mail->template(
                    $order->email,
                    str_replace('{{orderid}}', $order->getOrderNumber(), $notification->customer_subject),
                    'mail/invoice.tpl',
                    ['order' => $order,'notification' => $notification],
                    ['from' => $cs_email]
                );

                if ($send_copy) {
                    Xcart::app()->mail->template(
                        $cs_email,
                        str_replace('{{orderid}}', $order->getOrderNumber(), $notification->copy_subject),
                        'mail/invoice.tpl',
                        ['order' => $order, 'type' => 'A', 'notification' => $notification],
                        [
                            'from' => [$cs_email => $order->firstname],
                            'reply_to' => [$order->email => $order->firstname],
                            'bcc' => ['romann@s3stores.com' => ''],
                            'headers' => [
                                'X-Xcart-Label' => $xcartLabel
                            ]
                        ]
                    );
                }
            } catch (\Exception $exception) {
                Xcart::app()->logger->error($exception->getMessage(), $config ?? [], 'email');
            }
        }
    }

    public static function getInvoiceHtml(OrderModel $order, $template = 'mail/invoice.tpl', $mode = null)
    {
        if ($notification = $order->notification) {
            /** @var SiteModel $site */
            $site = Xcart::app()->getModule('Sites')->getSite();
            $config = $site->getGlobalConfig();

            return Xcart::app()->template->render($template, ['order' => $order, 'mode' => $mode]);
        }
    }
}