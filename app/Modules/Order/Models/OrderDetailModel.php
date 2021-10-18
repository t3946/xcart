<?php
namespace Modules\Order\Models;

use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductOptionVariantModel;
use Xcart\App\Main\Xcart;
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
 * @property float price
 * @property int amount
 * @property OrderModel order
 * @property int orderid
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
                'class' => AutoField::class,
            ],
            'product_model' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
                'null' => false,
            ],
            'back' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
            'retail_trust_price' => [
                'class' => DecimalField::class,
                'null' => false,
                'default' => 0
            ],
            'extra_data' => [
                'class' => SerializeField::class,
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
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['orderid' => 'orderid'],
                'null' => false,
            ],
        ];
    }

    public function getAmazonCompetitorMinPrice(): array
    {
        /** @var ProductModel $product */
        if ($product = $this->product_model) {
            return $product->getAmazonArbitragePrice($this->amount);
        }

        return [];
    }

    public function getOptions(): array
    {
        if ($product_options = $this->product_options) {
            foreach ($product_options as $productOptionId => $variantId) {
                if ($optionItem = (new ProductOptionVariantModel)->findItem($productOptionId, $variantId)) {
                    $result[$optionItem->product_option->option->title] = $optionItem->variant->name;
                }
            }
        }
        return $result ?? [];
    }

    public function getAbsoluteUrl($full = false)
    {
        if ($this->productid) {
            $url = Xcart::app()->router->url('catalog:product:view', ['id' => $this->productid, 'slug' => '']);

            if ($full && $site = $this->order_group->order->site) {
                $url = '//' . $site->domain . rtrim($url, '/') .'/';
            }

            return $url;
        }

        return false;
    }
}