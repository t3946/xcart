<?php


namespace Modules\Account\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * Class OrderProblemStatusesModel
 * @property int status_id
 * @property string status_text
 * @package Modules\Account\Models
 */
class OrderProblemStatusesModel extends Model
{
    public static function tableName(): string
    {
        return 'account_order_problems_statuses';
    }

    public static function getFields(): array
    {
        return [
            'status_id' => AutoField::class,
            'status_text' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false,
            ]
        ];
    }
}