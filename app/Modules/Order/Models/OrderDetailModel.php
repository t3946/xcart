<?php
namespace Modules\Order\Models;

use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderDetail;

/**
 * @property int order_group_id
 */
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
                'class' => SerializeField::class,
                'null' => true,
                'default' => null,
            ],
            'order_group' => [
                'field' => 'order_group_id',
                'class' => ForeignField::class,
                'modelClass' => OrderGroupModel::class,
                'link' => ['order_group_id' => 'order_group_id'],
                'null' => false,
            ],
        ];
    }

    public function getAmazonCompetitorMinPrice():? array
    {
        $result = null;

        /** @var ProductModel $product */
        if ($product = $this->product_model) {
            $result = $product->getAmazonArbitragePrice($this->amount);
        }

        return $result;

    }
}