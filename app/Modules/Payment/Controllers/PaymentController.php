<?php

namespace Modules\Payment\Controllers;


use Exception;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Models\ProcessorModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class PaymentController extends Controller
{
    /**
     * @param $gateway
     */
    public function process($gateway)
    {
        /** @var ProcessorModel $pm */
        if ($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])){
            if ($gw = Gateway::getGateway($pm)){

                $app = Xcart::app();
                $user = $app->user;
                $cart = $app->cart;
                if (!$cart->getCartNumber() || $cart->getIsEmpty()) {
                    $this->redirect('cart:list');
                }

                /** @var OrderModel $order */
                $order = OrderModel::objects()->get([
                    'cart_number' => $cart->getCartNumber(),
                ]);

                try {

                    $params = [
                        'cancelUrl' => Xcart::app()->router->absoluteUrl("payment:cancel", ['gateway' => strtolower($pm->processor_name)]),
                        'returnUrl' => Xcart::app()->router->absoluteUrl("payment:return", ['gateway' => strtolower($pm->processor_name)]),
                        'notifyUrl' => Xcart::app()->router->absoluteUrl("payment:success", ['gateway' => strtolower($pm->processor_name)]),
                        'amount' => number_format($order->total, 2, '.', ''),
                        'currency' => 'USD'
                    ];

                    if ($response = $gw->purchase($params)) {
                        //create order here

                        if ($gw->result->isRedirect()) {
                            $gw->result->redirect();
                        }

                        $this->redirect("payment:return", ['gateway' => strtolower($pm->processor_name)]);
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
        $pm = ProcessorModel::objects()->get(['processor_name' => $gateway]);

        if (!$pm) {
            $this->error(404);
        }

        $app = Xcart::app();
        $cart = $app->cart;
        if ($cart->getCartNumber()){
            if ($order = OrderModel::objects()->get(['cart_number' => $cart->getCartNumber()])) {
                $order->cb_status = OrderStatusModel::ORDER_STATUS_AUTHORIZED;
                $order->save();
                $this->redirect("checkout:complete");
            }
        }

    }

    public function endpoint($gateway)
    {
        $params = null;

        /** @var \Modules\Core\CoreModule $coreModule */
        $coreModule = Xcart::app()->getModule('Core');
        $config = $coreModule::getGlobalConfig();

        if ($bodyReceived = file_get_contents('php://input')) {

            if ($params = json_decode($bodyReceived, true)) {

                switch ($params['event_type']) {

                    case 'CUSTOMER.DISPUTE.CREATED':

                        if ($txn = OrderTransactionModel::objects()->get(['transaction_id' => $params['buyer_transaction_id']])) {

                            OrderTagEventHelper::orderTagEvent($config['Attention_tags_invoices']['tag_for_events_dispute_created'], $txn->order->orderid);
                        }

                        break;
                }
            }
        }

        if (!$params) {

            if (Xcart::app()->request->request->has('txn_type') && Xcart::app()->request->request->has('txn_id')) {

                if ($txn = OrderTransactionModel::objects()->get(['transaction_id' => Xcart::app()->request->request->get('txn_id')])) {

                    switch (Xcart::app()->request->request->get('txn_type')) {
                        case 'new_case':

                            OrderTagEventHelper::orderTagEvent($config['Attention_tags_invoices']['tag_for_events_dispute_created'], $txn->order->orderid);

                            break;
                    }
                }
            }
        }

        Xcart::app()->logger->info("{$gateway} IPN response", $params ?: $_REQUEST ?: [], 'ipn');
    }
}