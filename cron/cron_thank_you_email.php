<?php
use Mindy\QueryBuilder\Q\QOr;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Forms\Helpers\SnippetHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Sites\Models\SiteConfigModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\CsTipsModel;
use Xcart\App\Main\Xcart;

define("CIDEV_CRON_START", "CRON");
global $config, $mail_smarty;

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

$log_category = 'cron_thank_you_email';
$start_time = new DateTime('now');
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log($log_category, $log_text);

if ($storefrontsModels = SiteModel::objects()->all()){
    foreach ($storefrontsModels as $storefrontModel) {
        $defaultDays = GlobalConfigModel::objects()->get(['name' => 'thank_you_days']);
        $configDays = SiteConfigModel::objects()->get(['name' => 'thank_you_days', 'storefrontid' => $storefrontModel->storefrontid]);
        $thank_you_days = abs(empty($configDays) ? (int)$defaultDays->value : (int)$configDays->value);
        $days_to_check = 60 * 60 * $thank_you_days;
        $diff_time = time() - $days_to_check;
        $dateTime = new DateTime();
        $dateTime->setTimestamp($diff_time);

        /** @var OrderModel[] $ordersModels */
        $ordersModels = OrderModel::objects()
            ->filter([
                'groups__dc_status' => OrderStatusModel::ORDER_DC_STATUS_DELIVERED,
                'order_type' => 'XCART',
                'groups__cb_status__in' => [
                    OrderStatusModel::ORDER_STATUS_COMPLETED,
                    OrderStatusModel::ORDER_STATUS_UNPAID_PO,
                    OrderStatusModel::ORDER_STATUS_PARTIAL_REFUND],
                'groups__dc_update_datetime__lt' => $dateTime,
                'storefrontid' => $storefrontModel->storefrontid
            ])
            ->exclude([
                new QOr([
                    'thankyou_for_order_email_sent' => 'Y'])
            ])->group(['orderid'])->all();


        if (!empty($ordersModels)) {
            /** @var OrderModel $orderModel */
            foreach($ordersModels as $orderModel){
                $send = true;

//                $csTipsModel = new CsTipsModel();
//
//                $csTipsModel->order_id = $orderModel->orderid;
//                $csTipsModel->getHash();
//                $csTipsModel->calculateOrderTips();
//
//                if (
//                    $csTipsModel->capture_amount
//                    && $csTipsModel->capture_amount > 0
//                    && $csTipsModel->first_tip
//                    && $csTipsModel->first_tip > 0
//                )
//                {
//                    $mass = [
//                    'order' => $orderModel->orderid,
//                    'hash' => $csTipsModel->hash
//                    ];
//
//                    $ssl = ($storefrontModel->getConfig()['https_enabled'] == 'Y');
//                    $url = ($ssl ? 'https' : 'http') . '://' . $storefrontModel->domain . \Xcart\App\Main\Xcart::app()->router->url('user:cs_tips', [], $mass);
//                    $image = ($ssl ? 'https' : 'http') . '://' . $storefrontModel->domain . '/static/frontend/production/tip_now.png';
//
//                    $link = "<span><a href=\"{$url}\"><img src=\"{$image}\"></a></span>";
//
//                    $message = "We always strive to build a strong relationship with our clients, so if you are satisfied with our customer service please let us know by leaving a tip. To do so, click the \"TIP NOW\" button below." . " <br> " . " {$link}";
//
//                    $str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
//                    $log_text = "This order {$orderModel->orderid} 'thank you' email message with tips";
//                    func_backprocess_log($log_category, $log_text);
//                }
//                else {
//                    $message = "";
//                }


                if ($send) {
                    $defaultFrom = GlobalConfigModel::objects()->get(['name' => 'thank_you_from']);
                    $configFrom = SiteConfigModel::objects()->get(['name' => 'thank_you_from', 'storefrontid' => $orderModel->storefrontid]);
                    if (!$orderModel->isAmazon()) {
                        $defaultSubject = GlobalConfigModel::objects()->get(['name' => 'thank_you_subject']);
                        $defaultMessage = GlobalConfigModel::objects()->get(['name' => 'thank_you_message_body']);
                        $configSubject = SiteConfigModel::objects()->get(['name' => 'thank_you_subject', 'storefrontid' => $orderModel->storefrontid]);
                        $configMessage = SiteConfigModel::objects()->get(['name' => 'thank_you_message_body', 'storefrontid' => $orderModel->storefrontid]);
                    } else {
                        $defaultSubject = GlobalConfigModel::objects()->get(['name' => 'thank_you_amazon_subject']);
                        $defaultMessage = GlobalConfigModel::objects()->get(['name' => 'thank_you_message_body_amazon']);
                        $configSubject = SiteConfigModel::objects()->get(['name' => 'thank_you_amazon_subject', 'storefrontid' => $orderModel->storefrontid]);
                        $configMessage = SiteConfigModel::objects()->get(['name' => 'thank_you_message_body_amazon', 'storefrontid' => $orderModel->storefrontid]);
                    }

                    $from = $configFrom ? $configFrom->value : $defaultFrom->value;
                    $message = $configMessage ? $configMessage->value : $defaultMessage->value;
                    $subject = $configSubject ? $configSubject->value : $defaultSubject->value;

                    if (!empty($defaultSubject->value) && !empty($defaultMessage->value)) {
                        try {
                            Xcart::app()->mail->raw(
                                $orderModel->email,
                                SnippetHelper::render($subject, ['order' => $orderModel]),
                                SnippetHelper::render($message, ['order' => $orderModel]),
                                [
                                    'from' => [$from => ''],
                                    'bcc' => ['romann@s3stores.com' => ''],
                                ]
                            );

                        } catch (\Exception $exception) {
                            Xcart::app()->logger->error($exception->getMessage(),
                                ['from' => $from, 'subject' => $subject, 'message' => $message], 'email');
                        }

                        $orderModel->thankyou_for_order_email_sent = 'Y';
                        $orderModel->save();

                        $log = 'Thank you email sent by system <br />';
                        func_log_order($orderModel->orderid, 'X', $log);
                    }
                }
            }
        }
    }
}

$str_time = (new DateTime('now'))->diff($start_time)->format('%H:%I:%S');
$log_text = "Cron completed. Processing time: {$str_time}";
func_backprocess_log($log_category, $log_text);

print "Done.";