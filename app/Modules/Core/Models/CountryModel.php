<?php

namespace Modules\Core\Models;


use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Manager;
use Xcart\App\QueryBuilder\Expression;
use Modules\Core\CoreModule;
use Modules\Shipping\Models\ZoneElementModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Orm\Model;
/**
 * @property string code
 * @property string name
 * @property string active
 * @property null|Manager|CountryLangsModel[] lang_names
 */
class CountryModel extends Model
{
    use AutoMetaTrait;

    public static $codes = [
        'United States' => 'US',
        'USA' => 'US',
        'US' => 'US',
        'Canada' => 'CA',
        'RU' => 'RU'
    ];

    public static function tableName()
    {
        return 'xcart_countries';
    }

    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::class,
                'primary' => true,
            ],
            'zone_element' => [
                'class' => HasManyField::class,
                'modelClass' => ZoneElementModel::class,
                'link' => ['code' => 'field'],
                'extra' => ['field_type' => 'C']
            ],
            'name' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'lang_names' => [
                'class' => HasManyField::class,
                'modelClass' => CountryLangsModel::class,
                'link' => ['code' => 'country_code']
            ],
            'is_many_line_addresses' => BooleanField::class
        ];
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
    public function countryNameBySite(): string
    {
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        /** @var CountryLangsModel $country_lang */
        $country_lang = $this->lang_names->get(['lang_id' =>$site->lang->lang_id, 'country_code' => $this->code]);

        return $country_lang->value ?? $this->name;
    }
}