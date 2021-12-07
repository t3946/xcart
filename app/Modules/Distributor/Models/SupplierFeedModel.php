<?php

namespace Modules\Distributor\Models;

use DateTime;
use Modules\Goods\Models\CategoryModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

/**
 * @property mixed disable_search_of_discontinued_items
 * @property mixed manufacturerid
 * @property mixed feed_file_name
 * @property DistributorModel distributor
 * @property int last_update_time
 * @property bool $enabled
 * @property int $storefront_id
 * @property bool $run_force
 * @property array $dont_update_fields
 */
class SupplierFeedModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_supplier_feeds';
    }

    public static function getFields()
    {
        return [
            'feed_id' => [
                'class' => AutoField::class,
                'primary' => true,
                'null' => false,
            ],
            'feed_name' => [
                'class' => CharField::class,
                'verboseName' => 'Name'
            ],
            'last_feed_fields' => [
                'class' => SerializeField::class,
                'null' => false,
                'default' => ''
            ],
            'site' => [
                'field' => 'storefront_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['storefront_id' => 'storefrontid'],
                'verboseName' => 'Storefront'
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
            ],
            'base_category' => [
                'field' => 'base_category_id',
                'class' => ForeignField::class,
                'modelClass' => CategoryModel::class,
                'link' => ['base_category_id' => 'categoryid'],
                'null' => true,
                'default' => null,
                'verboseName' => 'Base category',
            ],
            'dont_update_fields' => [
                'class' => JsonField::class,
                'default' => [],
                'null' => true,
                'verboseName' => "Don't update fields",
            ],
            'feed_type' => [
                'class' => CharField::class,
                'choices' => [
                    'P' => 'product',
                    'I' => 'inventory',
                ],
                'default' => 'P',
                'verboseName' => 'Type'
            ],
            'feed_source' => [
                'class' => CharField::class,
                'choices' => [
                    'site' => 'Site',
                    'price' => 'Price list',
                ],
                'default' => 'site'
            ],
            'feed_file_name' => [
                'class' => CharField::class,
                'verboseName' => 'File name',
                'default' => ''
            ],
            'last_update_time' => [
                'class' => UnixTimestampField::class,
                'default' => 0
            ],
            'threshold' => [
                'class' => FloatField::class,
                'default' => 0,
                'null' => false
            ],
            'feed_source_date' => [
                'class' => DateTimeField::class,
            ],
            'process_time' => [
                'class' => IntField::class,
                'default' => 0,
                'verboseName' => 'Process time (sec)',
            ],
            'run_force' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'Run force',
            ],
            'add_new_only' => [
                'class' => BooleanCharField::class,
                'default' => false,
                'verboseName' => 'Add new only',
            ],
            'enabled' => [
                'class' => BooleanCharField::class,
                'default' => false,
            ],
        ];
    }

    public function getAverageUpdatePeriod(): string
    {
        $cur_time = time();
        $date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time));
        $date2 = DateTime::createFromFormat(
            'm-d-Y H:i:s',
            date('m-d-Y H:i:s', $cur_time + $this->average_update_period)
        );
        $interval = $date1->diff($date2);
        $years = (int)$interval->format('%y');
        $months = (int)$interval->format('%m');
        $days = (int)$interval->format('%d');
        $hours = (int)$interval->format('%h');
        $mins = (int)$interval->format('%i');
        return ($years !== 0 ? $years . ' years, ' : '') .
            ($months !== 0 ? $months . ' months, ' : '') .
            ($days !== 0 ? $days . ' days, ' : '') .
            sprintf('%1$02d', $hours) . ':' .
            sprintf('%1$02d', $mins) . ' hours';
    }

    public function beforeSave($owner, $isNew)
    {
        if ($isNew && !$this->feed_file_name) {
            $this->feed_file_name = strtolower("feed{$this->manufacturerid}{$this->feed_type}.json");
        }
    }

    public function isMultiStore(): bool
    {
        return $this->distributor->feed_P_E->count() > 1;
    }

    public function __toString()
    {
        return (string)$this->feed_name;
    }

    public function getCode()
    {
        $dx = $this->distributor;
        $code = str_replace('-', '_', $dx->code);
        return $dx->feeds->count() === 1 ? $code : "{$code}__$this->storefront_id";
    }

    public function getLastUpdateDates(): int
    {
        $last_update_date = (new DateTime())->setTimestamp($this->last_update_time);
        $days =  $last_update_date->diff(new DateTime('now'))->days;
        if ($days === false) {
            return 10000;
        }
        return $days;
    }
}