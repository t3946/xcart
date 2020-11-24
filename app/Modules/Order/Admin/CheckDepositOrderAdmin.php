<?php


namespace Modules\Order\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Order\Models\CheckDepositedOrderModel;
use Xcart\App\Orm\Model;

class CheckDepositOrderAdmin extends ListViewAdmin
{
    public $ownerField = 'order';
    public static $public = false;

    public function getListColumns()
    {
        return ['order', 'check_number', 'amount', 'notes'];
    }

    public function getForm()
    {
    }

    public function getModel()
    {
        return new CheckDepositedOrderModel;
    }

    public function getListGroupActions()
    {
        return [];
    }

    public function getListItemActions()
    {
        return [];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'order':
                $order = parent::getItemProperty($item, $property);
                return "<a href='{$item->order->getAdminUrl()}'>$order</a>";
        }
        return parent::getItemProperty($item, $property);
    }

    public function getAvailableListColumns()
    {
        return array_merge(parent::getAvailableListColumns(), [
            'order' => ['th' => ['width' => '1']],
            'check_number' => ['th' => ['width' => '1']],
            'amount' => ['th' => ['width' => '1']],
        ]);
    }
}