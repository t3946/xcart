<?php


namespace Modules\Core\Classes;


use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;

class SaveFilePrice
{
    public int $time_exec = 0;
    public int $count_update = 0;
    private int $count_image_field = 0;
    private array $ar_update_field;
    private array $success_productcode = [];
    private array $fields_image = [];
    private string $dx_code;
    public array $search_by = [];

    public function __construct(DistributorModel $dx_model, array $search_by)
    {
        $this->dx_code = $dx_model->code;
        $this->search_by = $search_by;
    }

    public function sendStats(): void
    {
        $site = Xcart::app()->getModule('Sites')->getSelectedSite();
        $ar_active = [
            'active_sku' => $this->success_productcode,
            'dx_code' => $this->dx_code,
            'process_time' => $this->time_exec,
            'feed_source' => 'price',
            'products_in_feed' => $this->count_update,
            'feed_source_date' => date('Y-m-d H:i:s'),
            'storefront' => $site->pk
        ];
        Xcart::app()->queue->send('products_active_test', json_encode($ar_active));
    }

    public function collectField(array $select, $ar_save): void
    {
        foreach ($select as $table_index => $ar_key) {
            foreach ($ar_key as $key => $field) {
                $value = array_column($ar_save[$table_index], $key);
                if ($field === 'images') {
                    $this->count_image_field++;
                    $this->ar_update_field[$table_index]["{$field}_{$this->count_image_field}"] = $value;
                    $this->fields_image[$table_index][] = "{$field}_{$this->count_image_field}";
                } else {
                    $this->ar_update_field[$table_index][$field] = $value;
                }
            }
        }
    }

    public function savePrice(): void
    {
        $time_start = time();
        foreach ($this->ar_update_field as $table_index => $ar_fields) {
            foreach ($ar_fields[$this->search_by[$table_index]] as $key => $code) {
                $search_value = "{$this->dx_code}-{$code}";
                if ($this->search_by[$table_index] !== 'productcode') {
                    $search_value = $code;
                }
                $product = ProductModel::objects()->get([$this->search_by[$table_index] => $search_value]);
                if ($product instanceof ProductModel) {
                    $this->sendProductData($product, $table_index, $key);
                    $this->success_productcode[] = "{$this->dx_code}-{$code}";
                }
            }
        }
        $this->time_exec = time() - $time_start;
    }

    private function sendProductData(ProductModel $product_model, int $t_index, int $num_row)
    {
        $ar_field = [];
        foreach ($this->ar_update_field[$t_index] as $field => $item) {
            switch ($field) {
                case 'productcode':
                    $ar_field[$field] = "{$this->dx_code}-{$item[$num_row]}";
                    break;
                default:
                    if (!in_array($field, $this->fields_image[$t_index])) {
                        $ar_field[$field] = $item[$num_row];
                    }
                    break;
            }
        }
        if (!empty($this->fields_image)) {
            $this->sendImageData($product_model, $t_index, $num_row);
        }
        if (!empty($ar_field)) {
            $ar_field['hash_product'] = md5(json_encode($ar_field, JSON_THROW_ON_ERROR));
            Xcart::app()->queue->send('products_test', json_encode($ar_field));
            $this->count_update++;
        }
    }

    private function sendImageData(ProductModel $productModel, int $table_index, int $num_row)
    {
        $ar_images = [];
        foreach ($this->fields_image[$table_index] as $field) {
            $ar_images[] = $this->ar_update_field[$table_index][$field][$num_row];
        }
        $ar_images_rabbit['images'] = $ar_images;
        $ar_images_rabbit['product_code'] = $productModel->productcode;

        Xcart::app()->queue->send('images_test', json_encode($ar_images_rabbit));
    }
}