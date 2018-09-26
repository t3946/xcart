<?php

namespace Modules\Amazon\Models;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class AmazonReorderBatchDataModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'amazon_reorder_batch_data';
    }

    public static  function getFields()
    {
        return [
            'batch_id' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'productid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
            ]
        ];
    }
}