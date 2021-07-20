<?php


namespace Modules\Goods\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Model;

class GroupIndexModel extends Model
{
    public static function tableName()
    {
        return 'xcart_products_group';
    }

    public static function getFields()
    {
        return [
            'group_index_id' => AutoField::class,
            'given' => [
                'class' => BooleanField::class,
                'default' => true,
            ]
        ];
    }
}