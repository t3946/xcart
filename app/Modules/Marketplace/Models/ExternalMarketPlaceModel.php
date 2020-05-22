<?php


namespace Modules\Marketplace\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class ExternalMarketPlaceModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_products_external_marketplaces';
    }
    public static function getFields()
    {
        return [
            'id' => AutoField::class,
        ];
    }

    public function __toString()
    {
        return (string) $this->marketplace_name;
    }
}