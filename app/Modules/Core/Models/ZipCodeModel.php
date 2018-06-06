<?php

namespace Modules\Core\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ZipCodeModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_zip';
    }

    public static function getFields()
    {
        return [
            'zip' => [
                'class' => CharField::class,
            ],
            'state_model' => [
                'field' => 'state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['state' => 'code'],
                'extra' => ['country' => 'country_code'],
            ],

        ];
    }
}