<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property int $reason_id
 * @property string $name
 * @property int $pos
 */
class OrderVoidedReasonModel extends Model
{
    public static function tableName()
    {
        return 'xcart_order_voided_reasons';
    }

    public static function getFields()
    {
        return [
            'reason_id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Voided reason'
            ],
            'pos' => [
                'class' => IntField::class,
                'default' => 100000,
            ],
        ];
    }

    public function __toString(): string
    {
        return $this->pk ? $this->name : 'Voided reason';
    }
}