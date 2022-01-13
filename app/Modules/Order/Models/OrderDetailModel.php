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
 * @property ProductModel product_model
 * @property int amount
 */
class OrderDetailModel extends Model
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
            'amount' => [
                'class' => IntField::class,
            ]
        ];
    }

    public function getAmazonCompetitorMinPrice(): ?array
    {
        $result = null;

        /** @var ProductModel $product */
        if ($product = $this->product_model) {
            $result = $product->getAmazonArbitragePrice($this->amount);
        }

        return $result;
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
                $url = '//' . $site->domain . rtrim($url, '/') . '/';
            }

            return $url;
        }

        return false;
    }
}