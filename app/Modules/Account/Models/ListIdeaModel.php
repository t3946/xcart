<?php

namespace Modules\Account\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class ListIdeaModel extends Model
{
    public static function tableName()
    {
        return 'account_list_ideas';
    }

    public static function getFields()
    {
        return [
            'product_id' => [
                'class' => AutoField::class,
            ],
            'name' => [
                'class' => CharField::class,
            ],

        ];
    }
}