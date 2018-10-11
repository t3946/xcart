<?php

namespace Modules\Amazon\Models;


use Doctrine\DBAL\Types\Type;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Core\Models\ZipCodeModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class AmazonFulfillmentLocationsModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_amazon_fulfillment_locations';
    }

    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::class,
                'primary' => true,
                'null' => false,
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['country' => 'code'],
                'sqlType' => Type::STRING,
            ],
            'state_model' => [
                'field' => 'state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'link' => ['state' => 'stateid'],
            ],
            'zipcode_model' => [
                'field' => 'zipcode',
                'class' => ForeignField::class,
                'modelClass' => ZipCodeModel::class,
                'link' => ['zipcode' => 'zip', 'country' => 'country'],
            ],
        ];
    }
}