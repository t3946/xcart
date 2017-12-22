<?php

namespace Modules\User\Controllers\Admin;

use Modules\Admin\Controllers\BackendController;
use Modules\Order\Models\OrderModel;
use Modules\User\Helpers\IdentityCheckHelper;
use Xcart\App\Main\Xcart;

class IdentityCheckController extends BackendController
{
    public function actionCallback()
    {
        if (!empty($_GET)) {
            $order_id = $_GET['order_id'];

            /** @var OrderModel $order_model */
            if ($order_model = OrderModel::objects()->get(['orderid' => $order_id])) {

                $pageTitle = "Identity Checker";
                $phone = $ip_info = '';

                if ($phone = $order_model->phone) {
                    $phone = trim($phone);
                    if (strpos($phone, '+') !== 0) {
                        if (strlen($phone) > 10) {
                            $phone = '+' . $phone;
                        }
                        else {
                            $phone = '+1' . $phone;
                        }
                    }
                }

                if ($order_extras = $order_model->extra_info->filter(['khash' => 'ip'])->get()) {
                    $ip_info = !empty($order_extras->value) ? $order_extras->value : '';
                }
                
                if ((empty($ip_info)) && !empty($order_model->extra['ip_info'])) {
                    $ip_info = $order_model->extra['ip_info'];
                    $regexp = '/.*?(\d+\.\d+\.\d+\.\d+).*/';
                    if (preg_match($regexp, $ip_info, $matches)) {
                        $ip_info = $matches[1];
                    }
                }

                Xcart::app()->breadcrumbs->add('identity check');
                echo $this->renderInSmarty("admin/icheck/index.tpl", [
                    'order' => $order_model,
                    'page_title' => $pageTitle,
                    'phone' => $phone,
                    'ip_info' => $ip_info,
                ]);
            }
        }

    }

    public function actionRequest()
    {
        if (!empty($_GET)) {

            $url = 'https://proapi.whitepages.com/3.2/identity_check.json';
            $headers = ['Accept' => 'application/json'];
            $query = [
                'primary.name' => $_GET['primary_name'],
                'primary.phone' => $_GET['primary_phone'],
                'primary.address.street_line_1' => $_GET['primary_address_street_line_1'],
                'primary.address.city' => $_GET['primary_address_city'],
                'primary.address.state_code' => $_GET['primary_address_state_code'],
                'primary.address.postal_code' => $_GET['primary_address_postal_code'],
                'primary.address.country_code' => $_GET['primary_address_country_code'],
                'secondary.name' => $_GET['secondary_name'],
                'secondary.phone' => $_GET['secondary_phone'],
                'secondary.address.street_line_1' => $_GET['secondary_address_street_line_1'],
                'secondary.address.city' => $_GET['secondary_address_city'],
                'secondary.address.state_code' => $_GET['secondary_address_state_code'],
                'secondary.address.postal_code' => $_GET['secondary_address_postal_code'],
                'secondary.address.country_code' => $_GET['secondary_address_country_code'],
                'email_address' => $_GET['email_address'],
                'ip_address' => $_GET['ip_address'],
                'api_key' => $_GET['api_key'],
            ];

            $response = IdentityCheckHelper::getCurlContent($url, $headers, $query);

            $response = json_decode($response, true);

            $pageTitle = "Response";

            Xcart::app()->breadcrumbs->add('info');
            echo $this->renderInSmarty("admin/icheck/index.tpl", [
                'page_title' => $pageTitle,
                'response' => $response,
            ]);

        }
    }
}