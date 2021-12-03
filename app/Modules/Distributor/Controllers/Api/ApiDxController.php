<?php


namespace Modules\Distributor\Controllers\Api;


use Exception;
use Modules\Core\Classes\GoogleDrive;
use Modules\Core\Classes\SaveFilePrice;
use Modules\Core\Helpers\CoreHelper;
use Modules\Distributor\Helpers\DistributorHelper;
use Modules\Distributor\Models\ColumnTableSaveModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Sites\Models\SiteModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Helpers\Paths;

class ApiDxController extends Controller
{
    private const COUNT_ITEMS_TABLE = 30;

    /**
     * @throws Exception
     */
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

    public function getFilesList(int $dx): void
    {
        /** @var DistributorModel $dx */
        $dx = DistributorModel::objects()->get(['manufacturerid' => $dx]);
        $google_drive = new GoogleDrive();
        $folder_list = $google_drive->getContentByPath();
        $dx_folder = null;
        foreach ($folder_list as $folder) {
            if ($folder['type'] === 'dir' && $folder['filename'] === $dx->code) {
                $dx_folder = $folder['path'];
                break;
            }
        }
        if (!$dx_folder) {
            $this->jsonResponse(['message' => 'Not found folder in google drive']);
            return;
        }
        $folder_dx_content = $google_drive->getContentByPath($dx_folder);
        $file_list = [];
        foreach ($folder_dx_content as $content) {
            if ($content['type'] === 'file') {
                $file_list[] = ['id' => $content['path'], 'name' => $content['filename'], 'dateCreate' => $content['timestamp']];
            }
        }
        usort($file_list, static function($curr, $prev) {
            if ($curr['id'] === $prev['id']) {
                return 0;
            }
            return ($curr['dateCreate'] > $prev['dateCreate']) ? -1 : 1;
        });
        $this->jsonResponse(['files' => $file_list, 'folderId' => $dx_folder]);
    }

    public function loadFile(): void
    {
        $post = json_decode(file_get_contents('php://input'));
        $file_id = $post->fileId;
        try {
            [$file, $info_file] = DistributorHelper::getResourceGooglePriceFile("$post->folderId/$file_id");
            $file_name = md5($info_file['name']);
            $path_save = Paths::get('runtime') . "/tmp/$file_name.{$info_file['extension']}";
            $file_save = file_put_contents($path_save, $file);
            if (!$file_save) {
                $this->jsonResponse(['message' => 'Failed save file to server with google drive'], 400);
            }

            $reader = IOFactory::createReaderForFile($path_save);
            $excel_obj = $reader->load($path_save);
            $ar_tables = [];
            $table_sheets = [];
            $table = [];

            for ($t = 0; $t < $excel_obj->getSheetCount(); $t++) {
                $worksheet = $excel_obj->getSheet($t);
                $row_iterator = $worksheet->getRowIterator();

                $cells = [];
                foreach ($row_iterator->current()->getCellIterator() as $key => $val) {
                    $cells[] = $key;
                }

                $cells_values = [];
                foreach ($cells as $cell) {
                    for ($i = 0; $i < 100; $i++) {
                        $cell_item = $worksheet->getCell($cell . $i);
                        $cell_value = $cell_item->getDataType() === DataType::TYPE_FORMULA ? $cell_item->getCalculatedValue() : $cell_item->getValue();
                        $cells_values[$cell][] = is_numeric($cell_value) ? round($cell_value, 2) : $cell_value;
                    }
                }

                $cells_values = array_map(static fn(array $cells) => array_filter($cells), $cells_values);
                $cells_values = array_filter($cells_values);
                foreach ($cells_values as $cell_values) {
                    for ($i = 0; $i < 100; $i++) {
                        $table_sheets[$t][$i][] = $cell_values[$i] ?? null;
                    }
                }

                foreach ($table_sheets as $sheet => $table_sheet) {
                    foreach ($table_sheet as $row) {
                        $active_cell = array_filter($row);
                        if (count($active_cell) >= count($row) * 30 / 100) {
                            $table[$sheet][] = $row;
                        }

                        if (isset($table[$sheet]) && count($table[$sheet]) === self::COUNT_ITEMS_TABLE) {
                            break;
                        }
                    }
                }
                if (!empty($table[$t])) {
                    $ar_tables[] = $excel_obj->getSheetNames()[$t];
                }
            }
            $output = [
                'contentFile' => $table,
                'tableNames' => $ar_tables
            ];
            $this->jsonResponse($output);
        } catch (Throwable $exception) {
            $this->jsonResponse(['message' => $exception->getMessage()], 400);
        } finally {
            if (isset($path_save) && file_exists($path_save)) {
                unlink($path_save);
            }
        }
    }

