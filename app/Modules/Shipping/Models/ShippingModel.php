<?php
namespace Modules\Shipping\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Shipping;

class ShippingModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass(): string
    {
        return Shipping::class;
    }

    public static function tableName()
    {
        return 'xcart_shipping';
    }

    public static function getFields()
    {
        return [
            'shippingid' => [
                'class' => AutoField::className()
            ],
            'important' => [
                'class' => IntField::className(),
                'length' => 1,
                'null' => false,
                'default' => 0,
                'chosen' => [
                    0 => 'No',
                    1 => 'Yes',
                ],
            ],
        ];
    }

    public function getFrontendName()
    {
        return $this->frontend_name ?: func_insert_trademark($this->shipping);
    }
}