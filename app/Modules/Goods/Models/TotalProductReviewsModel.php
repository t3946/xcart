<?php

namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class TotalProductReviewsModel extends Model {
    public static function tableName()
    {
        return 'total_product_reviews';
    }

    public static function getFields()
    {
        return [
            'product_id' => [
                'class' => IntField::class,
            ],
            'total' => [
                'class' => IntField::class,
            ],
        ];
    }
}
