<?php
namespace Modules\Order\Models;

use DateInterval;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroupInvoice;

class OrderGroupInvoiceModel extends Model
{
    use AutoMetaTrait;
    use DataModelTrait;

    public const INVOICE_STATUS_RECONCILED = 'R';
    public const INVOICE_STATUS_PRE_RECONCILED = 'P';
    public const INVOICE_STATUS_TENTATIVELY  = 'T';
    public const INVOICE_STATUS_UPDATED  = 'U';

    public static function getDataModelClass(): string
    {
        return OrderGroupInvoice::class;
    }

    public static function tableName()
    {
        return 'xcart_order_group_invoices';
    }

    public static function getFields()
    {
        return [
            'invoice_id' => AutoField::class,
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid'],
                'null' => false,
                'primary' => true,
            ],
            'manufacturer' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
                'primary' => true,
            ],
            'invoice_number' => [
                'class' => IntField::class,
                'null' => false,
                'primary' => true,
                'default' => 0
            ],
            'invoice_received' => [
                'class' => BooleanCharField::class,
                'default' => true
            ],
            'invoice_date' => [
                'class' => DateField::class,
                'null' => true
            ],
            'dx_invoice_number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'status' => [
                'class' => CharField::class,
                'choices' => [
                    'N' => 'Not received',
                    'A' => 'Added',
                    'U' => 'Updated',
                    'R' => 'Reconciled',
                    'P' => 'Pre-reconciled',
                    'T' => 'Tentatively paid',
                ],
                'default' => 'A',
                'null' => false
            ]
        ];
    }

    public function __toString()
    {
        return "{$this->order->getOrderNumber()}_{$this->manufacturer->code}-I-{$this->invoice_number}";
    }

    public function getPaymentDueDate()
    {
        $date = $this->getField('invoice_date')->getValue()->add(new DateInterval("P{$this->manufacturer->d_net_payment_terms_in_days}D"));
        return $date;
    }
}