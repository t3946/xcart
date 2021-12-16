<?php


namespace Modules\Order\Models;


use Modules\Core\Models\FraudFAQuestionModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

/**
 * @property string compare_coefficient
 * @property mixed fraud_score
 * @property array additional_info
 * @property float outcome
 * @property FraudFAQuestionModel question
 * @property int question_id
 */
class OrderFraudFACheckModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_order_fa_fraud_checks';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class
            ],
            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'null' => false,
                'link' => ['order_id' => 'orderid'],
            ],
            'question' => [
                'field' => 'question_id',
                'class' => ForeignField::class,
                'modelClass' => FraudFAQuestionModel::class,
                'null' => false,
                'link' => ['question_id' => 'question_id'],
            ],
            'fraud_score' => [
                'class' => DecimalField::class,
                'null' => true
            ],
            'outcome' => [
                'class' => DecimalField::class,
                'null' => true,
                'default' => 0.00
            ],
            'compare_coefficient' => [
                'class' => IntField::class,
                'default' => null,
                'null' => true,
            ],
            'additional_info' => [
                'class' => SerializeField::class,
                'null' => false,
                'default' => []
            ]
        ];
    }
}