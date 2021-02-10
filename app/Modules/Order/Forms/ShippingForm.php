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

class ShippingForm extends FrontendModelForm
{
    use AddressAttributesReplacement;

    protected array $_shippingFields;
    protected array $_contactFields;
    protected array $_billingFields;
    protected array $_purchase_order_details_form;
    protected array $_purchasing_manager_form;
    protected array $_accounts_payable_form;

    public $replacement = 's_';

    public $include = ['cb_status'];


    protected function beforeConstruct()
    {
        $shippingForm = new ShippingAddressForm();
        $contactForm = new ContactInfoForm();

        $this->_shippingFields = $shippingForm->getFields();
        $this->_shippingFields[$shippingForm->replacement.'firstname']['html']['data-duplicate'] = $this->getName().'_firstname';
        $this->_contactFields = $contactForm->getFields();
    }


    public function getModel()
    {
        return new OrderModel();
    }

    public function getFieldsets()
    {
        return [
            'shipping' => array_keys($this->_shippingFields),
            'contact' => array_keys($this->_contactFields),
        ];
    }

    public function getFields()
    {
        return array_merge($this->_shippingFields, $this->_contactFields);
    }

}