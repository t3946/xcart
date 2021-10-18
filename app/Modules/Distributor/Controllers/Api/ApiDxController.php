<?php


namespace Modules\Distributor\Controllers\Api;


use Cron\CronExpression;
use Modules\Core\Classes\GoogleDrive;
use Modules\Core\Classes\SaveFilePrice;
use Modules\Core\Helpers\CoreHelper;
use Modules\Distributor\Models\ColumnTableSaveModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Sites\Models\SiteModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class ApiDxController extends Controller
{
    private const COUNT_ITEMS_TABLE = 30;
    public function getDxInfo($code, $sfId = null): void
    {
        /** @var DistributorModel $dx */
        /** @var SupplierFeedModel $feed */

        if ($dx = DistributorModel::objects()->get(['code' => $code])) {
            $feedData = [];
            if ($sfId !== null) {
                $filter = ['storefront_id' => $sfId];
            }
            foreach ($dx->feeds->filter($filter ?? []) as $feed) {
                $feedData[$feed->storefront_id] = [
                    'type' => ($type = $feed->getField('feed_type')) ? $type->toText() : null,
                    'feed_source' => $feed->feed_source,
                    'feed_file_name' => $feed->feed_file_name,
                    'dont_update_fields' => $feed->dont_update_fields,
                    'md5' => $feed->last_md5,
                    'enabled' => $feed->enabled,
                    'source_date' => $feed->feed_source_date,
                ];
            }

            $this->jsonResponse([
                'id' => $dx->manufacturerid,
                'name' => $dx->manufacturer,
                'prefix' => $dx->code . '-',
                'source' => $dx->url,
                'authenticate' => [
                    'login' => $dx->d_login ? CoreHelper::cipherText($dx->d_login) : '',
                    'password' => $dx->d_password ? CoreHelper::cipherText($dx->d_password) : ''
                ],
                'feeds' => $feedData
            ]);
        }
    }

    public function getDxInfoSfCode($code, $sfCode = null): void
    {
        if ($sfCode && $site = SiteModel::objects()->get(['code' => $sfCode])) {
            $this->getDxInfo($code, $site->storefrontid);
        }
    }


    public function savePriceListFile()
    {
        $post = Xcart::app()->request->post;
        $dx = DistributorModel::objects()->get(['manufacturerid' => $post['dx']]);
        $output = ['status' => false];
        try {

            $google_drive = new GoogleDrive();
            $code = strtoupper($dx->code);
            $ar_file = $google_drive->uploadFile($code, $_FILES['d_price_list']); // load file to google drive folder

            if (!empty($ar_file)) {
                $link = $google_drive->getLink($ar_file['dirname']);
                $dx->d_price_list = $link;
                if ($dx->save()) {
                    $reader = IOFactory::createReaderForFile($_FILES['d_price_list']['tmp_name']);
                    $excel_obj = $reader->load($_FILES['d_price_list']['tmp_name']);
                    $ar_data = [];
                    $ar_tables = [];
                    $counter_table = 0;
                    /* looking from excel file first 30 rows which not empty */
                    for ($t = 0; $t < $excel_obj->getSheetCount(); $t++) {
                        $ar_excel = $excel_obj->getSheet($t)->toArray();
                        for ($i = 0; $i < count($ar_excel); $i++) {
                            $count_good = 0; // count not empty field
                            $row = $ar_excel[$i];
                            for ($d = 0; $d < count($row); $d++) {
                                if (!empty($row[$d]) && !is_null($row[$d])) {
                                    $count_good++;
                                }
                            }
                            if ($count_good > (count($row) / 100) * 30) { // if count not empty column in row > 30%
                                $ar_data[$counter_table][] = $row;
                            }
                            if (!empty($ar_data[$counter_table]) && count($ar_data[$counter_table]) === self::COUNT_ITEMS_TABLE) {
                                break;
                            }
                        }
                        if (!empty($ar_data[$counter_table])) {
                            $counter_table++;
                            $ar_tables[] = $excel_obj->getSheetNames()[$t];
                        }
                    }
                    $output = [
                        'status' => true,
                        'data' => $ar_data,
                        'tableNames' => $ar_tables,
                    ];
                }
            }
        } catch (\Throwable $exception) {
            $output['error'] = $exception->getMessage();
        } finally {
            $this->jsonResponse($output);
        }
    }

    /* Update products by rows from excel file */
    public function updateProductsFromPriceList()
    {
        set_time_limit(0);
        $post = Xcart::app()->request->post;
        $select = json_decode($post->select, true);
        $file = $_FILES['file'];
        $result = ['status' => false, 'countUpdate' => 0];
        $search_by = json_decode($post->checkField, true);
        $active_products = json_decode($post->active_value, true);
        $dx = DistributorModel::objects()->get(['manufacturerid' => $post->dx]);

        $reader = IOFactory::createReaderForFile($file['tmp_name']);
        $excel_obj = $reader->load($file['tmp_name']);
        $ar_save = [];
        for ($i = 0; $i < $excel_obj->getSheetCount(); $i++) {
            foreach ($excel_obj->getSheet($i)->toArray() as $column) {
                $ar_save[$i][] = $column;
            }
        }
        if ($dx instanceof DistributorModel) {
            $ob_save_price = new SaveFilePrice($dx, $search_by);
            try {
                $ob_save_price->active_for_sale_value = $active_products;
                $ob_save_price->collectField($select, $ar_save);
                $ob_save_price->savePrice();
                $ob_save_price->sendStats();
                $result['status'] = true;
                $result['countUpdate'] = $ob_save_price->count_update;
            } catch (\Throwable $exception) {
                $result['error'] = $exception->getMessage();
            } finally {
                // overwriting column order in excel table
                $id_list = ColumnTableSaveModel::objects()->filter(['manufacturer_id' => $dx->pk])->valuesList(['id'], true);
                if ($id_list) {
                    ColumnTableSaveModel::objects()->delete(['id__in' => $id_list]);
                }
                foreach ($select as $table_index => $fields) {
                    foreach ($fields as $key => $field) {
                        $column = new ColumnTableSaveModel();
                        $column->num_column = $key;
                        $column->option_name = $field;
                        $column->manufacture = $dx;
                        $column->num_table = $table_index;
                        $column->save();
                    }
                }
                $this->jsonResponse($result);
            }
        }
    }

    /* Get order columns by manufacturerid */
    public function getColumnByDx(int $dx)
    {
        $dx_model = DistributorModel::objects()->get(['manufacturerid' => $dx]);
        $ar_column = [];
        /** @var ColumnTableSaveModel $column */
        foreach (ColumnTableSaveModel::objects()->filter(['manufacturer_id' => $dx_model->pk]) as $column) {
            $ar_column[$column->num_table][$column->num_column] = $column->option_name;
        }
        $this->jsonResponse($ar_column);
    }

}