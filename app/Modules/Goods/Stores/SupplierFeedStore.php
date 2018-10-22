<?php

namespace Modules\Goods\Stores;

use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\GoodsModule;
use Xcart\App\Store\BaseStore;

class SupplierFeedStore extends BaseStore
{
    public $supplier_id;
    public $supplier_name;
    public $original_url;
    public $create_date;
    public $feed_type;
    public $products_in_feed = null;
    public $defaults = [];
    public $dont_update_fields = [];
    public $products = [];
    public $errors = [];
    /**
     * @var SupplierFeedModel
     */
    public $feed_model = null;

    public function __construct($feed_model, $feed)
    {
        $this->feed_model = $feed_model;

        if (($content = json_decode($feed, true)) && \is_array($content)) {
            $this->populate($content);
        }
    }

    public function isValid()
    {
        if (empty($this->products) || !\is_array($this->products)) {
            $this->errors[] = GoodsModule::t('manufacturerid: {mid}. No products found. ({feed_type})',
                ['{mid}' => $this->feed_model->manufacturerid, '{feed_type}' => $this->feed_model->getField('feed_type')->toText()]);
            return false;
        }
        if ($this->count() != $this->products_in_feed) {
            $this->errors[] = GoodsModule::t('manufacturerid: {mid}. Corrupted feed file (by products in feed count). ({feed_type}) {c1} vs {c2}',
                ['{mid}' => $this->feed_model->manufacturerid, '{feed_type}' => $this->feed_model->getField('feed_type')->toText(), '{c1}' => $this->count(), '{c2}' => $this->products_in_feed]);
            return false;
        }
        if ($this->supplier_id != $this->feed_model->manufacturerid) {
            $this->errors[] = GoodsModule::t('manufacturerid: {mid}. Wrong supplier_id. ({feed_type}) . Feed skipped.',
                ['{mid}' => $this->feed_model->manufacturerid, '{feed_type}' => $this->feed_model->getField('feed_type')->toText()]);
            return false;
        }
        if ($this->feed_model->last_update_items_count > 0) {
            if (($this->products_in_feed / $this->feed_model->last_update_items_count) < $this->feed_model->threshold) {
                $this->errors[] = GoodsModule::t('manufacturerid: {mid}. Too few products in feed in comparison with last update {c1} against {c2}. ({feed_type})',
                    ['{mid}' => $this->feed_model->manufacturerid, '{feed_type}' => $this->feed_model->getField('feed_type')->toText(), '{c1}' => $this->products_in_feed, '{c2}' => $this->feed_model->last_update_items_count]);
                return false;
            }
        }

        return true;
    }

    public function populate(array $feed)
    {
        $product_cols_replace =
            [
                'sku' => 'productcode',
                'quantity' => 'r_avail',
                'eta_date' => 'eta_date_mm_dd_yyyy',
                'title' => 'product',
                'listprice' => 'list_price'
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
                if ($doNotUpdateFiled === 'images') {
                    $doNotUpdateFiled = 'supplier_images';
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

    public function count(): int
    {
        return \count($this->products);
    }

    public function getFeedDate()
    {
        $create_date_arr = explode('-', $this->create_date);
        return mktime(0, 0, 0, $create_date_arr[0], $create_date_arr[1], $create_date_arr[2]);

    }

    public static function replaceFeedFields($data)
    {
        $data['productcode'] = str_replace(' ', '-', strtoupper(trim($data['sku'] ?? $data['productcode'])));
        $data['r_avail'] = $data['quantity'] ?? $data['r_avail'];
        $data['eta_date_mm_dd_yyyy'] = $data['eta_date'] ?? $data['eta_date_mm_dd_yyyy'];
        $data['product'] = $data['title'] ?? $data['product'];
        $data['list_price'] = $data['listprice'] ?? $data['list_price'];

        $data = array_filter($data, function ($v) {
            return $v !== null;
        });

        if (isset($data['eta_date_mm_dd_yyyy'])) {
            $data['eta_date_mm_dd_yyyy'] = strtotime($data['eta_date_mm_dd_yyyy']);
        }

        if (isset($data['images'])) {
            $data['supplier_images'] = $data['images'];
            $data['supplier_images'] = array_map('html_entity_decode', $data['supplier_images']);
            unset($data['images']);
        }

        return $data;
    }
}