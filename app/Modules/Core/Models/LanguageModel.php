<?php

namespace Modules\Core\Models;


use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property string value
 */
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
            'value' => CharField::class
        ];
    }

    public static function translate($name, $code = 'US')
    {
        return self::objects()->get(['code' => $code, 'name' => $name]);
    }

    public function __toString()
    {
        return (string)$this->value;
    }
    public static function getCountry(string $code)
    {
        global $shop_language;
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        /** @var LanguageModel $lang_model */
        $lang_model = static::objects()->get(['name' => "country_$code", 'code' => $shop_language]);

        return $lang_model->value;

    }
}