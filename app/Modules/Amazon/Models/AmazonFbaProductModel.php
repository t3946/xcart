<?php

namespace Modules\Amazon\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class AmazonFbaProductModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_cidev_amazon_fba_products';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ]
        ];
    }
}