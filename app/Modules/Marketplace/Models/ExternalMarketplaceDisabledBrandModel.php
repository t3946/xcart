<?php
namespace Modules\Marketplace\Models;

use Modules\Brand\Models\BrandModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ExternalMarketplaceDisabledBrandModel extends Model
{
    public static function tableName()
    {
        return 'xcart_products_disabled_marketplaces';
    }

    public static function getFields()
    {
        return [
            'marketplace' => [
                'class' => ForeignField::class,
                'modelClass' => ExternalMarketPlaceModel::class,
                'link' => ['marketplace_id' => 'id'],
                'primary' => true,
            ],
            'resource' => [
                'class' => ForeignField::class,
                'modelClass' => BrandModel::class,
                'link' => ['resource_id' => 'brandid'],
                'primary' => true,
            ],
            'resource_type' => [
                'class' => CharField::class,
                'primary' => true,
                'default' => 'B',
            ],
            'update_date' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ]
        ];
    }
}