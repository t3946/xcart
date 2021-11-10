<?php

namespace Modules\Core\Models;

use Doctrine\DBAL\Types\Types;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property int country_lang_id
 * @property CountryModel country
 * @property string country_code
 * @property LanguageModel lang
 * @property int lang_id
 * @property string value
 */
class CountryLangsModel extends Model
{

    public static function tableName(): string
    {
        return 'xcart_country_langs';
    }
    public static function getFields(): array
    {
        return [
            'country_lang_id' => AutoField::class,
            'country' => [
                'class' => ForeignField::class,
                'field' => 'country_code',
                'modelClass' => CountryModel::class,
                'link' => ['country_code' => 'code'],
                'sqlType' => Types::STRING,
            ],
            'lang' => [
                'field' => 'lang_id',
                'class' => ForeignField::class,
                'modelClass' => LanguageModel::class,
                'link' => ['lang_id' => 'lang_id'],
                'null' => false,
            ],
            'value' => CharField::class
        ];
    }
}