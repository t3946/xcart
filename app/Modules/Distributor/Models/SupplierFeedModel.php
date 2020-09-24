<?php

namespace Modules\Distributor\Models;

use DateTime;
use Modules\Goods\Models\CategoryModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

/**
 * @property mixed disable_search_of_discontinued_items
 * @property mixed manufacturerid
 * @property mixed feed_file_name
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
            'add_new_only' => [
                'class' => BooleanCharField::class,
                'default' => false,
                'verboseName' => 'Add new only',
            ],
            'threshold' => [
                'class' => FloatField::class,
                'default' => 0.8,
                'null' => false
            ],
            'feed_source_date' => [
                'class' => DateTimeField::class,
            ],

            'enabled' => [
                'class' => BooleanCharField::class,
            ],
        ];
    }

    public function getAverageUpdatePeriod(): string
    {
        $cur_time = time();
        $date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time));
        $date2 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time + $this->average_update_period));
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
            $this->feed_file_name = strtolower("feed{$this->manufacturerid}{$this->feed_type}.txt");
        }
    }

    public function __toString()
    {
        return (string)$this->feed_name;
    }
}