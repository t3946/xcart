<?php

namespace Modules\Distributor\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property int manufacturer_id
 * @property DistributorModel manufacture
 * @property int num_table
 * @property int num_column
 * @property string option_name
 * @property bool is_for_sale_value
 */
class ColumnTableSaveModel extends Model
{
    public static function tableName()
    {
        return 'xcart_column_table_save';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class
            ],
            'num_column' => [
                'class' => IntField::class,
                'default' => 0,
                'null' => false
            ],
            'option_name' => [
                'class' => CharField::class
            ],
            'manufacture' => [
                'field' => 'manufacturer_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturer_id' => 'manufacturerid'],
                'null' => false,
            ],
            'num_table' => [
                'class' => IntField::class
            ],
            'is_for_sale_value' => [
                'class' => BooleanField::class,
                'null' => true,
                'default' => 0,
            ],
        ];
    }
}