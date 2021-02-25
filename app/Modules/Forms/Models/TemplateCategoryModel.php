<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\TreeModel;

class TemplateCategoryModel extends TreeModel
{
    public static function tableName()
    {
        return 'xcart_templates_categories';
    }

    public static function getFields()
    {
        return array_merge([
            'id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Template category'
            ],
            'pos' => [
                'class' => IntField::class,
                'default' => 0,
            ],
        ], parent::getFields());
    }

    public function __toString()
    {
        return (string)($this->pk ? $this->name : 'Template category');
    }
}