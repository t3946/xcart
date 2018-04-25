<?php

namespace Modules\Core\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class LanguageModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_languages';
    }

    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::class,
                'primary' => true
            ],
            'name' => [
                'class' => CharField::class,
                'primary' => true
            ],

        ];
    }
}