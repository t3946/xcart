<?php


namespace Modules\Core\Classes;


use JsonException;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;

class SaveFilePrice
{
    public int $time_exec = 0; // count seconds of request execution
    public int $count_update = 0;
    private int $count_image_field = 0;
    private array $ar_update_field;
    private array $success_productcode = [];
    private array $fields_image = [];
    private string $dx_code;
    public array $active_for_sale_value;
    public array $search_by = [];

    public function __construct(DistributorModel $dx_model, array $search_by)
    {
        $this->dx_code = $dx_model->code;
        $this->search_by = $search_by;
    }

    /** Send data about full query save price to RabbitMQ
     * @throws JsonException
     */
    public function sendStats(): void
    {
        $ar_active = [
            'active_sku' => $this->success_productcode,
            'dx_code' => $this->dx_code,
            'process_time' => $this->time_exec,
            'feed_source' => 'manual',
            'products_in_feed' => $this->count_update,
            'feed_source_date' => date('Y-m-d H:i:s'),
        ];
        Xcart::app()->queue->send('products_active', json_encode($ar_active, JSON_THROW_ON_ERROR));
    }

    /** Collect data
     * @param array $select
     * @param $ar_save
     */
    public function collectField(array $select, $ar_save): void
    {
        foreach ($select as $table_index => $ar_key) {
            foreach ($ar_key as $key => $field) {
                $value = array_column($ar_save[$table_index], $key);
                if ($field === 'images') {
                    $this->count_image_field++;
                    $this->ar_update_field[$table_index]["{$field}_$this->count_image_field"] = $value;
                    $this->fields_image[$table_index][] = "{$field}_$this->count_image_field";
                } else {
                    $this->ar_update_field[$table_index][$field] = $value;
                }
            }
        }
    }

    /* Through tables and rows of the table and searches for the product, if it finds, updates */
    public function savePrice(): void
    {
        $time_start = time();
        foreach ($this->ar_update_field as $table_index => $ar_fields) {
            foreach ($ar_fields[$this->search_by[$table_index]] as $key => $code) {
                $search_value = "$this->dx_code-$code";
                if ($this->search_by[$table_index] !== 'productcode') {
                    $search_value = $code;
                }
                $product = ProductModel::objects()->get([$this->search_by[$table_index] => $search_value]);
                if ($product instanceof ProductModel) {
                    $this->sendProductData($product, $table_index, $key);
                    $this->success_productcode[] = "$this->dx_code-$code";
                }
            }
        }
        $this->time_exec = time() - $time_start;
    }

    private function sendProductData(ProductModel $product_model, int $t_index, int $num_row): void
    {
        $ar_field = [];
        foreach ($this->ar_update_field[$t_index] as $field => $item) {
            switch ($field) {
                case 'productcode':
                    $ar_field[$field] = "$this->dx_code-$item[$num_row]";
                    break;
                case 'cost_to_us':
                case 'list_price':
                case 'new_map_price':
                    if (!in_array($field, $this->fields_image[$t_index], true)) {
                        $ar_field[$field] = str_replace(['$', ','], '', $item[$num_row]);
                    }
                    break;
                case 'for_sale':
                    if (empty($this->active_for_sale_value[$t_index])) {
                        $ar_field[$field] = 'N';
                    } else {
                        $ar_field[$field] = $this->active_for_sale_value[$t_index] === $item[$num_row] ? 'Y' : 'N';
                    }
                    break;
                default:
                    if (!in_array($field, $this->fields_image[$t_index], true)) {
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
            $ar_field['source'] = 'manual';
            Xcart::app()->queue->send('products', json_encode($ar_field, JSON_THROW_ON_ERROR));
            $this->count_update++;
        }
    }

    /** Sends image data to RabbitMQ
     * @param ProductModel $productModel
     * @param int $table_index - number table from excel table
     * @param int $num_row
     * @throws JsonException
     */
    private function sendImageData(ProductModel $productModel, int $table_index, int $num_row): void
    {
        $ar_images = [];
        foreach ($this->fields_image[$table_index] as $field) {
            $ar_images[] = $this->ar_update_field[$table_index][$field][$num_row];
        }
        $ar_images_rabbit['images'] = $ar_images;
        $ar_images_rabbit['product_code'] = $productModel->productcode;

        Xcart::app()->queue->send('images_test', json_encode($ar_images_rabbit, JSON_THROW_ON_ERROR));
    }
}