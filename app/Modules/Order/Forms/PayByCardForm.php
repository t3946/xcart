<?php

namespace Modules\Order\Forms;

use Modules\Core\Forms\FrontendForm;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderTransactionHelper;
use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\OrderModule;
use Modules\Payment\Gateways\Gateway;
use Modules\Payment\Models\ProcessorModel;
use Xcart\App\Form\Fields\CharCleanField;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Base;

class PayByCardForm extends FrontendForm
{
    public string $stripe_payment_intent = '';
    public string $public_key = '';

    protected array $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/one_field_checkout.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors.tpl',
        'hintTemplate' => 'forms/field/default/custom/hint.tpl',
        'labelTemplate' => 'forms/field/default/custom/label.tpl',
    ];

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if ($order = OrderHelper::getCartOrder()) {
            $payment_id = (int) $order->payment_method_model->paymentid;
            if ($payment_id === 0) {
                return;
            }
            $params = [
                'amount' => $order->total,
                'currency' => $order->currency,
                'description' => $order->getTransactionDescription(),
                'order' => $order
            ];

            /** @var ProcessorModel $pm */
            if (
                ($pm = ProcessorModel::objects()->get(['processor_name' => 'Stripe']))
                && $gw = Gateway::getGateway($pm)
            ) {
                if ($transaction = $order
                    ->transactions
                    ->filter([
                        'transaction_status' => OrderTransactionModel::STATUS_PENDING,
                        'transaction_amount' => $params['amount'] ?? 0,
                        'transaction_currency' => $params['currency'] ?? 'USD',
                        'paymentid' => $payment_id])
                    ->limit(1)
                    ->get()) {
                    $transaction_id = $transaction->transaction_response['client_secret'] ?? '';
                } else {
                    if ($stripe_customer = Xcart::app()->request->session->get('stripe_customer_reference')) {
                        $customer = $gw->gateway->fetchCustomer([
                            'customerReference' => $stripe_customer
                        ])->send();
                    } else {
                        $customer = $gw->gateway->createCustomer(
                            [
                                'email' => $order->email,
                                'name' => $order->b_firstname ?: $order->firstname,
                                'description' => $order->orderid,
                            ]
                        )->send();
                        Xcart::app()->request->session->add('stripe_customer_reference', $customer->getCustomerReference());
                    }

                    $intent = $gw->gateway->createPaymentIntent(
                        array_merge(
                            $params,
                            [
                                'metadata' => ['order' => $order->orderid, 'email' => $order->email],
                                'connectedAccount' => $gw::CONNECTED_ACCOUNT_ID,
                                'setupFutureUsage' => 'off_session',
                                'captureMethod' => 'manual',
                                'customerReference' => $customer->getCustomerReference()
                            ]
                        )
                    )->send();
                    $gw->result = $intent;

                    $transaction_id = $intent->getData() ? $intent->getData()['client_secret'] ?? '' : '';

                    $transaction = new OrderTransactionModel(array_merge(
                            OrderTransactionHelper::prepareOrderTransaction($gw, $params),
                            [
                                'transaction_status' => OrderTransactionModel::STATUS_PENDING,
                                'orderid' => $order->orderid,
                                'type' => 'authorization',
                                'paymentid' => $order->paymentid,
                            ])
                    );
                    if ($payment_id === 106) {
                        $transaction->save();
                    }
                }

                $this->stripe_payment_intent = $transaction_id;
                $this->public_key = $pm->param01 ?? '';
            }
        }
    }

    public function getFields()
    {
        return [
            'pbc_card_holder_name' => [
                'class' => CharCleanField::class,
                'label' => OrderModule::t('Cardholder name'),
                'required' => true,
                'html' => [
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'labelClass' => 'common-label common-label_required checkout__single-common-label',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],

            'pbc_card_details' => [
                'class' => CharField::class,
                'label' => OrderModule::t('Credit / Debit card details'),
                'required' => true,
                'html' => [
                    'data-correct' => 'common-input__correct',
                    'data-wrong' => 'common-input__wrong',
                ],
                'inputTemplate' => 'forms/field/stripe/input.tpl',
                'labelClass' => 'common-label common-label_required checkout__single-common-label',
                'hintClass' => 'common-hint',
                'fieldClass' => 'checkout-field',
                'errorClass' => 'form-field-error form-field__error checkout__error error_checkout',
            ],
        ];
    }
}