<?php
namespace Modules\Help\Models;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\Fields\HasManyField;

class HelpListModel extends Model
{
    public static function tableName()
    {
        return 'xcart_help_menu_items';
    }

    public static function getFields()
    {
        return [
            'menu_id' => [
                'class' => AutoField::class,
            ],
            'icon' => [
                'class' => CharField::class,
            ],
            'active_icon' => [
                'class' => CharField::class,
            ],
            'title' => [
                'class' => CharField::class,
            ],
            'order_by' => [
                'class' => IntField::class,
                'default' => 0
            ],
            'menu_items' => [
                'class' => HasManyField::class,
                'modelClass' => HelpMenuContentModel::class,
                'link' => ['menu_id' => 'menu_id'],
            ]
        ];
    }
}