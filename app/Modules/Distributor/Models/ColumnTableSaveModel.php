<?php
namespace Modules\Distributor\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

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
                'class' => IntField::class
            ],
            'option_name' => [
                'class' => CharField::class
            ],
            'manufacture' => [
                'field' => 'manufactureid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
            ],
            'num_table' => [
                'class' => IntField::class
            ]
        ];
    }
}