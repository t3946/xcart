<?php

namespace Modules\Order\Models;

use Modules\Core\Models\TelephoneAreaModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Order\Helpers\BaseFraudCheckHelperV2;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Payment\Models\ProcessorModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Helpers\PhoneHelper;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Model;

/**
 * @property mixed auto
 * @property mixed question_template_body
 * @property mixed question_code
 * @property float|string weight
 * @property string type
 * @property int|string id
 */
class BaseFraudCheckModelV2 extends Model
{
    use AutoMetaTrait;

    private $result;
    private $order_fraud_model;
    public const FRAUD_TYPE_DIAGONAL = 'diagonal';
    public const FRAUD_TYPE_RED_FLAGS = 'red_flags';
    public const FRAUD_TYPE_PAY_PAL = 'pay_pal';
    public const FRAUD_TYPE_STRIPE = 'stripe';
    public const FRAUD_TYPE_GENERAL_PAYMENT = 'general_payment';
    private array $question_code_pay_pal = [
        'MANUAL_PAYPAL_FULLNAME_VERIFIED',
        'MANUAL_PAYPAL_SHIPPING_EQUAL_BILLING',
        'MANUAL_PAYPAL_SHIPPING_CONFIRMED',
        'MANUAL_PAYPAL_EMAIL_EQUAL_TO_ORDER'
    ];

    public static function tableName()
    {
        return 'xcart_fraud_check_v2';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'question_template_body' => [
                'class' => CharField::className(),
                'null' => true,
                'default' => null
            ],
            'weight' => [
                'class' => FloatField::class,
                'null' => true,
                'default' => 0,
            ],
            'active' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'choices' => [
                    'Y' => 'Y',
                    'N' => 'N',
                ],
            ],
            'type' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'choices' => [
                    self::FRAUD_TYPE_DIAGONAL,
                    self::FRAUD_TYPE_RED_FLAGS
                ]
            ]
        ];
    }

    public function getScore(OrderModel $order, $recalc = true)
    {
        if ($result = $this->getMethodResult($order, $recalc)) {
            [$fraud_result, $weight, $add_info, $action] = $result;
            $outcome = 0;
            if ($fraud_result === 'positive') {
                $outcome = 1;
            }
            return [$fraud_result, round($weight * $outcome, 2), $add_info, $action];
        }
        return null;
    }

    public function isPaypalPayment(OrderModel $order): bool
    {
        /** @var OrderTransactionModel $oTransaction */
        if ($oTransaction = $this->getFirstTransaction($order)) {
            return ($oTransaction->payment_method_model->frontend_processor->processor_name === ProcessorModel::PAYMENT_NAME_PAYPAL);
        }
        return false;
    }

    public function isStripePayment(OrderModel $order): bool
    {
        /** @var OrderTransactionModel $transaction_model */
        if ($transaction_model = $order->getFirstTransaction()) {
            return ($transaction_model->payment_method_model->frontend_processor->processor_name === ProcessorModel::PAYMENT_NAME_STRIPE);
        }
        return false;
    }

    public function isBluePayPayment(OrderModel $order_model) : bool
    {
        /** @var OrderTransactionModel $transaction_model */
        if ($transaction_model = $order->getFirstTransaction()) {
            return ($transaction_model->payment_method_model->frontend_processor->processor_name === ProcessorModel::PAYMENT_NAME_BLUEPAY);
        }
        return false;
    }

    public function getMethodResult(OrderModel $order, $recalc = true)
    {
        if ($this->result === null) {
            /** @var OrderBaseFraudCheckModelV2 $order_fraud */
            if (!$recalc && $order_fraud = OrderBaseFraudCheckModelV2::objects()->get(['orderid' => $order, 'question_code' => $this->question_code])) {
                $this->result = [$order_fraud->fraud_result, $order_fraud->fraud_score, $order_fraud->additional_info, $order_fraud->manual_action];
                return $this->result;
            }
            $method = "score{$this->question_code}";
            /** Если PayPal|Stripe вопрос, то проверяется оплата заказа была ли через них сделано **/
            if ($this->type === self::FRAUD_TYPE_PAY_PAL) {
                if (!$this->isPaypalPayment($order)) {
                    return $this->result;
                }
            } else if ($this->type === self::FRAUD_TYPE_STRIPE) {
                if (!$this->isStripePayment($order)) {
                    return $this->result;
                }
            }
            if (method_exists(BaseFraudCheckHelperV2::class, $method)) {
                $this->result = BaseFraudCheckHelperV2::$method($order, $this);
            }
        }
        return $this->result;
    }

    public function getFirstTransaction(OrderModel $order)
    {
        if ($log = $order->transactions_log->limit(1)->order(['date'])->get()) {
            return $log->transaction;
        }
        return $order->transactions->limit(1)->order(['-date'])->get();
    }

    public function getOrderFraudCheck($orderModel)
    {
        if ($this->order_fraud_model === null) {
            $this->order_fraud_model = OrderFraudCheckModel::objects()->get(['orderid' => $orderModel->orderid, 'question_code' => $this->question_code]);
        }
        return $this->order_fraud_model;
    }

    public function getManualAction(OrderModel $order): ?string
    {
        if (($this->auto !== 'Y') && $of = $this->getOrderFraudCheck($order)) {
            return $of->manual_action;
        }
        return null;
    }

    public function getResponse(OrderModel $order)
    {
        if (Xcart::app()->template->exists($template = "fraud_check/{$this->question_code}.tpl")) {
            [, , $add] = $this->getMethodResult($order, false);
            return Xcart::app()->template->render($template, ['item' => $this, 'additional_info' => $add]);
        }
        return '';
    }

}