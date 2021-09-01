<?php

namespace Modules\Amazon\Models;


use Xcart\App\Form\Fields\DateTimeField;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class AmazonOfferCompetitorsModel extends Model
{
    public static function tableName()
    {
        return 'amazon_offers_competitors';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'offer' => [
                'field' => 'offer_id',
                'class' => ForeignField::class,
                'modelClass' => AmazonOfferModel::class,
                'link' => ['offer_id' => 'id'],
            ],
            'seller' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'country' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'state' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'rating' => [
                'class' => IntField::class,
                'default' => 0,
                'null' => false,
            ],
            'LandingPrice' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'ListingPrice' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'Shipping' => [
                'class' => DecimalField::class,
                'null' => true,
            ],
            'channel' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'is_buybox' => [
                'class' => BooleanField::class,
            ],

        ];
    }
}