<?php

namespace Modules\Payment\Controllers;


use Exception;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Models\ProcessorModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class PaymentController extends Controller
{
    public function process($gateway)
    {
        /** @var ProcessorModel $pm */
        if ($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])){
            if ($gw = Gateway::getGateway($pm)){

                try {
                    $params = [
                        'cancelUrl' => Xcart::app()->router->url("payment:cancel", ['gateway' => strtolower($pm->processor_name)]),
                        'returnUrl' => Xcart::app()->router->url("payment:return", ['gateway' => strtolower($pm->processor_name)]),
                        'noticeUrl' => Xcart::app()->router->url("payment:success", ['gateway' => strtolower($pm->processor_name)]),
                        'amount' => '1.11',
                        'currency' => 'USD'
                    ];

                    $response = $gw->purchase($params);

                } catch (Exception $e){
                    exit('Sorry, there was an error processing your payment. Please try again later.');
                }
            }
        }

    }

    public function success($gateway)
    {
        x_log_flag('log_payment_paypal_processing', 'PAYPAL', $_REQUEST, true);

        if(isset($_GET['success'])) {
            /** @var ProcessorModel $pm */
            if ($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])){
                if ($gw = Gateway::getGateway($pm)) {
                    try {
                        $params = [];
                        $gw->complete($params);
                    }
                    catch (Exception $e) {
                        exit('Sorry, there was an error processing your payment. Please try again later.');
                    }
                }
            }
        }
    }

    public function endpoint($gateway)
    {
        var_dump($gateway);
    }
}