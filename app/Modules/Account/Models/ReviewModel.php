<?php

namespace Modules\Account\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BigIntField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class ReviewModel extends Model
{
    public const MIN_RATE = 1;
    public const MAX_RATE = 5;

    public static function tableName()
    {
        return 'product_reviews';
    }

    public static function getFields()
    {
        return [
            'product_review_id' => [
                'class' => AutoField::class,
            ],
            'user_id' => [
                'class' => BigIntField::class,
            ],
            'product_id' => [
                'class' => BigIntField::class,
            ],
            'header' => [
                'class' => CharField::class,
            ],
            'body' => [
                'class' => CharField::class,
            ],
            'overall_rate' => [
                'class' => IntField::class,
            ],
            'location' => [
                'class' => CharField::class,
            ],
            'created' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
        ];
    }

    public function toArray(): array
    {
        return $this->getAttributes();
    }
}
