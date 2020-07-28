<?php


namespace Modules\Payment\Gateways;


use Modules\Order\Models\OrderTransactionModel;
use Omnipay\Stripe\Message\PaymentIntents\ConfirmPaymentIntentRequest;
use Omnipay\Stripe\Message\PaymentIntents\Response;
use Xcart\App\Main\Xcart;

class Stripe extends Gateway
{
    public static function getProcessorName()
    {
        return 'Stripe';
    }

    public function init()
    {
        parent::init();
        $this->gateway->initialize([
            'apiKey' => 'sk_test_51FmjzfBBFmepO8dOYfc0LN8QImGbPGfIq2gu95ZffQPLJcTwdZzir7Kndz5oggnWNerV7Q9aPxvagWxEKwkCZAKT00SRojdCTt',
        ]);
    }

    public function refund($params)
    {
        // TODO: Implement refund() method.
    }

    public function void($params)
    {
        // TODO: Implement void() method.
    }

    public function capture($params)
    {
        // TODO: Implement capture() method.
    }

    public function lookup($params)
    {
        // TODO: Implement lookup() method.
    }

    public function authorize($params)
    {
        $this->result = $this->gateway
            ->authorize($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function reauthorize($params)
    {
        // TODO: Implement reauthorize() method.
    }

    public function purchase($params)
    {
        if (Xcart::app()->request->getIsPost()) {
            $this->result = $this->gateway->authorize(array_merge($params, [
                'token' => Xcart::app()->request->post->get('token'),
                //'confirm' => false
            ]))->send();
        } else {
            $intent = $this->gateway->createPaymentIntent(
                array_merge($params, ['metadata' => ['integration_check' => 'accept_a_payment', 'order' => $params['order']->orderid]])
            )->send();
            Xcart::app()->template->display('checkout/stripe_checkout.tpl',
                array_merge($params, ['client_secret' => $intent->getData() ? $intent->getData()['client_secret'] : '']));
            $this->result = $intent;
            return $intent->isSuccessful();
        }
    }

    public function complete($params)
    {
        // TODO: Implement complete() method.
    }

    public function getState($mode)
    {

    }

    public function success($params): void
    {
        $payload = @file_get_contents('php://input');
        $pay = json_decode($payload, true);
        $response = new Response($this->gateway->createPaymentIntent($params), json_encode($pay['data']['object']));
        $data = $response->getData();
        $txn_id = $data['id'] ?? null;
        //Xcart::app()->logger->debug("Stripe callback action", $data ?? [], 'payment');
        if ($response->isSuccessful() && $txn_id && $this->txn = OrderTransactionModel::objects()->get(['transaction_id' => $txn_id])) {
            $this->txn->setAttributes([
                'transaction_response' => $data,
                'transaction_id' => $response->getTransactionReference(),
            ]);
            $this->txn->transaction_status = OrderTransactionModel::STATUS_AUTHORIZED;
            $this->txn->save();
        }
        parent::success($params);

    }
}