<?php

namespace Modules\Sites\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property int id
 * @property int site_id
 * @property SiteModel site
 * @property int address_id
 * @property AddressModel address
 */
class SitesAddressesModel extends Model
{
    public static function tableName(): string
    {
        return 'xcart_sites_addresses';
    }

    public static function getFields(): array
    {
        return [
            'site_address_id' => AutoField::class,
            'site' => [
                'field' => 'site_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => [
                    'site_id' => 'storefrontid'
                ],
            ],
            'address' => [
                'field' => 'address_id',
                'class' => ForeignField::class,
                'modelClass' => AddressModel::class,
                'link' => [
                    'address_id' => 'address_id'
                ]
            ]
        ];
    }
}