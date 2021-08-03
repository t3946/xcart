<?php

namespace Modules\Help\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\Fields\IntField;

class HelpMenuContentModel extends Model
{
    public static function tableName()
    {
        return 'xcart_help_menu_content';
    }

    public static function getFields()
    {
        return [
            'item_content_id' => [
                'class' => AutoField::class,
            ],
            'form_type' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'choices' => [
                    'question' => 'Question'
                ]
            ],
            'answer' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'question' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'order_by' => [
                'class' => IntField::class,
                'default' => 0
            ],
            'menu' => [
                'field' => 'menu_id',
                'class' => ForeignField::class,
                'modelClass' => HelpListModel::class,
                'link' => ['menu_id' => 'menu_id'],
            ],
        ];
    }
}