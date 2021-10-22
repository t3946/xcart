<?php

namespace Modules\Sites\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property int id
 * @property int site_id
 * @property SiteModel site
 * @property int address_id
 * @property AddressModel address
 */
class SiteSocialsModel extends Model
{
    public static function tableName(): string
    {
        return 'xcart_site_socials';
    }

    public static function getFields(): array
    {
        return [
            'site_social_id' => AutoField::class,
            'site' => [
                'field' => 'site_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['site_id' => 'storefrontid'],
            ],
            'social' => [
                'field' => 'social_id',
                'class' => ForeignField::class,
                'modelClass' => SocialModel::class,
                'link' => [
                    'social_id' => 'social_id'
                ]
            ],
            'is_active' => [
                'class' => BooleanField::class,
                'default' => true,
            ],
            'order_by' => [
                'class' => IntField::class,
                'default' => 100000
            ],
        ];
    }
}