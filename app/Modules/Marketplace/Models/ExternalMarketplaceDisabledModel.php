<?php


namespace Modules\Marketplace\Models;


use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ExternalMarketplaceDisabledModel extends Model
{
    public function getTable()
    {
        return 'xcart_products_disabled_marketplaces';
    }

    public static function getFields()
    {
        return [
            'marketplace_id' => [
                'class' => IntField::class,
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
            'update_date' => DateTimeField::class
        ];
    }
}