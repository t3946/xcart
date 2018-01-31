<?php

namespace Modules\Order\Controllers\Admin;

use Modules\Admin\Controllers\BackendController;
use Modules\Order\Helpers\OrderTagEventHelper;
use Xcart\App\Main\Xcart;

class ApiTagsController extends BackendController
{
    public function actionAdd($order_id, $status_id)
    {
        $login = Xcart::app()->getUser()->login;

        $status_name = func_query_first_cell("SELECT status FROM xcart_attention_tags_values WHERE status_id='$status_id'");
        $allowed_logins = func_query("SELECT login FROM xcart_attention_tags_values_logins WHERE status_id='$status_id' AND action='set'");
        $allowed_to_set_flag = false;

        if (!empty($allowed_logins) && is_array($allowed_logins))
        {
            foreach ($allowed_logins as $k => $v)
            {
                if ($v["login"] == $login || $v["login"] == "_ANY_") {
                    $allowed_to_set_flag = true;
                    break;
                }
            }
        }

        if ($allowed_to_set_flag) {
            $is_such_additional_tag_status = func_query_first_cell("SELECT status_id FROM xcart_orders_additional_tags WHERE orderid='$order_id' AND status_id='$status_id'");

            if (empty($is_such_additional_tag_status) && $allowed_to_set_flag) {

                OrderTagEventHelper::orderTagEvent($status_id, $order_id);

                $this->jsonResponse([
                    'content' => 'Done.',
                    'type' => 'success',
                ]);

                die();
            }
        }

        $this->jsonResponse([
            'content' => "You cannot add the '{$status_name}' tag.",
            'type' => 'error',
        ]);
    }

    public function actionDel($order_id, $status_id)
    {
        $login = Xcart::app()->getUser()->login;

        $status_name = func_query_first_cell("SELECT status FROM xcart_attention_tags_values WHERE status_id='$status_id'");
        $allowed_logins = func_query("SELECT login FROM xcart_attention_tags_values_logins WHERE status_id='$status_id' AND action='unset'");
        $allowed_to_unset_flag = false;

        if (!empty($allowed_logins) && is_array($allowed_logins))
        {
            foreach ($allowed_logins as $k => $v)
            {
                if ($v["login"] == $login || $v["login"] == "_ANY_") {
                    $allowed_to_unset_flag = true;
                    break;
                }
            }
        }

        if ($allowed_to_unset_flag) {
            $is_such_additional_tag_status = func_query_first_cell("SELECT status_id FROM xcart_orders_additional_tags WHERE orderid='$order_id' AND status_id='$status_id'");

            if (!empty($is_such_additional_tag_status) && $allowed_to_unset_flag) {


                db_query("DELETE FROM xcart_orders_additional_tags WHERE status_id='$status_id' AND orderid='$order_id'");


                $log = "'" . $status_name . "' attention tag removed";
                func_log_order($order_id, 'X', $log, $login);

                $this->jsonResponse([
                    'content' => 'Done.',
                    'type' => 'success',
                ]);

                die();
            }
        }

        $this->jsonResponse([
            'content' => "You cannot remove the '{$status_name}' tag.",
            'type' => 'error',
        ]);
    }
}