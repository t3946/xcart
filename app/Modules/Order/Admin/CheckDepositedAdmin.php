<?php


namespace Modules\Order\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Order\Forms\CheckDepositForm;
use Modules\Order\Models\CheckDepositedModel;
use Xcart\App\Orm\Model;

class CheckDepositedAdmin extends Admin
{
    public $createTemplate = 'deposited/_check_create.tpl';
    public $updateTemplate = 'deposited/_check_update.tpl';

    public function getListColumns()
    {
        return ['check_date', 'currency_model', 'total_deposit_amount', 'status'];
    }

    public function getForm()
    {
        return new CheckDepositForm;
    }

    public function getModel()
    {
        return new CheckDepositedModel;
    }

    public static function getName()
    {
        return 'Checks deposited';
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'currency_model':
                return $item->currency_model->currency_code;
            case 'status':
                return $item->getField('status')->toText();
            case 'total_deposit_amount':
                $amount = parent::getItemProperty($item, $property);
                return "<a href='{$this->getUpdateUrl($item->pk)}'>{$amount}</a>";
        }
        return parent::getItemProperty($item, $property);
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
                $this->sort
            ]);
        } else {
            $qs->order([
                '-check_date'
            ]);
        }
        return $qs;
    }

    public function getListItemActions()
    {
        return [];
    }



}