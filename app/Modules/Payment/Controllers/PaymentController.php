<?php

namespace Modules\Payment\Controllers;


use Exception;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Models\ProcessorModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Logger\Logger;
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
                        'cancelUrl' => Xcart::app()->router->absoluteUrl("payment:cancel", ['gateway' => strtolower($pm->processor_name)]),
                        'returnUrl' => Xcart::app()->router->absoluteUrl("payment:return", ['gateway' => strtolower($pm->processor_name)]),
                        'notifyUrl' => Xcart::app()->router->absoluteUrl("payment:success", ['gateway' => strtolower($pm->processor_name)]),
                        'amount' => '1.11',
                        'currency' => 'USD'
                    ];

                    if ($response = $gw->purchase($params)) {

                        //create order here

                        if ($gw->result->isRedirect()) {
                            $gw->result->redirect();
                        }
                    }

                } catch (Exception $e){
                    exit('Sorry, there was an error processing your payment. Please try again later.');
                }
            }
        }

    }

    public function success($gateway)
    {
        //x_log_flag('log_payment_paypal_processing', 'PAYPAL', $_REQUEST, true);

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

    public function cancel($gateway)
    {
        var_dump($gateway);
    }

    public function ret($gateway)
    {
        var_dump($gateway);
    }

    public function endpoint($gateway)
    {
        Xcart::app()->logger->info("{$gateway} IPN response",$_REQUEST ?: [], 'ipn');
    }
}