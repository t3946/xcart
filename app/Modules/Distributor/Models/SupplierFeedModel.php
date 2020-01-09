<?php

namespace Modules\Distributor\Models;

use DateTime;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
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

            'last_feed_fields' => [
                'class' => SerializeField::class,
                'null' => false,
                'default' => ''
            ],

            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
            ],

            'feed_type' => [
                'class' => CharField::class,
                'choices' => [
                    'P' => 'product',
                    'I' => 'inventory',
                ],
                'default' => 'I'
            ]
        ];
    }

    public function getAverageUpdatePeriod()
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
}