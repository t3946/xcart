<?php

namespace Modules\Order\Models\Decisions;

use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class DecisionsCustomerFiles extends Model
{
    public static function tableName()
    {
        return 'decisions_user_files';
    }

    public static function getFields()
    {
        return [
            'decision_id' => [
                'class' => IntField::class,
            ],
            'user_file_id' => [
                'class' => IntField::class,
            ],
        ];
    }
}
