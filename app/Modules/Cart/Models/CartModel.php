<?php

namespace Modules\Cart\Models;

use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

/**
 * Class CartModel
 * @package Modules\Cart\Models
 *
 * @property int id
 * @property array data
 * @property string|\DateTime created_at
 */
class CartModel extends Model
{
    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'data' => SerializeField::class,
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
        ];
    }
    public function getProducts(): array
    {
        $ar_products = [];
        foreach ($this->data['cart'] as $item_cart) {
            /** @var ProductModel $product */
            $product = $item_cart->_object;
            $ar_products[] = [
                'name' => $product->product,
                'sku' => $product->productcode,
                'price' => $product->getPrice(),
                'quantity' => $item_cart->_quantity,
                'totalPrice' => $item_cart->_price,
                'urlProduct' => $product->getAbsoluteUrl(),
                'image' => $product->getMainImage() ? $product->getMainImage()->getCdnURL(ProductImageModel::IMAGE_SIZE_THUMB) : null
            ];
        }
        return $ar_products ?? [];
    }
}