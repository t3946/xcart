<?php


namespace Modules\Translate\Models;


use Modules\Core\Models\CountryModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class LanguageModel extends Model
{

    public static function getFields()
    {
        return [
            'lang_id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
            ],
            'lang_code' => [
                'class' => CharField::class,
            ],
            'country' => [
                'class' => ForeignField::class,
                'field' => 'country_code',
                'modelClass' => CountryModel::class,
                'link' => ['country_code' => 'code'],
            ],
            'status' => [
                'class' => BooleanField::class,
                'default' => true
            ]
        ];
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}