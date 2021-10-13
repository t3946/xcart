<?php

namespace Modules\Core\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class HistoryDataModel extends Model
{
    public const RESOURCE_TYPE_PRODUCT = 'product';
    public const RESOURCE_TYPE_CATEGORY = 'category';
    public const RESOURCE_TYPE_BRAND = 'brand';

    public const FIELD_NAME_COST_TO_US = 'cost_to_us';
    public const FIELD_NAME_BRAND_ID = 'brandid';

    public static function tableName()
    {
        return 'xcart_history_data';
    }

    public static function getFields()
    {
        return [
            'resourceid' => [
                'class' => IntField::class,
                'primary' => true,
                'null' => false
            ],
            'resource_type' => [
                'class' => CharField::class,
                'primary' => true,
                'null' => false
            ],
            'changedate' => [
                'class' => DateTimeField::class,
                'primary' => true,
                'autoNowAdd' => true,
                'null' => false
            ],
            'fieldname' => [
                'class' => CharField::class,
                'primary' => true,
                'null' => false
            ],
            'value' => [
                'class' => CharField::class,
                'primary' => true,
                'null' => false
            ],
        ];
    }
}