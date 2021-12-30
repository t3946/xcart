<?php

namespace Modules\Distributor\Commands;

use Exception;
use JsonException;
use League\Flysystem\FileNotFoundException;
use Modules\Core\Classes\SaveFilePrice;
use Modules\Distributor\Helpers\DistributorHelper;
use Modules\Distributor\Models\ColumnTableSaveModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\DistributorUploadPriceModel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use RuntimeException;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;

class UploadFileCommand extends Command
{

    public function handle($arguments = []): void
    {
        Xcart::app()->queue->setCount(1)->consume('dx_prices', [$this, 'consume']);
    }

    /**
     * @param AMQPMessage $message
     * @throws JsonException
     */
    public function consume(AMQPMessage $message): void
    {
        if ($message->body && $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            /** @var DistributorUploadPriceModel $upload_model */
            $upload_model = DistributorUploadPriceModel::objects()->get(['pk' => $data['upload_id']]);
            if (!$upload_model) {
                $message->ack();
                return;
            }
            try {
                /** @var DistributorModel $dx */
                $dx = DistributorModel::objects()->get(['pk' => $upload_model->manufacturer_id]);
                if (!$dx) {
                    throw new Exception("Not found dx by pk {$data['dx']}");
                }

                [$file, $info_file] = DistributorHelper::getResourceGooglePriceFile($data['pathFile']);
                $file_name = md5($info_file['name']);
                $path_save = Paths::get('runtime') . "/tmp/$file_name.{$info_file['extension']}";
                if (!file_put_contents($path_save, $file)) {
                    throw new RuntimeException('Failed save file');
                }

                $select = $data['select'];
                $search_by = $data['checkField'];
                $active_products = $data['valueActive'];
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
                $ob_save_price->storefront = $data['storefront'];
                $ob_save_price->need_create = $data['create'];
                $ob_save_price->collectField((array)$select, $ar_save);
                $ob_save_price->need_send = $data['needSend'];

                $ob_save_price->savePrice();
                $ob_save_price->sendStats();

                $upload_model->count_rows = $ob_save_price->count_send;
                $upload_model->status = DistributorUploadPriceModel::UPLOAD_STATUS_SUCCESS;
            } catch (Throwable $e) {
                $upload_model->status = DistributorUploadPriceModel::UPLOAD_STATUS_ERROR;
                Xcart::app()->logger->error('error consume dx price', [$e->getMessage(), $e->getFile(), $e->getLine()], 'upload dx file');
            } finally {
                if (isset($path_save) && file_exists($path_save)) {
                    unlink($path_save);
                }
                if ($dx) {
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
                $upload_model->save();
            }
        }
        $message->ack();

    }
}