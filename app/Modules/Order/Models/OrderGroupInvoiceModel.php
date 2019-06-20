<?php
namespace Modules\Order\Models;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroupInvoice;

class OrderGroupInvoiceModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

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
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::className(),
                'modelClass' => OrderModel::className(),
                'link' => ['orderid' => 'orderid'],
                'null' => false,
                'primary' => true,
            ],
            'manufacturer' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
                'primary' => true,
            ],
            'invoice_number' => [
                'class' => IntField::className(),
                'null' => false,
                'primary' => true,
                'default' => 0
            ],
            'invoice_date' => [
                'class' => DateField::className(),
                'null' => true
            ],
        ];
    }

    public function __toString()
    {
        return "{$this->order->getOrderNumber()}_{$this->manufacturer->code}-I-{$this->invoice_number}";
    }

    public function getPaymentDueDate()
    {
        $date = $this->getField('invoice_date')->getValue()->add(new \DateInterval("P{$this->manufacturer->d_net_payment_terms_in_days}D"));
        return $date;
    }
}