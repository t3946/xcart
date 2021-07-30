<?php


namespace Modules\Distributor\Controllers\Api;


use Cron\CronExpression;
use DateInterval;
use DateTime;
use Modules\Core\Classes\GoogleDrive;
use Modules\Core\Classes\SaveFilePrice;
use Modules\Core\Helpers\CoreHelper;
use Modules\Distributor\Helpers\SchedulerHelper;
use Modules\Distributor\Models\ColumnTableSaveModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Base;

class ApiDxController extends Controller
{
    private const FEEDS_START_TIME = '00:00';
    private const COUNT_ITEMS_TABLE = 30;
    private const TIME_FRAME_SEC = 32400;

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

    public function schedule(): void
    {
        $nextRunning = [];

        foreach (SupplierFeedModel::objects()->filter(['schedule__isnull' => false, 'enabled' => 'Y']) as $feed) {
            $schedule = trim($feed->run_force ? "* * * * *" : $feed->schedule);
            if (CronExpression::isValidExpression($schedule) &&
                CronExpression::factory($schedule)->isDue()) {
                $nextRunning[] = $feed;
                $feed->run_force = false;
                $feed->save();
            }
        }
        if ($nextRunning) {
            $nextRunning = array_map(static function ($feed) {
                $dx = $feed->distributor;
                $code = str_replace('-', '_', $dx->code);
                return $dx->feeds->count() === 1 ? $code : "{$code}__{$feed->storefront_id}";
            }, $nextRunning);
            $this->jsonResponse($nextRunning);
        }
    }

    private static function getCode($feed)
    {
        $dx = $feed->distributor;
        $code = str_replace('-', '_', $dx->code);
        return $dx->feeds->count() === 1 ? $code : "{$code}__{$feed->storefront_id}";
    }

    public function scheduleDynamic(): void
    {
        [$h, $m] = explode(':', self::FEEDS_START_TIME);
        $now = new DateTime();
        $start = (int)$now->format('H') < (int)$h ? new DateTime('yesterday') : new DateTime();
        $start->setTime($h, $m);
        if ($now->getTimestamp() >= $start->getTimestamp()) {
            $offset = (int)(($now->getTimestamp() - $start->getTimestamp()) / 60);
            if ($offset >= 0) {
                $feeds = SupplierFeedModel::objects()->filter(['enabled' => 'Y'])
                    ->order(['-process_time'])
                    ->cache($start->add(new DateInterval('P1D'))->getTimestamp() - $now->getTimestamp())
                    ->all();

                $times = array_map(static fn($f) => (int)$f->process_time, $feeds);

                $runForces = SupplierFeedModel::objects()->filter(['enabled' => 'Y', 'run_force' => true])->all();

                $schedule = SchedulerHelper::algorithm(self::TIME_FRAME_SEC, $times);
                $schedule = array_map(static fn($sh) => (int)($sh / 60), $schedule);

                $idsToLaunch = array_keys(array_filter($schedule, static fn($o) => $o === $offset));
                $nextRunning = array_map(static fn($id) => self::getCode($feeds[$id]), $idsToLaunch);
                $nextRunning = array_merge($nextRunning, array_map(static function ($feed) {
                    $feed->run_force = false;
                    $feed->save();
                    return self::getCode($feed);
                }, $runForces));
                $this->jsonResponse($nextRunning);
            }
        }
    }

    public function scheduleDynamic2(): void
    {
        $feeds = SupplierFeedModel::objects()->filter(['enabled' => 'Y'])->order(['-process_time'])->all();
        $times = array_map(static fn($f) => (int)$f->process_time, $feeds);
        $runForces = array_filter($feeds, static fn($f) => $f->run_force === true);

        $schedule = SchedulerHelper::algorithm(self::TIME_FRAME_SEC, $times);
        $schedule = array_map(static fn($sh) => (int)($sh / 60), $schedule);

        [$h, $m] = explode(':', self::FEEDS_START_TIME);

        $now = new DateTime();
        $start = (int)$now->format('H') < (int)$h ? new DateTime('yesterday') : new DateTime();
        $start->setTime($h, $m);
        $offset = (int)(($now->getTimestamp() - $start->getTimestamp()) / 60);
        if ($offset >= 0) {
            $idsToLaunch = array_keys(array_filter($schedule, static fn($o) => $o === $offset));
            $nextRunning = array_map(static fn($id) => self::getCode($feeds[$id]), $idsToLaunch);
            $nextRunning = array_merge($nextRunning, array_map(static function ($feed) {
                $feed->run_force = false;
                $feed->save();
                return self::getCode($feed);
            }, $runForces));
            $this->jsonResponse($nextRunning);
        }

    }

    public function savePrice()
    {
        $post = Xcart::app()->request->post;
        $dx = DistributorModel::objects()->get(['manufacturerid' => $post['dx']]);
        $output = ['status' => false];
        try {
            $google_drive = new GoogleDrive('Test', '1m0heCJuDhMuBlzfY-vKWKIi58Xa98U2r');
            $code = strtoupper($dx->code);
            $ar_file = $google_drive->uploadFile($code, $_FILES['d_price_list']);

            if (!empty($ar_file)) {
                $link = $google_drive->getLink($ar_file['dirname']);
                $dx->d_price_list = $link;
                if ($dx->save()) {
                    $reader = IOFactory::createReaderForFile($_FILES['d_price_list']['tmp_name']);
                    $excel_obj = $reader->load($_FILES['d_price_list']['tmp_name']);
                    $ar_data = [];
                    $ar_tables = [];
                    for ($t = 0; $t < $excel_obj->getSheetCount(); $t++) {
                        $ar_excel = $excel_obj->getSheet($t)->toArray();
                        for ($i = 0; $i < count($ar_excel); $i++) {
                            $count_good = 0;
                            $row = $ar_excel[$i];
                            for ($d = 0; $d < count($row); $d++) {
                                if (!empty($row[$d]) && !is_null($row[$d])) {
                                    $count_good++;
                                }
                            }
                            if ($count_good > (count($row) / 100) * 30) {
                                $ar_data[$t][] = $row;
                            }
                            if (count($ar_data[$t]) === self::COUNT_ITEMS_TABLE) {
                                break;
                            }
                        }
                        if (!empty($ar_data[$t])) {
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
        } catch (\Exception $exception) {
            $output['error'] = $exception->getMessage();
        } finally {
            $this->jsonResponse($output);
        }
    }

    public function saveProductsPrice()
    {
        set_time_limit(0);
        $post = Xcart::app()->request->post;
        $select = json_decode($post->select, true);
        $file = $_FILES['file'];
        $result = ['status' => false, 'countUpdate' => 0];
        $search_by = json_decode($post->checkField, true);
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
                $ob_save_price->collectField($select, $ar_save);
                $ob_save_price->savePrice();
                $ob_save_price->sendStats();
                $result['status'] = true;
                $result['countUpdate'] = $ob_save_price->count_update;
            } catch (\Exception $exception) {
                $result['error'] = $exception->getMessage();
            } finally {
                $id_list = ColumnTableSaveModel::objects()->filter(['manufactureid' => $dx->pk])->valuesList(['id'], true);
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

    public function getColumnByDx(int $dx)
    {
        $dx_model = DistributorModel::objects()->get(['manufacturerid' => $dx]);
        $ar_column = [];
        foreach (ColumnTableSaveModel::objects()->filter(['manufactureid' => $dx_model->pk]) as $column) {
            $ar_column[$column->num_table][$column->num_column] = $column->option_name;
        }
        $this->jsonResponse($ar_column);
    }
}