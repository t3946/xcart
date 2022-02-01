<?php

namespace Modules\Order\Models\Decisions;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class DecisionsCustomerFiles extends Model
{
    public static function tableName()
    {
        return 'decisions_customer_files';
    }

    public static function getFields()
    {
        return [
            'decision_id' => [
                'class' => IntField::class,
            ],
            'file_id' => [
                'class' => IntField::class,
            ],
        ];
    }
}
