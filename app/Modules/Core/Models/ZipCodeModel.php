<?php

namespace Modules\Core\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class ZipCodeModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_zip_code_info';
    }

    public static function getFields()
    {
        return [
            'zip' => [
                'class' => CharField::class,
                'primary' => true,
            ],

        ];
    }
}