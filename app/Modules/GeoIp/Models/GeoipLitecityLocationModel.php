<?php

namespace Modules\GeoIp\Models;

use Doctrine\DBAL\Types\Types;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

/**
 * @property mixed postalCode
 * @property mixed region
 * @property mixed country
 * @property mixed city
 */
class GeoipLitecityLocationModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_geo_litecity_location';
    }

    public static function getFields()
    {
        return [
            'locId' => [
                'class' => AutoField::class,
            ],
            'region' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'country' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'sqlType' => Types::STRING,
                'modelClass' => CountryModel::class,
                'link' => ['country' => 'code'],
            ],
            'state_model' => [
                'field' => 'region',
                'class' => ForeignField::class,
                'sqlType' => Types::STRING,
                'modelClass' => StateModel::class,
                'link' => [
                    'region' => 'code',
                    'country' => 'country_code'
                ],
            ],
            'blocks' => [
                'field' => 'locId',
                'class' => HasManyField::class,
                'modelClass' => GeoLitecityBlocks::class,
                'link' => ['locId' => 'locId'],
            ],
        ];
    }

    public function __toString()
    {
        return "{$this->country}, {$this->region}, {$this->city}, {$this->postalCode}";
    }
}