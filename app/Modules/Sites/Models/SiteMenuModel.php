<?php
namespace Modules\Sites\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\TreeModel;

/**
 * @property int $menu_id
 * @property string $name
 * @property int $pos
 * @property string url
 */
class SiteMenuModel extends TreeModel
{
    public static function tableName()
    {
        return 'xcart_site_menu';
    }

    public static function getFields()
    {
        return  array_merge([
            'menu_id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Name item'
            ],
            'pos' => [
                'class' => IntField::class,
                'default' => 100000,
            ],
            'url' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ]
        ], parent::getFields());
    }

    public function __toString(): string
    {
        return $this->pk ? $this->name : 'Site menu';
    }
}