<?php

namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class OrderCxInvoiceModel extends Model
{
    use AutoMetaTrait;

    public const STATUS_PAID = 'PAID';
    public const STATUS_SENT = 'SENT';
    public const STATUS_CANCELLED = 'CANCELLED';
    public const STATUS_REFUNDED = 'REFUNDED';


    public static function tableName()
    {
        return 'xcart_order_cx_invoices';
    }

    public static function getFields()
    {
        return [
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid'],
                'primary' => true,
                'null' => false
            ],
            'invoice_order_number' => [
                'class' => IntField::class,
                'null' => false
            ],
            'status' => [
                'class' => CharField::class,
                'null' => false,
                'choices' => [
                    self::STATUS_PAID => 'PAID',
                    self::STATUS_SENT => 'SENT',
                    self::STATUS_CANCELLED => 'CANCELLED',
                    self::STATUS_REFUNDED => 'REFUNDED',
                ]
            ]

        ];
    }

    public function __toString()
    {
        return "{$this->order->getOrderNumber()}-{$this->invoice_order_number}";
    }
}