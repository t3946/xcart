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
use Xcart\App\Form\Fields\SpaceField;

class ShippingForm extends FrontendModelForm
{

    private $_shippingFields;
    private $_contactFields;


    public function init()
    {
        parent::init();
        $shippingForm = new ShippingAddressForm();
        $contactForm = new ContactInfoForm();

        $this->_shippingFields = $shippingForm->getFields();
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