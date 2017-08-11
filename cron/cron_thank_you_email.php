<?php
use Mindy\QueryBuilder\Q\QOr;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Order\Models\OrderModel;
use Modules\Sites\Models\SiteConfigModel;
use Modules\Sites\Models\SiteModel;

define("CIDEV_CRON_START", "CRON");
global $config, $mail_smarty;

require "../top.inc.php";
require "../init.php";

$log_category = 'cron_thank_you_email';
$start_time = new DateTime('now');
$log_text = " * * *  Cron started  * * * ";
func_backprocess_log($log_category, $log_text);

if ($storefrontsModels = SiteModel::objects()->all()){
    foreach ($storefrontsModels as $storefrontModel) {
        $defaultDays = GlobalConfigModel::objects()->get(['name' => 'thank_you_days']);
        $configDays = SiteConfigModel::objects()->get(['name' => 'thank_you_days', 'storefrontid' => $storefrontModel->storefrontid]);
        $thank_you_days = abs(empty($configDays) ? intval($defaultDays->value) : intval($configDays->value));
        $days_to_check = 60 * 60 * 24 * $thank_you_days;
        $diff_time = time() - $days_to_check;

        /** @var OrderModel[] $ordersModels */
        $ordersModels = OrderModel::objects()
            ->filter([
                'tracking_all_filled' => 'Y',
                'tracking_fill_time__lt' => $diff_time,
                'storefrontid' => $storefrontModel->storefrontid
            ])
            ->exclude([
                new QOr([
                    'thankyou_for_order_email_sent' => 'Y',
                    'tracking_fill_time' => 0])
            ])->all();


        if (!empty($ordersModels)) {
            foreach($ordersModels as $orderModel){
                $send = false;
                if ($groups = $orderModel->groups){
                    $send = true;
                    foreach($groups as $groupModel){
                        if (!((in_array($groupModel->cb_status, ['P', 'O', 'H'])
                            && in_array($groupModel->dc_status, ['S', 'L', 'C'])))){
                            $send = false;
                            break;
                        }
                    }
                }
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

                    if (!empty($defaultSubject->value) && !empty($defaultMessage->value)) {
                        $oMail = \Xcart\App\Main\Xcart::app()->oldMail;
                        $oMail->init();
                        $oMail->addReplaceRule('{{orderid}}', $orderModel->getOrderNumber());
                        $oMail->addReplaceRule('{{c-fullname}}', $orderModel->firstname);
                        $oMail->to = $orderModel->email;
                        $oMail->from = (empty($configFrom)) ? $defaultFrom->value : $configFrom->value;
                        $oMail->subject = (empty($configSubject)) ? $defaultSubject->value : $configSubject->value;
                        $oMail->body = (empty($configMessage)) ? $defaultMessage->value : $configMessage->value;
                        $oMail->sendEmail();
                        $oMail->to = 'igor@s3stores.com';
                        $oMail->sendEmail();

                        $orderModel->thankyou_for_order_email_sent = 'Y';
                        $orderModel->save();

                        $log = "Thank you email sent by system <br />";
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