    /* Update products by rows from Excel file */
    public function updateProductsFromPriceList(): void
    {
        $post = json_decode(file_get_contents('php://input'));
        $select = $post->select;
        $search_by = $post->checkField;
        $active_products = $post->valueActive;
        /** @var DistributorModel $dx */
        $dx = DistributorModel::objects()->get(['manufacturerid' => $post->dx]);
        if (!$dx) {
            $this->jsonResponse(['message' => 'Fail found distributor'], 400);
        }
        try {
            [$file, $info_file] = DistributorHelper::getResourceGooglePriceFile($post->pathFile);
            $file_name = md5($info_file['name']);
            $path_save = Paths::get('runtime') . "/tmp/$file_name.{$info_file['extension']}";
            $file_save = file_put_contents($path_save, $file);
            if (!$file_save) {
                $this->jsonResponse(['message' => 'Failed save file to server with google drive'], 400);
            }
            $reader = IOFactory::createReaderForFile($path_save);
            $excel_obj = $reader->load($path_save);
            $ar_save = [];
            for ($i = 0; $i < $excel_obj->getSheetCount(); $i++) {
                foreach ($excel_obj->getSheet($i)->toArray() as $column) {
                    $ar_save[$i][] = $column;
                }
            }
            $ob_save_price = new SaveFilePrice($dx, (array)$search_by);
            $ob_save_price->active_for_sale_value = $active_products;
            $ob_save_price->storefront = $post->storefront;
            $ob_save_price->need_create = $post->create;
            $ob_save_price->collectField((array)$select, $ar_save);
            $ob_save_price->need_send = $post->needSend;
            $ob_save_price->savePrice();
            $ob_save_price->sendStats();
            $this->jsonResponse(['countUpdate' => $ob_save_price->count_update]);
        } catch (Throwable $exception) {
            $this->jsonResponse(['message' => $exception->getMessage()], 400);
        } finally {
            if (isset($path_save) && file_exists($path_save)) {
                unlink($path_save);
            }
            // overwriting column order in Excel table
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
            if (!empty($active_products)) {
                foreach ($active_products as $table_index => $value) {
                    $column = new ColumnTableSaveModel();
                    $column->num_table = $table_index;
                    $column->is_for_sale_value = true;
                    $column->manufacture = $dx;
                    $column->option_name = $value;
                    $column->save();
                }
            }
        }
    }

    /* Get order columns by dx id */
    /**
     * @throws Exception
     */
    public
    function getColumnByDx(int $dx): void
    {
        $dx_model = DistributorModel::objects()->get(['manufacturerid' => $dx]);
        $ar_column = [];
        $ar_for_sale_value = [];
        /** @var ColumnTableSaveModel $column */
        foreach (ColumnTableSaveModel::objects()->filter(['manufacturer_id' => $dx_model->pk]) as $column) {
            if ($column->is_for_sale_value) {
                $ar_for_sale_value[$column->num_table] = $column->option_name;
                continue;
            }
            $ar_column[$column->num_table][$column->num_column] = $column->option_name;
        }
        $ar_site = [['storefrontid' => '', 'domain' => 'Select storefront']];
        foreach (SiteModel::getAllEnabled() as $site) {
            $ar_site[] = [
                'storefrontid' => $site->pk,
                'domain' => $site->domain,
            ];
        }
        $this->jsonResponse(['column' => $ar_column, 'for_sale_value' => $ar_for_sale_value, 'sites' => $ar_site]);
    }

}