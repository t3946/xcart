<?php


namespace Modules\Order\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Order\Forms\OrderStatusForm;
use Modules\Order\Models\OrderStatusModel;

class OrderStatusAdmin extends Admin
{
    public ?string $sort = 'orderby';

    public function getListColumns() : array
    {
        return [
            'type',
            'name',
            'description',
            'code',
        ];
    }

    public function getForm() : OrderStatusForm
    {
        return new OrderStatusForm();
    }

    public function getModel()
    {
        return new OrderStatusModel();
    }

    public function getListItemActions()
    {
        return [
            'update',
        ];
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function applyOrder($qs)
    {
        $order = $this->getOrder();

        if ($order && isset($order['raw'])) {
            $qs->order([
                $order['raw']
            ]);
        } else if ($this->sort) {
            $qs->order([
                'type',
                $this->sort
            ]);
        } else {
            $qs->order([
                'code'
            ]);
        }
        return $qs;
    }

    public function getCanSort($qs): bool
    {
        if ($this->sort) {
            $order = $qs->getOrder();
            return in_array($this->sort, $order, true);
        }

        return parent::getCanSort($qs);
    }

    public static function getName(): string
    {
        return 'Order statuses';
    }
}