<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignCharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class SiteMarketplaceModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_storefronts_external_marketplaces';
    }

    public static function getFields()
    {
        return [
            'marketplace_id' => [
                'class' => IntField::class,
                'primary' => true,
            ],
            'storefront' => [
                'field' => 'storefront_id',
                'class' => ForeignCharField::class,
                'modelClass' => SiteModel::class,
                'link' => ['storefront_id' => 'storefrontid'],
                'primary' => true,
            ],
        ];
    }
}