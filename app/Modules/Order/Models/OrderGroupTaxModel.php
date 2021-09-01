<?php


namespace Modules\Order\Models;


use Modules\Sites\Models\TaxRatesModel;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property TaxRatesModel tax_rate
 * @property float value
 */
class OrderGroupTaxModel extends Model
{
    public static function tableName()
    {
        return 'xcart_order_group_taxes';
    }

    public static function getFields()
    {
        return [
            'order_group' => [
                'field' => 'order_group_id',
                'class' => ForeignField::class,
                'modelClass' => OrderGroupModel::class,
                'link' => ['order_group_id' => 'order_group_id'],
                'primary' => true
            ],
            'tax_rate' => [
                'field' => 'tax_rate_id',
                'class' => ForeignField::class,
                'modelClass' => TaxRatesModel::class,
                'link' => ['tax_rate_id' => 'rateid'],
                'primary' => true
            ],
            'value' => [
                'class' => DecimalField::class,
                'default' => 0
            ]
        ];
    }
}