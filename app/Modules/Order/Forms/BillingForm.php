<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 19.07.2018
 * Time: 15:20
 */

namespace Modules\Order\Forms;


use Modules\Core\Forms\FrontendModelForm;
use Modules\Order\Models\OrderModel;
use Modules\Order\Traits\AddressAttributesReplacement;
use Xcart\App\Form\Fields\SpaceField;

class BillingForm extends FrontendModelForm
{
    use AddressAttributesReplacement;

    private $_billingFields;

    public $replacement = 'b_';

    //public $include = ['cb_status'];

    protected function beforeConstruct()
    {
        $billingForm = new BillingAddressForm();
        $this->_billingFields = $billingForm->getFields();
    }


    public function getModel()
    {
        return new OrderModel();
    }


    public function getFields()
    {
        return $this->_billingFields;
    }

}