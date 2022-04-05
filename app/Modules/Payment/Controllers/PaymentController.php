<?php

namespace Modules\Payment\Controllers;


use Exception;
use Modules\Core\Components\GlobalConfig;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Middleware\OrderCheckoutMiddleware;
use Modules\Order\Models\Decisions\DecisionModel;
use Modules\Order\Models\Decisions\DecisionTypeModel;
use Modules\Order\Models\OrderCxInvoiceModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Models\TransactionLogModel;
use Modules\Order\Stores\OrderStore;
use Modules\Payment\Gateways\AbstractGateway;
use Modules\Payment\Models\ProcessorModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class PaymentController extends Controller
{
    /**
     * @param $gateway
     */
    public function process($gateway): void
    {
        /** @var ProcessorModel $pm */
        if (($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])) && $gw = AbstractGateway::getGateway($pm)) {

            /** @var OrderModel $order */
            $order = OrderHelper::getCartOrder();

            if (!$order) {
                $this->redirect('cart:list');
            }

            $order->cb_status = OrderStatusModel::ORDER_STATUS_QUEUED;
            $order->save();

            try {
                $timestamp = time();
                $order_number = "{$order->pk}_$timestamp";
                $params = [
                    'cancelUrl' => Xcart::app()->router->absoluteUrl('payment:cancel', ['gateway' => strtolower($pm->processor_name)]),
                    'returnUrl' => Xcart::app()->router->absoluteUrl('payment:return', [
                        'gateway' => strtolower($pm->processor_name),
                        'order_id' => $order->orderid,
                        'slug' => $order->getOrderHash()
                    ]),
                    'notifyUrl' => Xcart::app()->router->absoluteUrl('payment:success', ['gateway' => strtolower($pm->processor_name)]),
                    'amount' => number_format($order->total, 2, '.', ''),
                    'order' => $order,
                    'currency' => $order->currency,
                    'description' => $order->getTransactionDescription(),
                    'processor_model' => $pm,
                    'orderNumber' => $order_number
                ];

                if ($gw->purchase($params) && $gw->result) {

                    $params = [
                        'mode' => OrderTransactionModel::TYPE_AUTHORIZATION,
                        'amount' => $order->total,
                        'currency' => $order->currency,
                        'payment_method_model' => $order->payment_method_model,
                        'uniqueOrderNumber' => $order_number
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
                OrderHelper::changeOrderStatus($order, OrderStatusModel::ORDER_STATUS_NOT_FINISHED, 'cb', false);

                if ($gw->result && $gw->result->isRedirect()) {
                    $gw->result->redirect();
                }

            } catch (Exception $e) {
                Xcart::app()->logger->error("$gateway process action error", [$e->getMessage()], 'payment');
                Xcart::app()->flash->error('Sorry, there was an error processing your payment. Please try again later.');
                $this->redirect('checkout:review');
            }
        }
    }

    /**
     * @param $gateway
     */
    public function success($gateway): void
    {
        $params = Xcart::app()->request->request->all();

        /** @var ProcessorModel $pm */
        if ($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])) {
            if ($gw = AbstractGateway::getGateway($pm)) {
                try {
                    $gw->success($params);

                } catch (Exception $e) {
                    Xcart::app()->logger->error("{$gateway} callback action error : {$e->getMessage()}", $params, 'payment');
                }
            }
        }
    }

    public function cancel($gateway): void
    {
        $order = OrderHelper::getCartOrder();

        if ($order) {
            $order->cb_status = OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3;
            $order->save();

            $order->groups->update(['cb_status' => $order->cb_status]);
        }

        $route = Xcart::app()->request->session->get(OrderCheckoutMiddleware::ORDER_TYPE) === OrderCheckoutMiddleware::ONE_PAGE_CHECKOUT_TYPE
            ? 'checkout:checkoutOnePage'
            : 'checkout:review';

        $this->redirect($route);
    }

    public function return($gateway, $order_id, $slug): void
    {
        $pm = ProcessorModel::objects()->get(['processor_name' => $gateway]);

        if (!$pm) {
            $this->error();
        }

        if (($action = Xcart::app()->request->get->get('action')) && $action === 'cancel') {
            $this->cancel($gateway);
        }

        $this->redirect('checkout:complete', ['order_id' => $order_id, 'slug' => $slug]);

    }

    /**
     * @param $gateway
     * @throws Exception
     */
    public function endpoint($gateway): void
    {
        /** @var OrderCxInvoiceModel $invoice */
        /** @var OrderTransactionModel $txn */

        if ($app = Xcart::app()) {

            $order = $order_id = null;

            $body = $app->request->body;

            $request = $app->request->request;

            $config = GlobalConfig::getInstance();

            switch($gateway) {
                case 'paypal':
                    if ($body->has('event_type')) {

                        switch ($body->event_type) {

                            case 'CUSTOMER.DISPUTE.CREATED':

                                if ($body->has('buyer_transaction_id') &&
                                    $txn = OrderTransactionModel::objects()->get(['transaction_id' => $body->buyer_transaction_id])) {
                                    OrderTagEventHelper::orderTagEvent($config['tag_for_events_dispute_created'], $txn->order->orderid);
                                }

                                break;
                        }
                    }

                    if ($request->has('txn_type') && $request->has('txn_id')) {

                        switch ($request->txn_type) {
                            case 'invoice_payment':

                                if ($request->has('invoice')
                                    && $invoice = OrderCxInvoiceModel::objects()->get(['invoice_number' => $request->invoice])) {
                                    $order_id = $invoice->orderid;
                                }

                                break;
                            case 'web_accept':
                                if ($request->has('custom')) {
                                    $order_id = (int)$request->custom;
                                }
                                break;
                            case 'new_case':
                                if (($txn = OrderTransactionModel::objects()->limit(1)->get(['transaction_id' => $request->txn_id]))
                                    && $order = $txn->order) {
                                    $order_id = $txn->order->orderid;
                                }
                                break;
                        }

                        /** @var OrderModel $order */
                        if ($order_id && $order = OrderModel::objects()->get(['orderid' => $order_id])) {
                            $txn_data = [
                                'transaction_id' => $request->txn_id,
                                'orderid' => $order->orderid
                            ];

                            [$txn] = OrderTransactionModel::objects()->getOrCreate($txn_data);
                        }

                        switch ($request->txn_type) {
                            case 'new_case':

                                if ($order) {
                                    OrderTagEventHelper::orderTagEvent($config['tag_for_events_dispute_created'], $order->orderid);
                                }

                                break;
                            case 'web_accept':
                                //set solved
                                /** @var DecisionTypeModel $decision_type */
                                $decision_type = DecisionTypeModel::objects()->get(['slug'=> "unpaid-order"]);
                                $decisions = DecisionModel::objects()->all([
                                    'order_id' => $order_id,
                                    'decision_type_id' => $decision_type->decision_type_id
                                ]);
                                /** @var DecisionModel $decision */
                                foreach ($decisions as $_ => $decision) {
                                    $options = json_decode(json_encode($decision->options), true);
                                    $options['action'] = "pay-by-paypal";
                                    $decision->options = $options;
                                    $decision->solved = 1;
                                    $decision->save();
                                }

                                /** @var OrderTransactionModel $txn */
                                if ($order && in_array($request->payment_status, ['Pending', 'Authorized'])) {

                                    $gross = (float)$request->payment_gross;

                                    if (!$gross) {
                                        $gross = (float)$request->mc_gross;
                                    }

                                    $txn->setAttributes([
                                        'orderid' => $order->orderid,
                                        'type' => OrderTransactionModel::TYPE_AUTHORIZATION,
                                        'transaction_status' => OrderTransactionModel::STATUS_PENDING,
                                        'transaction_amount' => $gross,
                                        'login' => $order->login,
                                        'paymentid' => $order->paymentid,
                                    ]);

                                    $txn->save();

                                    $txn->transaction_response = $request->all();
                                    $txn->save();
                                    $transactionLog = new TransactionLogModel(
                                        [
                                            'orderid' => $txn->orderid,
                                            'paymentid' => $txn->paymentid,
                                            'order_transaction_id' => $txn->id,
                                            'transaction_id' => $txn->transaction_id,
                                            'transaction_status' => $txn->transaction_status,
                                            'transaction_total' => $txn->transaction_amount,
                                            'transaction_currency' => $txn->transaction_currency,
                                            'login' => $txn->login,
                                            'transaction_log' => $txn->transaction_response
                                        ]
                                    );

                                    if ($transactionLog->isValid()) {
                                        $transactionLog->save();
                                    }

                                    if ($gross && $gross === (float)$order->total) {
                                        $app->event->trigger('order:paid', ['model' => $order]);
                                    }
                                }

                                break;
                            case 'invoice_payment':
                                if ($invoice && $order && $request->payment_status === 'Completed') {
                                    $txn->setAttributes([
                                        'orderid' => $order->orderid,
                                        'type' => OrderTransactionModel::TYPE_CAPTURE,
                                        'transaction_status' => OrderTransactionModel::STATUS_COMPLETED,
                                        'transaction_amount' => $request->payment_gross,
                                        'login' => $order->login,
                                        'paymentid' => 100,
                                    ]);

                                    $txn->save();

                                    $txn->transaction_response = $request->all();
                                    $txn->save();

                                    $invoice->status = OrderCxInvoiceModel::STATUS_PAID;
                                    $invoice->save();

                                    OrderLogModel::createLog(
                                        $order->orderid,
                                        OrderLogModel::LOG_TYPE_SYSTEM,
                                        "Paypal Cx invoice <a href=\"https://www.paypal.com/webscr?cmd=_history-details-from-hub&id={$invoice->invoice_number}\" target=\"_blank\">#{$invoice->invoice_number}</a> has been PAID"
                                    );

                                    if ((float)$order->total > 0 && ($order_store = new OrderStore($order)) && $order_store->getAmountDeficit() == 0) {
                                        $app->event->trigger('order:paid', ['model' => $order]);
                                    }
                                    OrderTagEventHelper::orderTagEvent(5, $order->orderid);

                                }
                                break;
                        }
                    }

                    break;
                case 'stripe':
                    $payment_intent = $body->data['object']['payment_intent'] ?? null;

                    if (!$payment_intent) {
                        break;
                    }

                    if ($txn = OrderTransactionModel::objects()->get(['transaction_id' => $payment_intent])) {
                        switch ($body->type) {
                            case 'charge.dispute.created' :
                                OrderTagEventHelper::orderTagEvent($config['tag_for_events_dispute_created'], $txn->order->orderid);
                                break;
                            case 'charge.expired':
                                $txn->transaction_status = OrderTransactionModel::STATUS_EXPIRED;
                                $txn->save();
                                break;
                        }
                    }
                    break;
            }

            $app->logger->info("{$gateway} IPN response", $body->all() ?: $request->all() ?: [], 'ipn');
        }
    }
}