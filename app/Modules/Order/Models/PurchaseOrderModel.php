<?php

namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Model;

/**
 * @property string PO_number
 * @property int po_id
 * @property string original_po_file
 * @property string status
 */
class PurchaseOrderModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_po_pipeline';
    }

    public static function getFields()
    {
        return [
            'po_id' => [
                'class' => AutoField::class,
            ],
            'modify_date' => [
                'class' => TimestampField::class,
                'autoNowAdd' => true,
                'autoNow' => true,
            ]
        ];
    }
}