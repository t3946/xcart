<?php
namespace Modules\Order\Models;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\OrderGroupMemos;

class OrderGroupMemoModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass(): string
    {
        return OrderGroupMemos::class;
    }

    public static function tableName()
    {
        return 'xcart_order_group_memos';
    }

    public static function getFields()
    {
        return [
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
            'memo_number' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
                'primary' => true,
            ],
            'memo_date' => [
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
        return "{$this->order->getOrderNumber()}_{$this->manufacturer->code}-C-{$this->memo_number}";
    }

    public function getPaymentDueDate()
    {

        $date = $this->getField('memo_date')->getValue()->add(new \DateInterval("P{$this->manufacturer->d_net_payment_terms_in_days}D"));
        return $date;
    }

}