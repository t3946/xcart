<?php
namespace Modules\Help\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Storage\FileNameHasher\MD5FileContentHasher;

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
                'class' => ImageField::class,
                'adapterName' => 's3',
                'uploadTo' => "help-menu/icons/%Y%m",
                'nameHasher' => MD5FileContentHasher::class,
                'null' => false,
                'default' => ''
            ],
            'active_icon' => [
                'class' => ImageField::class,
                'adapterName' => 's3',
                'uploadTo' => "help-menu/active-icons/%Y%m",
                'nameHasher' => MD5FileContentHasher::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'Active icon'
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
                'verboseName' => 'Menu items'
            ]
        ];
    }
    public function __toString()
    {
        return (string)($this->pk ? $this->title : 'Help list item');
    }
}