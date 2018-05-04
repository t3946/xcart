<?php

namespace Modules\Payment\Controllers;


use Exception;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
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
        if ($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])) {

            if ($gw = Gateway::getGateway($pm)) {

                /** @var OrderModel $order */
                $order = OrderHelper::getCartOrder();

                if (!$order) {
                    $this->redirect('cart:list');
                }

                try {

                    $params = [
                        'cancelUrl' => Xcart::app()->router->absoluteUrl("payment:cancel", ['gateway' => strtolower($pm->processor_name)]),
                        'returnUrl' => Xcart::app()->router->absoluteUrl("payment:return", ['gateway' => strtolower($pm->processor_name)]),
                        'notifyUrl' => Xcart::app()->router->absoluteUrl("payment:success", ['gateway' => strtolower($pm->processor_name)]),
                        'amount' => number_format($order->total, 2, '.', ''),
                        'order' => $order,
                        'currency' => 'USD'
                    ];

                    if ($gw->purchase($params)) {
                        $order->cb_status = OrderStatusModel::ORDER_STATUS_NOT_FINISHED;
                        $order->save();

                        $order->groups->update(['cb_status' => $order->cb_status]);

                        $params = [
                            'mode' => OrderTransactionModel::TYPE_AUTHORIZATION,
                            'amount' => $order->total,
                            'currency' => 'USD',
                        ];

                        $transaction = new OrderTransactionModel(array_merge(
                            OrderTransactionHelper::prepareOrderTransaction($gw, $params),
                            [
                                'transaction_status' => OrderTransactionModel::STATUS_PENDING,
                                'orderid' => $order->orderid,
                                'type' => $params['mode'],
                                'paymentid' => $order->paymentid,
                            ])
                        );
                        $transaction->save();

                        if ($gw->result->isRedirect()) {
                            $gw->result->redirect();
                        }

                        //$this->redirect('payment:return', ['gateway' => strtolower($pm->processor_name)]);
                    }

                } catch (Exception $e) {
                    exit('Sorry, there was an error processing your payment. Please try again later.');
                }
            }
        }
    }

    public function success($gateway): void
    {
        $params = Xcart::app()->request->request->all();

        Xcart::app()->logger->debug("{$gateway} callback action", $params, 'payment');

        /** @var ProcessorModel $pm */
        if ($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])) {
            if ($gw = Gateway::getGateway($pm)) {
                try {

                    $gw->success($params);

                } catch (Exception $e) {
                    Xcart::app()->logger->error("{$gateway} callback action error : {$e->getMessage()}", $params, 'payment');
                }
            }
        }
    }

    public function cancel($gateway)
    {
        $order = OrderHelper::getCartOrder();

        if ($order) {
            $order->cb_status = OrderStatusModel::ORDER_STATUS_QUEUED;
            $order->save();

            $order->groups->update(['cb_status' => $order->cb_status]);
        }

        $this->redirect("checkout:review");
    }

    public function return($gateway): void
    {
        $pm = ProcessorModel::objects()->get(['processor_name' => $gateway]);

        if (!$pm) {
            $this->error(404);
        }

        if (($action = Xcart::app()->request->get->get('action')) && $action === 'cancel') {
            $this->cancel($gateway);
        }

        if ($order = OrderHelper::getCartOrder()) {
            //$order->cb_status = OrderStatusModel::ORDER_STATUS_AUTHORIZED;
            //$order->save();
            $this->redirect("checkout:complete");
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