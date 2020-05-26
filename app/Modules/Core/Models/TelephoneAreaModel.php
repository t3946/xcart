<?php

namespace Modules\Core\Models;

use Doctrine\DBAL\Types\Type;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class TelephoneAreaModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_Telephone_area_codes';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'country_model' => [
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['country_code' => 'code'],
                'field' => 'country',
                'sqlType' => Type::STRING,
            ],
            'state_model' => [
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['country_code' => 'country_code', 'state_code' => 'code'],
                'field' => 'state',
                'sqlType' => Type::STRING,
            ],
            'state_code' => [
                'class' => CharField::class,
            ],
        ];
    }
}