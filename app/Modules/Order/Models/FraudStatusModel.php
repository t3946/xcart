<?php

namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property mixed|\Xcart\App\Orm\Fields\Field|\Xcart\App\Orm\Fields\FileField|\Xcart\App\Orm\Fields\ModelFieldInterface|null code
 * @property mixed|\Xcart\App\Orm\Fields\Field|\Xcart\App\Orm\Fields\FileField|\Xcart\App\Orm\Fields\ModelFieldInterface|null name
 */
class FraudStatusModel extends Model
{
    use AutoMetaTrait;

    public const STATUS_NOT_YET_STARTED = 'N';
    public const STATUS_ORDER_DECLINED = 'T';
    public const STATUS_NEED_EXPERT = 'E';
    public const STATUS_UNDER_REVIEW = 'R';
    public const STATUS_UNDEFINED = 'U';
    public const STATUS_FRAUD_PROBABLY = 'B';
    public const STATUS_FRAUD_PURE = 'P';
    public const STATUS_CLEARED = 'C';
    public const STATUS_FRAUD_CHARGEBACK = 'K';

    public static function tableName()
    {
        return 'xcart_order_fraud_statuses';
    }

    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::className(),
                'primary' => true
            ],
        ];
    }
}