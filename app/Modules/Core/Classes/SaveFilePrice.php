<?php


namespace Modules\Core\Classes;


use Exception;
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
    public DistributorModel $dx_model;
    private array $success_productcode = [];
    private array $fields_image = [];
    public array $active_for_sale_value;
    public array $search_by = [];
    public bool $need_send = false;
    public ?string $storefront;
    public bool $need_create = false;

    public function __construct(DistributorModel $dx_model, array $search_by)
    {
        $this->dx_model = $dx_model;
        $this->search_by = $search_by;
    }

    /** Send data about full query save price to RabbitMQ
     * @throws JsonException
     */
    public function sendStats(): void
    {
        if (filter_var($this->need_send, FILTER_VALIDATE_BOOLEAN)) {
            $ar_active = [
                'active_sku' => $this->success_productcode,
                'dx_code' => $this->dx_model->code,
                'process_time' => $this->time_exec,
                'feed_source' => 'manual',
                'products_in_feed' => $this->count_update,
                'feed_source_date' => date('Y-m-d H:i:s'),
            ];
            Xcart::app()->queue->send('products_active', json_encode($ar_active, JSON_THROW_ON_ERROR));
        }
    }

    /** Collect data
     * @param array $select
     * @param $ar_save
     * @throws Exception
     */
    public function collectField(array $select, $ar_save): void
    {
        if (empty($select)) {
            throw new Exception('Please selected fields for update ');
        }
        foreach ($select as $table_index => $ar_key) {
            if (empty($ar_key)) {
                throw new Exception('Please selected fields for update ');
            }
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
    /**
     * @throws JsonException
     * @throws Exception
     */
    public function savePrice(): void
    {
        $time_start = time();
        foreach ($this->ar_update_field as $table_index => $ar_fields) {
            foreach ($ar_fields[$this->search_by[$table_index]] as $key => $code) {
                if (!$code) {
                    continue;
                }
                $search_value = "{$this->dx_model->code}-$code";
                if ($this->search_by[$table_index] !== 'productcode') {
                    $search_value = $code;
                }
                /** @var ProductModel $product */
                $product = ProductModel::objects()->get([$this->search_by[$table_index] => $search_value]);
                if ($product) {
                    $fields_send = $this->collectProductData($product, $table_index, $key);
                } else if ($cost_to_us = $this->ar_update_field[$table_index]['cost_to_us'][$key]) {
                    $cost_to_us = str_replace(['$', ','], '', $cost_to_us);
                    if (!empty($search_value)
                        && is_numeric($cost_to_us)
                        && (float)$cost_to_us > 0
                        && filter_var($this->need_send, FILTER_VALIDATE_BOOLEAN)
                        && $this->need_create
                    ) {
                        $fields_send = $this->collectProductData(null, $table_index, $key);
                    }
                }
                if ($fields_send) {
                    $fields_send['manufacturerid'] = $this->dx_model->pk;
                    $this->sendProduct($fields_send);
                    $this->count_update++;
                    $this->success_productcode[] = $search_value;
                }
            }
        }
        $this->time_exec = time() - $time_start;
    }

    /**
     * @throws JsonException
     */
    private function sendProduct(array $ar_field): void
    {
        Xcart::app()->queue->send('products', json_encode($ar_field, JSON_THROW_ON_ERROR));
    }

    /**
     * @param ProductModel|null $product_model
     * @param int $t_index
     * @param int $num_row
     * @return array|null
     * @throws JsonException
     */
    private function collectProductData(?ProductModel $product_model, int $t_index, int $num_row): ?array
    {
        $ar_field = [];
        foreach ($this->ar_update_field[$t_index] as $field => $item) {
            switch ($field) {
                case 'productcode':
                    $ar_field[$field] = "{$this->dx_model->code}-$item[$num_row]";
                    break;
                case 'cost_to_us':
                case 'list_price':
                case 'new_map_price':
                    if ($item[$num_row] && preg_match("/(?<price>(\d+\.\d+)|(\d+)|(\.\d+))/", $item[$num_row], $matches)) {
                        $ar_field[$field] = (float)$matches['price'];
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
                    $result_field = (string)preg_replace('/^[\s]*(?U)(.*)[\s]*$/s', '$1', $item[$num_row]);
                    $ar_field[$field] = $result_field;
                    break;
            }
        }
        if (!empty($this->fields_image)) {
            $this->sendImageData($ar_field['productcode'], $t_index, $num_row);
        }
        if (!empty($ar_field)) {
            $ar_field['hash_product'] = md5(json_encode($ar_field, JSON_THROW_ON_ERROR));
            $ar_field['source'] = 'manual';
            if ($this->storefront) {
                $ar_field['storefront'] = $this->storefront;
            }
            return $ar_field;
        }
        return null;
    }

    /** Sends image data to RabbitMQ
     * @param $product_model
     * @param int $table_index - number table from excel table
     * @param int $num_row
     * @throws JsonException
     */
    private function sendImageData($product_model, int $table_index, int $num_row): void
    {
        $ar_images = [];
        foreach ($this->fields_image[$table_index] as $field) {
            $ar_images[] = $this->ar_update_field[$table_index][$field][$num_row];
        }
        $ar_images_rabbit['images'] = $ar_images;
        $ar_images_rabbit['product_code'] = $product_model instanceof ProductModel ? $product_model->productcode : $product_model;

        Xcart::app()->queue->send('images_test', json_encode($ar_images_rabbit, JSON_THROW_ON_ERROR));
    }
}