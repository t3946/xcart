<?php

namespace Modules\Account\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class RatingsModel extends Model {
    public static function tableName()
    {
        return 'ratings';
    }

    public static function getFields()
    {
        return [
            'rating_id' => [
                'class' => AutoField::class,
            ],
            'name' => [
                'class' => CharField::class,
            ],
            'slug' => [
                'class' => CharField::class,
            ],
        ];
    }
}
