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

class CheckoutBillingForm extends FrontendModelForm
{
    use AddressAttributesReplacement;

    private $_billingFields;

    public $replacement = 'b_';

    protected $fieldsSettings = [
        'fieldTemplate' => 'forms/field/default/custom/one_field_checkout.tpl',
        'errorsTemplate' => 'forms/field/default/custom/errors.tpl',
        'hintTemplate' => 'forms/field/default/custom/hint.tpl',
        'labelTemplate' => 'forms/field/default/custom/label.tpl',
    ];

    protected function beforeConstruct()
    {
        $billingForm = new CheckoutBillingAddressForm();
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