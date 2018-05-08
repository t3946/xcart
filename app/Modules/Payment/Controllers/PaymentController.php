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
use PayPal\Api\Transaction;
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
                        'currency' => 'USD',
                        'description' => "S3 Stores, Inc. Order # {$order->getOrderNumber()}"
                    ];

                    if ($gw->purchase($params)) {

                        $params = [
                            'mode' => OrderTransactionModel::TYPE_AUTHORIZATION,
                            'amount' => $order->total,
                            'currency' => 'USD',
                            'payment_method_model' => $order->payment_method
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
                    }

                    $order->cb_status = OrderStatusModel::ORDER_STATUS_NOT_FINISHED;
                    $order->save();

                    $order->groups->update(['cb_status' => $order->cb_status]);

                    if ($gw->result && $gw->result->isRedirect()) {
                        $gw->result->redirect();
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

    /**
     * @param $gateway
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
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

                $txn_data = ['transaction_id' => Xcart::app()->request->request->get('txn_id')];

                if (Xcart::app()->request->request->has('custom')) {
                    /** @var OrderModel $order */
                    $order = OrderModel::objects()->get(['orderid' => Xcart::app()->request->request->get('custom')]);
                    $txn_data['orderid'] = $order->orderid;
                }

                /** @var OrderTransactionModel $txn */
                [$txn] = OrderTransactionModel::objects()->getOrCreate($txn_data);

                switch (Xcart::app()->request->request->get('txn_type')) {
                    case 'new_case':

                        if ($order = $txn->order) {
                            OrderTagEventHelper::orderTagEvent($config['Attention_tags_invoices']['tag_for_events_dispute_created'], $order->orderid);
                        }

                        break;
                    case 'web_accept':

                        $txn->setAttributes([
                            'orderid' => $order->orderid,
                            'type' => OrderTransactionModel::TYPE_AUTHORIZATION,
                            'transaction_status' => OrderTransactionModel::STATUS_PENDING,
                            'transaction_amount' => Xcart::app()->request->request->get('payment_gross'),
                            'transaction_response' => Xcart::app()->request->request->all(),
                            'login' => $order->login,
                            'paymentid' => $order->paymentid,
                        ]);

                        $txn->save();

                        Xcart::app()->event->trigger('order:paid', ['model' => $order]);

                        break;

                }
            }
        }

        Xcart::app()->logger->info("{$gateway} IPN response", $params ?: $_REQUEST ?: [], 'ipn');
    }
}