<?php
namespace Modules\Order\Models;

use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Model;

class OrderGroupInvoiceProductModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_order_group_invoices_products';
    }

    public static function getFields()
    {
        return [
            'invoice_detail_id' => AutoField::class,
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid'],
                'null' => false,
            ],
            'manufacturer' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
            ],
            'invoice_number' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
            'item' => [
                'field' => 'itemid',
                'class' => ForeignField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['itemid' => 'itemid'],
                'null' => false,
                'primary' => true,
            ],
            'item_string' => [
                'field' => 'item_string',
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'primary' => true,
            ],
            'product' => [
                'field' => 'product_id',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['product_id' => 'productid'],
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'class' => TimestampField::class,
            ],
            'invoice' => [
                'field' => 'invoice_id',
                'class' => ForeignField::class,
                'modelClass' => OrderGroupInvoiceModel::class,
                'link' => ['invoice_id' => 'invoice_id'],
            ]
        ];
    }
}