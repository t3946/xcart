<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\RenderTrait;

/**
 * @property string code
 * @property string description
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
        return $this->renderString(str_replace(['<!--', '-->'], '', html_entity_decode($this->template)), $params);
    }

    public static function renderSnippetsInfo(): string
    {
        $snippets = array_map(static fn(SnippetModel $model) => "{{{$model->code}}} = {$model->description}", self::objects()->all());
        return nl2br("Replacements will occur according to the following rules:\n" . implode("\n", $snippets));
    }
}