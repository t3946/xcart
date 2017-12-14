<?php

namespace Modules\Goods\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class ProductFileModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_product_files';
    }

    public static function getFields()
    {
        return [
            'fileid' => [
                'class' => AutoField::className()
            ]
        ];
    }
}