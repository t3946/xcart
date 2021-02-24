<?php


namespace Modules\Dashboard\Models;


use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class DashboardFilterStatisticModel extends Model
{
    public static function tableName()
    {
        return 'xcart_dashboard_filters_statistic';
    }

    public static function getFields()
    {
        return [
            'filter' => [
                'field' => 'filter_id',
                'class' => ForeignField::class,
                'modelClass' => DashboardFilter::class,
                'link' => ['filter_id' => 'id'],
                'primary' => true
            ],
            'date' => [
                'class' => DateField::class,
                'autoNowAdd' => true,
                'primary' => true
            ],
            'hour' => [
                'class' => IntField::class,
                'null' => false,
                'primary' => true
            ],
            'count' => [
                'class' => IntField::class,
                'null' => false,
            ],
        ];
    }
}