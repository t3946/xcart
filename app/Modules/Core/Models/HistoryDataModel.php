<?php

namespace Modules\Core\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class HistoryDataModel extends Model
{
    const RESOURCE_TYPE_PRODUCT = 'product';
    const RESOURCE_TYPE_CATEGORY = 'category';
    const RESOURCE_TYPE_BRAND = 'brand';

    const FIELD_NAME_COST_TO_US = 'cost_to_us';
    const FIELD_NAME_BRAND_ID = 'brandid';

    public static function tableName()
    {
        return 'xcart_history_data';
    }

    public static function getFields()
    {
        return [
            'resourceid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false
            ],
            'resource_type' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false
            ],
            'changedate' => [
                'class' => DateTimeField::className(),
                'primary' => true,
                'autoNowAdd' => true,
                'null' => false
            ],
            'fieldname' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false
            ],
            'value' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false
            ],
        ];
    }
}