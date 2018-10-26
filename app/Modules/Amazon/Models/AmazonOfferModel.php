<?php

namespace Modules\Amazon\Models;


use Doctrine\DBAL\Types\Type;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class AmazonOfferModel extends Model
{

    public static function tableName()
    {
        return 'amazon_offers';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'product' => [
                'class' => HasToOneField::class,
                'modelClass' => ProductModel::class,
                'link' => ['ASIN' => 'ASIN'],
                'sqlType' => Type::STRING,
            ],
            'ASIN' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'competitors' => [
                'class' => HasManyField::class,
                'modelClass' => AmazonOfferCompetitorsModel::class,
                'link' => ['id' => 'offer_id']
            ],
            'myPrice' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'lowest_LandedPrice' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'lowest_ListingPrice' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'lowest_Shipping' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'lowest_Channel' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'buybox_LandedPrice' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'buybox_ListingPrice' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'buybox_Shipping' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'buybox_Channel' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'offers' => [
                'class' => IntField::class,
                'default' => 0,
                'null' => false,
            ],
            'offer_change_time' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
            'is_buybox_my' => [
                'class' => BooleanField::class,
            ],
            'sales_rank' => [
                'class' => IntField::class,
                'null' => true
            ],
            'fba_total_supply' => [
                'class' => IntField::class,
                'default' => 0,
                'null' => false,
            ],
            'fba_instock_supply' => [
                'class' => IntField::class,
                'default' => 0,
                'null' => false,
            ],
            'FNSKU' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'updated_at' => [
                'class' => DateTimeField::class,
                'autoNow' => true,
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],

        ];
    }
}