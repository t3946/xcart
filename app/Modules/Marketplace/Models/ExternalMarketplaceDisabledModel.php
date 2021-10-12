<?php


namespace Modules\Marketplace\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ExternalMarketplaceDisabledModel extends Model
{
    public static function tableName()
    {
        return 'xcart_products_disabled_marketplaces';
    }

    public static function getFields()
    {
        return [
            'marketplace' => [
                'field' => 'marketplace_id',
                'class' => ForeignField::class,
                'modelClass' => ExternalMarketPlaceModel::class,
                'link' => ['marketplace_id' => 'id'],
                'primary' => true,
            ],
            'resource_id' => [
                'class' => IntField::class,
                'primary' => true,
            ],
            'resource_type' => [
                'class' => CharField::class,
                'primary' => true,
            ],
            'update_date' => [
                'class' => DateTimeField::class,
                'autoNow' => true
            ]
        ];
    }
}