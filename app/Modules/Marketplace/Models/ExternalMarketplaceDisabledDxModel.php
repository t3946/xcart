<?php


namespace Modules\Marketplace\Models;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ExternalMarketplaceDisabledDxModel extends Model
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
                'modelClass' => DistributorModel::class,
                'link' => ['resource_id' => 'manufacturerid'],
                'primary' => true,
            ],
            'resource_type' => [
                'class' => CharField::class,
                'primary' => true,
                'default' => 'D',
            ],
            'update_date' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ]
        ];
    }
}