<?php


namespace Modules\Order\Models;


use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class OrderGroupRefundProductModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_refunded_products';
    }

    public static function getFields()
    {
        return [
            'refund_product_id' => AutoField::class,
            'refund_group' => [
                'field' => 'refund_group_id',
                'class' => ForeignField::class,
                'modelClass' => OrderGroupRefundModel::class,
                'link' => ['refund_group_id' => 'refund_group_id']
            ],
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid']
            ],
            'order_detail' => [
                'class' => HasManyField::class,
                'modelClass' => OrderDetailModel::class,
                'link' => ['productid' => 'productid', 'orderid' => 'orderid']
            ],
        ];
    }

    public function getOrderDetail(): ?OrderDetailModel
    {
        return $this->order_detail->limit(1)->get();
    }

    public function getRestockingFee()
    {
        $detail = $this->getOrderDetail();

        if (!$detail || !$detail->price) {
            return 0;
        }

        $refund_price = $this->ref_price;

        return round((1 - $refund_price / $detail->price) * 100);
    }

    public function getSubtotal(): float
    {
        return round($this->ref_price * $this->ref_qty, 2);
    }
}