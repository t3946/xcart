<?php
namespace Modules\Sites\Models;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property int site_menu_id
 * @property SiteModel site
 * @property SiteMenuModel menu
 */
class SitesMenuModel extends Model
{
    public static function tableName(): string
    {
        return 'xcart_sites_menu';
    }

    public static function getFields(): array
    {
        return [
            'site_menu_id' => AutoField::class,
            'site' => [
                'field' => 'site_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => [
                    'site_id' => 'storefrontid'
                ],
            ],
            'menu' => [
                'field' => 'menu_id',
                'class' => ForeignField::class,
                'modelClass' => SiteMenuModel::class,
                'link' => [
                    'menu_id' => 'menu_id'
                ]
            ]
        ];
    }
}