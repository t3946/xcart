<?php

namespace Modules\Account\Models;

use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class TotalProductRatingsModel extends Model {
    public static function tableName()
    {
        return 'total_product_ratings';
    }

    public static function getFields()
    {
        return [
            'product_id' => [
                'class' => IntField::class,
            ],
            'rating_id' => [
                'class' => IntField::class,
            ],
            'rating' => [
                'field' => 'rating_id',
                'class' => ForeignField::class,
                'modelClass' => RatingsModel::class,
                'link' => ['rating_id' => 'rating_id'],
                'primary' => true,
            ],
            'total' => [
                'class' => IntField::class,
            ],
        ];
    }
}
