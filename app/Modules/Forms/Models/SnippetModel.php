<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\RenderTrait;

/**
 * @property string code
 */
class SnippetModel extends Model
{
    use RenderTrait;

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'code' => [
                'class' => CharField::class,
                'unique' => true,
                'verboseName' => "Code",
                'required' => true,
            ],
            'name' => [
                'class' => CharField::class,
                'verboseName' => "Name",
                'required' => true,
            ],
            'description' => [
                'class' => TextField::class,
                'verboseName' => "Description",
                'null' => true,
            ],
            'template' => [
                'class' => TextField::class,
                'verboseName' => "Template",
                'null' => true,
            ]
        ];
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    public function render($params)
    {
        return $this->renderString(str_replace(['<!--', '-->'],'', html_entity_decode($this->template)), $params);
    }
}