<?php

namespace Modules\Payment\Controllers;


use Exception;
use Modules\Core\Components\GlobalConfig;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderInvoiceHelper;
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
    public function process($gateway): void
    {
        /** @var ProcessorModel $pm */
        if (($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])) && $gw = Gateway::getGateway($pm)) {

            /** @var OrderModel $order */
            $order = OrderHelper::getCartOrder();

            if (!$order) {
                $this->redirect('cart:list');
            }

            $order->cb_status = OrderStatusModel::ORDER_STATUS_QUEUED;
            $order->save();

            $hash = md5($order->orderid.$order->total.$order->email);

            try {

                $params = [
                    'cancelUrl' => Xcart::app()->router->absoluteUrl('payment:cancel', ['gateway' => strtolower($pm->processor_name)]),
                    'returnUrl' => Xcart::app()->router->absoluteUrl('payment:return', ['gateway' => strtolower($pm->processor_name), 'order_id' => $order->orderid, 'slug' => $hash]),
                    'notifyUrl' => Xcart::app()->router->absoluteUrl('payment:success', ['gateway' => strtolower($pm->processor_name)]),
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
                        'payment_method_model' => $order->payment_method_model
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
                Xcart::app()->logger->error("{$gateway} process action error", [$e->getMessage()], 'payment');
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

        //Xcart::app()->logger->debug("{$gateway} callback action", $params, 'payment');

        /** @var ProcessorModel $pm */
        if ($pm = ProcessorModel::objects()->get(['processor_name' => $gateway])) {
            if ($gw = Gateway::getGateway($pm)) {
                try {

                    $gw->success($params);

                } catch (\Exception $e) {
                    Xcart::app()->logger->error("{$gateway} callback action error : {$e->getMessage()}", $params, 'payment');
                }
            }
        }
    }

    public function cancel($gateway): void
    {
        $order = OrderHelper::getCartOrder();

        if ($order) {
            $order->cb_status = OrderStatusModel::ORDER_STATUS_FAILED;
            $order->save();

            $order->groups->update(['cb_status' => $order->cb_status]);
        }

        $this->redirect('checkout:review');
    }

    public function return($gateway, $order_id, $slug): void
    {
        $pm = ProcessorModel::objects()->get(['processor_name' => $gateway]);

        if (!$pm) {
            $this->error(404);
        }

        if (($action = Xcart::app()->request->get->get('action')) && $action === 'cancel') {
            $this->cancel($gateway);
        }

        $this->redirect('checkout:complete', ['order_id' => $order_id, 'slug' => $slug]);

    }

    /**
     * @param $gateway
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function endpoint($gateway): void
    {
        if ($app = Xcart::app()) {
            $params = $order = null;

            $config = GlobalConfig::getInstance();

            if (($bodyReceived = file_get_contents('php://input')) && $params = json_decode($bodyReceived, true)) {

                switch ($params['event_type']) {

                    case 'CUSTOMER.DISPUTE.CREATED':

                        /** @var OrderTransactionModel $txn */
                        if ($txn = OrderTransactionModel::objects()->get(['transaction_id' => $params['buyer_transaction_id']])) {

                            OrderTagEventHelper::orderTagEvent($config['tag_for_events_dispute_created'], $txn->order->orderid);
                        }

                        break;
                }
            }

            if (!$params) {

                if ($app->request->request->has('txn_type') && $app->request->request->has('txn_id')) {

                    $txn_data = ['transaction_id' => $app->request->request->get('txn_id')];

                    if ($app->request->request->has('custom') && (int) $order_id = $app->request->request->get('custom')) {
                        /** @var OrderModel $order */
                        $order = OrderModel::objects()->get(['orderid' => $order_id]);
                        $txn_data['orderid'] = $order->orderid;
                    }

                    /** @var OrderTransactionModel $txn */
                    [$txn] = OrderTransactionModel::objects()->getOrCreate($txn_data);

                    switch ($app->request->request->get('txn_type')) {
                        case 'new_case':

                            if ($order = $txn->order) {
                                OrderTagEventHelper::orderTagEvent($config['tag_for_events_dispute_created'], $order->orderid);
                            }

                            break;
                        case 'web_accept':

                            if (\in_array($app->request->request->get('payment_status'), ['Pending', 'Authorized'])) {
                                if ($order) {
                                    $txn->setAttributes([
                                        'orderid' => $order->orderid,
                                        'type' => OrderTransactionModel::TYPE_AUTHORIZATION,
                                        'transaction_status' => OrderTransactionModel::STATUS_PENDING,
                                        'transaction_amount' => $app->request->request->get('payment_gross'),
                                        'login' => $order->login,
                                        'paymentid' => $order->paymentid,
                                    ]);

                                    $txn->save();

                                    $txn->transaction_response = $app->request->request->all();
                                    $txn->save();

                                    if (($payment_gross = (float)$app->request->request->get('payment_gross')) && $payment_gross === (float)$order->total) {

                                        $app->event->trigger('order:paid', ['model' => $order]);

                                    }
                                }
                            }

                            break;
                    }
                }
            }

            $app->logger->info("{$gateway} IPN response", $params ?: $_REQUEST ?: [], 'ipn');
        }
    }
}