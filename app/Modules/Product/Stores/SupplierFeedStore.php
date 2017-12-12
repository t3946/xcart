<?php

namespace Modules\Product\Stores;

use Modules\Product\Models\ProductModel;
use Xcart\App\Store\BaseStore;

class SupplierFeedStore extends BaseStore
{
    public $supplier_id = null;
    public $supplier_name = null;
    public $original_url = null;
    public $create_date = null;
    public $feed_type = null;
    public $products_in_feed = null;
    public $defaults = [];
    public $dont_update_fields = [];
    public $products = [];

    public function __construct($feed)
    {
        if ($content = json_decode($feed, true)) {
            if (is_array($content)) {
                $this->populate($content);
            }
        }

    }

    public function populate(array $feed)
    {
        $product_cols_replace =
            [
                "sku" => "productcode",
                "quantity" => "r_avail",
                "eta_date" => "eta_date_mm_dd_yyyy",
                "title" => "product",
                "listprice" => "list_price"
            ];

        $this->supplier_id = $feed['supplier_id'];
        $this->supplier_name = $feed['supplier_name'];
        $this->original_url = $feed['original_url'];
        $this->create_date = $feed['create_date'];
        $this->feed_type = $feed['feed_type'];
        $this->products_in_feed = $feed['products_in_feed'];
        $this->defaults = $feed['defaults'];

        if (!empty($feed['dont_update_fields'])) {
            foreach ($feed['dont_update_fields'] as $doNotUpdateFiled) {
                if ($doNotUpdateFiled == "images") {
                    $doNotUpdateFiled = "supplier_images";
                }
                $idx = array_search($doNotUpdateFiled, array_keys($product_cols_replace));
                if ($idx !== false) {
                    $this->dont_update_fields[] = $product_cols_replace[$doNotUpdateFiled];
                } else {
                    $this->dont_update_fields[] = $doNotUpdateFiled;
                }
            }
        }

        if (!empty($feed['products'])) {
            foreach ($feed['products'] as $product) {

                if (isset($product['child_products'])) {
                    foreach ($product['child_products'] as $key => $child) {
                        $product['child_products'][$key] = self::replaceFeedFields($child);
                    }
                }

                $this->products[] = self::replaceFeedFields($product);
            }
        }
    }

    public function count()
    {
        return count($this->products);
    }

    public function getFeedDate()
    {
        $create_date_arr = explode("-", $this->create_date);
        return mktime(0, 0, 0, $create_date_arr[0], $create_date_arr[1], $create_date_arr[2]);

    }

    public static function replaceFeedFields($data)
    {
        $data['productcode'] = strtoupper(trim(!isset($data['sku']) ? $data['productcode'] : $data['sku']));
        $data['r_avail'] = !isset($data['quantity']) ? $data['r_avail'] : $data['quantity'];
        $data['eta_date_mm_dd_yyyy'] = !isset($data['eta_date']) ? $data['eta_date_mm_dd_yyyy'] : $data['eta_date'];
        $data['product'] = !isset($data['title']) ? $data['product'] : $data['title'];
        $data['list_price'] = !isset($data['listprice']) ? $data['list_price'] : $data['listprice'];

        $data = array_filter($data, function ($v) {
            return !is_null($v);
        });

        if (isset($data['eta_date_mm_dd_yyyy'])) {
            $data['eta_date_mm_dd_yyyy'] = strtotime($data['eta_date_mm_dd_yyyy']);
        }

        if (isset($data['images'])) {
            $data['supplier_images'] = $data['images'];
            unset($data['images']);
        }

        return $data;
    }
}