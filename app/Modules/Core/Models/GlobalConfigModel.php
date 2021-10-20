<?php
namespace Modules\Core\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

class GlobalConfigModel extends Model
{
    public static function tableName()
    {
        return 'xcart_config';
    }

    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'primary' => true,
                'null' => false,
            ],
            'value' => [
                'class' => TextField::class,
                'null' => false
            ],
            'defvalue' => [
                'class' => TextField::class,
                'null' => false
            ],
            'category' => [
                'class' => CharField::class,
                'null' => false,
                'length' => 32,
            ],
            'type' => [
                'class' => CharField::class,
                'default' => 'text',
                'chosen' => [
                    'numeric'=>'numeric',
                    'text' => 'text',
                    'textarea' => 'textarea',
                    'checkbox' => 'checkbox',
                    'separator' => 'separator',
                    'selector' => 'selector',
                    'multiselector' => 'multiselector'
                ]
            ],
            'variants' => [
                'class' => TextField::class,
                'null' => false
            ],
            'validation' => [
                'class' => CharField::class,
                'null' => false
            ],
            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'comment' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
        ];
    }
}