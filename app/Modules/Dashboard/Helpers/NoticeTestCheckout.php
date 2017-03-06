<?php
namespace Modules\Dashboard\Helpers;

use Modules\Core\Models\GlobalConfigModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Main\Xcart;

class NoticeTestCheckout
{
    public static function test()
    {
        $model = OrderModel::objects()->filter(['groups__cb_status' => 'AP'])->order(['-orderid'])->limit(1)->get();
        $user = Xcart::app()->user;

        $no_orders_test_checkout_hide_time = Xcart::app()->request->session->get('no_orders_test_checkout_hide_time');

        $diff_order_time = time() - $model->date;
        $no_orders_test_checkout_sec = current(GlobalConfigModel::objects()->filter(['name' => 'no_orders_test_checkout'])->valuesList(['value'], true)) * 60;
        $show_no_orders_test_checkout_message = false;

        if ($diff_order_time > $no_orders_test_checkout_sec){

            if (!empty($no_orders_test_checkout_hide_time)){
                $diff_no_orders_test_checkout_hide_time = time() - $no_orders_test_checkout_hide_time;
                $show_message_in_time = 60*60;

                if ($diff_no_orders_test_checkout_hide_time > $show_message_in_time){
                    $show_no_orders_test_checkout_message = true;
                    $no_orders_test_checkout_hide_time = "";

                    Xcart::app()->request->session->add('no_orders_test_checkout_hide_time', $no_orders_test_checkout_hide_time);
                }
                else {
                    $show_no_orders_test_checkout_message = false;
                }
            }
            else {
                $show_no_orders_test_checkout_message = true;
            }

            if ($show_no_orders_test_checkout_message){
                $log_text = "{$user->firstname} ({$user->login}) has seen 'Test checkout' notification.";
                func_backprocess_log("Test_checkout", $log_text);
            }
        }

        return $show_no_orders_test_checkout_message;
    }
}