<?php

namespace Modules\Order\Models;

use Modules\Order\Helpers\BaseFraudCheckHelperV2;
use Modules\Payment\Models\ProcessorModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property mixed auto
 * @property mixed question_template_body
 * @property mixed question_code
 * @property float|string weight
 * @property string type
 * @property int|string question_id
 * @property int orderby
 */
class BaseFraudCheckModelV2 extends Model
{
    use AutoMetaTrait;

    public const FRAUD_TYPE_DIAGONAL = 'diagonal';
    public const FRAUD_TYPE_RED_FLAGS = 'red_flags';
    public const FRAUD_TYPE_PAY_PAL = 'pay_pal';
    public const FRAUD_TYPE_STRIPE = 'stripe';
    public const FRAUD_TYPE_GENERAL_PAYMENT = 'general_payment';
    private array $question_code_pay_pal = [
        'PP-VER',
        'PP_SASA',
        'PP_SASA_C',
        'PP-EE'
    ];

    public static function tableName(): string
    {
        return 'xcart_fraud_check_v2';
    }

    public static function getFields(): array
    {
        return [
            'question_id' => [
                'class' => AutoField::class,
            ],
            'question_template_body' => [
                'class' => CharField::class,
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
            ],
            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
        ];
    }

    public function getScore(OrderModel $order): ?array
    {
        if ($result = $this->getMethodResult($order)) {
            [$fraud_result, $weight, $add_info, $action, $outcome] = $result;
            $fraud_score = (int)$outcome * $weight;
            return [$fraud_result, round($fraud_score, 2), $add_info, $action];
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

    public function getMethodResult(OrderModel $order): ?array
    {
        $result = null;
        /** @var OrderBaseFraudCheckModelV2 $order_fraud */
        if ($order_fraud = OrderBaseFraudCheckModelV2::objects()->get(['order_id' => $order, 'question__question_code' => $this->question_code])) {
            return [$order_fraud->fraud_result, $order_fraud->fraud_score, $order_fraud->additional_info, $order_fraud->manual_action];
        }
        $code_method = str_replace('-', '_', $this->question_code);
        $method = "score$code_method";
        /** Если PayPal|Stripe вопрос, то проверяется оплата заказа была ли через них сделана **/
        if (in_array($this->type, [self::FRAUD_TYPE_PAY_PAL, self::FRAUD_TYPE_STRIPE])
            && !($this->isStripePayment($order) || $this->isPaypalPayment($order))) {
            return null;
        }
        if (method_exists(BaseFraudCheckHelperV2::class, $method)) {
            $result = BaseFraudCheckHelperV2::$method($order, $this);
        }
        return $result;
    }

    public function getFirstTransaction(OrderModel $order)
    {
        if ($log = $order->transactions_log->limit(1)->order(['date'])->get()) {
            return $log->transaction;
        }
        return $order->transactions->limit(1)->order(['-date'])->get();
    }

    public function getResponse(OrderModel $order)
    {
        if (Xcart::app()->template->exists($template = "fraud_check/$this->question_code.tpl")) {
            [, , $add] = $this->getMethodResult($order);
            return Xcart::app()->template->render($template, ['item' => $this, 'additional_info' => $add]);
        }
        return '';
    }

}