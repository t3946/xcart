<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class TemplateCategoryModel extends Model
{
    public static function tableName()
    {
        return 'xcart_templates_categories';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Template category'
            ],
            'pos' => [
                'class' => IntField::class,
                'default' => 0,
            ]
        ];
    }

    public function __toString()
    {
        return (string) ($this->pk ? $this->name : 'Template category');
    }
}