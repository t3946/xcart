<?php
namespace Modules\Order\Models;

use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderDetail;

class OrderDetailModel  extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass(): string
    {
        return OrderDetail::class;
    }

    public static function tableName()
    {
        return 'xcart_order_details';
    }

    public static function getFields()
    {
        return [
            'itemid' => [
                'class' => AutoField::className(),
            ],
            'product_model' => [
                'field' => 'productid',
                'class' => ForeignField::className(),
                'modelClass' => ProductModel::className(),
                'link' => ['productid' => 'productid'],
                'null' => false,
            ],
            'back' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0
            ],
            'retail_trust_price' => [
                'class' => DecimalField::className(),
                'null' => false,
                'default' => 0
            ],
            'extra_data' => [
                'class' => SerializeField::className(),
                'null' => false,
                'default' => '',
            ],
            'product_options' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],

            'order_groups' => [
                'field' => 'orderid',
                'class' => ForeignField::className(),
                'modelClass' => OrderGroupModel::className(),
                'link' => ['orderid' => 'orderid'],
                'null' => false,
            ]
        ];
    }
}