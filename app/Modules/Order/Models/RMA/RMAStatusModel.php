<?php

namespace Modules\Order\Models\RMA;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class RMAStatusModel extends Model
{
    public const STATUS_OPEN = 1;
    public const STATUS_CREATED = 2;
    public const STATUS_SUBMIT_TO_US = 3;
    public const STATUS_SEND_TO_CUSTOMER = 4;

    public static function tableName()
    {
        return 'xcart_rma_statuses';
    }

    public static function getFields()
    {
        return [
            'code' => AutoField::class,
            'name' => [
                'class' => CharField::class,
            ],
            'orderby' => IntField::class
        ];
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}