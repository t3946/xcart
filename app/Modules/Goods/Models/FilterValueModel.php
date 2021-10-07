<?php
namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class FilterValueModel extends Model
{
    public static function tableName()
    {
        return 'xcart_cidev_filter_values';
    }

    public static function getFields()
    {
        return [
            'fv_id' => [
                'class' => AutoField::class,
                'primary' => true,
                'null' => false,
            ],
            'filter' => [
                'field' => 'f_id',
                'class' => ForeignField::class,
                'modelClass' => FilterModel::class,
                'link' => ['f_id' => 'f_id'],
                'null' => false,
                'default' => 0
            ],
            'fv_name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'Value'
            ],
            'fv_order_by' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 10
            ],
            'fv_active' => [
                'class' => CharField::class,
                'null' => false,
                'default' => 'Y'
            ],
        ];
    }
}