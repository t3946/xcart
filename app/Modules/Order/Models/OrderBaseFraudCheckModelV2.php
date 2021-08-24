<?php

namespace Modules\Order\Models;

use Modules\Core\Models\FraudAllQuestionModel;
use Modules\Goods\Models\ProductHardResellModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

/**
 * Class OrderFraudCheckModel
 * @package Modules\Order\Models
 * @property OrderModel order
 * @property BaseFraudCheckModelV2 question
 * @property string fraud_result
 * @property string|float fraud_score
 * @property string|int question_id
 * @property string manual_action
 * @property string additional_info
 */
class OrderBaseFraudCheckModelV2 extends Model
{
    use AutoMetaTrait;
    public const FRAUD_RESULT_POSITIVE = 'positive';
    public const FRAUD_RESULT_NEGATIVE = 'negative';

    public static function tableName()
    {
        return 'xcart_order_fraud_checks_v2';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'additional_info' => [
                'class' => SerializeField::class,
                'null' => false,
                'default' => []
            ],
            'question' => [
                'field' => 'question_id',
                'class' => ForeignField::class,
                'modelClass' => BaseFraudCheckModelV2::class,
                'link' => ['question_id' => 'id'],
                'null' => false,
            ],
            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid'],
                'null' => false,
            ],
            'fraud_result' => [
                'class' => CharField::class,
                'null' => true,
                'default' => true,
                'choices' => [
                    self::FRAUD_RESULT_POSITIVE,
                    self::FRAUD_RESULT_NEGATIVE,
                ]
            ],
            'manual_action' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'choices' => [
                    'Y',
                    'N'
                ]
            ]
        ];
    }
}