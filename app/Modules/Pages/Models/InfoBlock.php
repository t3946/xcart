<?php

namespace Modules\Pages\Models;

use Modules\Translate\Models\LanguageModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

/**
 * Class InfoBlock
 * @package Modules\Text\Models
 *
 * @property String name
 * @property String text
 * @property String key
 */
class InfoBlock extends Model
{
    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
                'label' => 'Name'
            ],
            'text' => [
                'class' => TextField::class,
                'label' => 'Text'
            ],
            'tag' => [
                'class' => CharField::class,
                'label' => 'Developer key',
                'null' => true
            ],
            'language' => [
                'class' => ForeignField::class,
                'field' => 'lang_id',
                'modelClass' => LanguageModel::class,
                'label' => 'Language'
            ]
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}