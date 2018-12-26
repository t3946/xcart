<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property mixed name
 */
class OrderStatusModel extends Model
{
    use AutoMetaTrait;

    public const ORDER_STATUS_NULL = null;
    public const ORDER_STATUS_CHECKOUT_STEP1 = 'S1';
    public const ORDER_STATUS_CHECKOUT_STEP2 = 'S2';
    public const ORDER_STATUS_CHECKOUT_STEP3 = 'S3';
    public const ORDER_STATUS_CHECKOUT_STEP4 = 'S4';

    public const ORDER_STATUS_AUTHORIZED = 'AP';
    public const ORDER_STATUS_COMPLETED = 'P';
    public const ORDER_STATUS_QUEUED = 'Q';
    public const ORDER_STATUS_UNPAID = 'N';
    public const ORDER_STATUS_NOT_FINISHED = 'I';
    public const ORDER_STATUS_FAILED = 'F';
    public const ORDER_STATUS_PENDING_ORDER_ENTRY = 'E';
    public const ORDER_STATUS_CANCELED = 'A';
    public const ORDER_STATUS_DECLINED = 'D';
    public const ORDER_STATUS_UNPAID_PO = 'O';
    public const ORDER_STATUS_PENDING_PARTIAL_REFUND = '3';
    public const ORDER_STATUS_PARTIAL_REFUND = 'H';

    public const ORDER_DC_STATUS_NOT_SHIPPED = 'T';
    public const ORDER_DC_STATUS_SHIPPED = 'S';
    public const ORDER_DC_STATUS_RECEIVED_BY_AMAZON = 'DA';
    public const ORDER_DC_STATUS_RECEIVED_BY_DISTRIBUTOR = 'L';
    public const ORDER_DC_STATUS_RECEIVED_BY_DISPATCHED  = 'C';

    public const ORDER_DA_STATUS_NOT_SHIPPED = 'DT';
    public const ORDER_DA_STATUS_SHIPPED = 'DS';
    public const ORDER_DA_STATUS_PENDING_DISPATCH = 'PD';
    public const ORDER_DA_STATUS_DISPATCHED = 'DC';
    public const ORDER_DA_STATUS_PENDING_ORDER_ENTRY = 'DE';
    public const ORDER_DA_STATUS_PENDING_AVAIL = 'DK';
    public const ORDER_DA_STATUS_RECEIVED_BY_DISTRIBUTOR = 'DL';

    public const ORDER_BD_STATUS_UNPAID = 'W';
    public const ORDER_BD_STATUS_PAID = 'Y';
    public const ORDER_BD_STATUS_INVOICED = 'X';

    public const ORDER_VN_STATUS_VERIFIED = 'PV';

    public static function tableName()
    {
        return 'xcart_order_statuses';
    }
    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::className(),
                'primary' => true
            ],
        ];
    }
    public function __toString()
    {
        return $this->name;
    }
}