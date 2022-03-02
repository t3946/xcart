<?php


namespace Modules\Distributor\Controllers\Api;


use Exception;
use JsonException;
use Modules\Core\Classes\GoogleDrive;
use Modules\Core\Classes\SaveFilePrice;
use Modules\Core\Helpers\CoreHelper;
use Modules\Distributor\Helpers\DistributorHelper;
use Modules\Distributor\Models\ColumnTableSaveModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\DistributorUploadPriceModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Sites\Models\SiteModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;

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
                'feeds' => $feedData,
                'rules' => $dx->feed_info ?? []
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
        /** @var DistributorUploadPriceModel $upload_model */
        foreach ($dx->upload_prices as $upload_model) {
            $ar_logs[] = [
                'uploadId' => $upload_model->upload_id,
                'date' => $upload_model->date,
                'userUpload' => (string)$upload_model->user,
                'count' => $upload_model->count_rows,
                'status' => $upload_model->status,
                'name' => $upload_model->file_name
            ];
        }
        $this->jsonResponse([
            'files' => $file_list,
            'folderId' => $dx_folder,
            'logs' => $ar_logs ?? []
        ]);
    }

    /**
     * @throws JsonException
     */
    public function loadFile(): void
    {
        ini_set('memory_limit', '-1');
        set_time_limit ( 0 );

        $post = json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
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
                        if ($cell_value instanceof RichText) {
                            $cell_value = $cell_value->getPlainText();
                        }
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
    /**
     * @throws JsonException
     */
    public function updateProductsFromPriceList(): void
    {
        $post = json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
        try {
            [$file, $info_file] = DistributorHelper::getResourceGooglePriceFile($post->pathFile);
            $upload_model = new DistributorUploadPriceModel();
            $upload_model->user = Xcart::app()->user;
            $upload_model->manufacturer_id = $post->dx;
            $upload_model->date = time();
            $upload_model->file_path = $post->pathFile;
            $upload_model->file_name = $info_file['name'] ?? 'None';
            if ($upload_model->save()) {
                Xcart::app()->queue->send('dx_prices', json_encode(array_merge((array)$post, ['upload_id' => $upload_model->pk]), JSON_THROW_ON_ERROR));
            }
            $this->jsonResponse(['status' => true]);
        } catch (Throwable $e) {
            $this->jsonResponse(['message' => $e->getMessage()]);
        }
    }

    /* Get order columns by dx id */
    /**
     * @throws Exception
     */
    public function getColumnByDx(int $dx): void
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

    public function saveFeedInfo(): void
    {
        $post = json_decode(file_get_contents('php://input'), true, 512);

        if (!$post) {
            $this->jsonResponse(['status' => false], 400);
        }

        $dx = DistributorModel::objects()->get(['code' => $post['dx_code']]);

        if (!$dx) {
            $this->jsonResponse(['status' => false, 'message' => 'Dx not found'], 404);
            return;
        }

        $dx->feed_info = $post;

        if ($dx->save()) {
            $this->jsonResponse(['status' => true]);
            return;
        }

        $this->jsonResponse(['status' => false]);
    }

    public function getFeedInfo(string $dx): void
    {
        /** @var DistributorModel $dx */

        $dx = DistributorModel::objects()->get(['code' => $dx]);

        if (!$dx) {
            $this->jsonResponse(['status' => false, 'message' => 'Dx not found'], 404);
            return;
        }

        $this->jsonResponse(['status' => true, 'data' => $dx->feed_info]);
    }

}