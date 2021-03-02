<?php

namespace Modules\Order\Models;

use Doctrine\DBAL\Types\Types;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;

/**
 * @property mixed name
 * @property OrderStatusAvailabilityModel[]|Manager availability_statuses
 * @property int status_id
 * @property string type
 */
class OrderStatusModel extends Model
{

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
    public const ORDER_STATUS_INCOMPLETE_PO = 'IO';
    public const ORDER_STATUS_PENDING_PARTIAL_REFUND = '3';
    public const ORDER_STATUS_PARTIAL_REFUND = 'H';
    public const ORDER_STATUS_FULLY_REFUND = 'R';

    public const ORDER_DC_STATUS_NOT_SHIPPED = 'T';
    public const ORDER_DC_STATUS_DELIVERED = 'Z';
    public const ORDER_DC_STATUS_SHIPPED = 'S';
    public const ORDER_DC_STATUS_PENDING_AVAIL_CHECK = 'K';
    public const ORDER_DC_STATUS_PENDING_ADDL_PAYMENT = 'M';
    public const ORDER_DC_STATUS_SHIPPED_BACKORDERED = 'G';
    public const ORDER_DC_STATUS_RECEIVED_BY_AMAZON = 'DA';
    public const ORDER_DC_STATUS_PENDING_DISPATCH = 'DP';
    public const ORDER_DC_STATUS_RECEIVED_BY_DISTRIBUTOR = 'L';
    public const ORDER_DC_STATUS_RECEIVED_BY_DISPATCHED = 'C';

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

    public const ORDER_FRAUD_CHECK_STATUS_CLEARED = 'C';

    public const ORDER_VN_STATUS_VERIFIED = 'PV';

    public static function tableName()
    {
        return 'xcart_order_statuses';
    }

    public static function getFields()
    {
        return [
            'status_id' => AutoField::class,
            'code' => [
                'class' => CharField::class,
            ],
            'name' => [
                'class' => CharField::class,
            ],
            'description' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],
            'orderby' => [
                'class' => IntField::class,
            ],
            'type' => [
                'class' => CharField::class,
                'choices' => [
                    'AB' => 'AB',
                    'AC' => 'AC',
                    'BD' => 'BD',
                    'C2' => 'C2',
                    'CA' => 'CA',
                    'CB' => 'CB',
                    'DA' => 'DA',
                    'DC' => 'DC',
                    'PV' => 'PV',
                    'RU' => 'RU',
                ]
            ],
            'availability_statuses' => [
                'class' => ManyToManyField::class,
                'through' => OrderStatusAvailabilityModel::class,
                'link' => ['source_status_id', 'destination_status_id'],
                'modelClass' => self::class,
                'verboseName' => 'Available statuses'
            ]
        ];
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}