<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 23.07.2018
 * Time: 9:47
 */

namespace Modules\Order\Forms;


use Modules\Core\Forms\FrontendForm;
use Modules\Order\OrderModule;
use Xcart\App\Form\Fields\TextField;

class CheckoutReviewForm extends FrontendForm
{

    private $_orderDetailsForm;
    private $_purchasingManagerForm;
    private $_accountsPayableForm;
    private $_localFields;

    protected function beforeConstruct()
    {
        $orderDetailsForm = new PurchaseOrderDetailsForm();
        $purchasingManagerForm = new PurchasingManagerForm();
        $accountsPayableForm = new AccountsPayableForm();

        $this->_orderDetailsForm = $orderDetailsForm->getFields();
        $this->_purchasingManagerForm = $purchasingManagerForm->renamedFields();
        $this->_accountsPayableForm = $accountsPayableForm->renamedFields();


        $this->_localFields = [
            'customer_notes' => [
                'class' => TextField::class,
                'label' => OrderModule::t('Customer notes'),
                'className' => 'wide_footer',
                'html' => [
                    'placeholder' => 'Put order related instructions here',
                ],
            ],
        ];
    }

    public function getFieldsets()
    {
        return [
            'order_details' => ['po_number', 'organization_name'],
            'purchasing_manager' => array_keys($this->_purchasingManagerForm),
            'accounts_payable' => array_merge(array_keys($this->_accountsPayableForm), ['purchase_order_file']),
            'notes' => array_keys($this->_localFields)
        ];
    }

    public function getFields()
    {
//        dd(array_merge($this->_orderDetailsForm, $this->_purchasingManagerForm,
//            $this->_accountsPayableForm, $this->_localFields));

        return array_merge(
            $this->_orderDetailsForm,
            $this->_purchasingManagerForm,
            $this->_accountsPayableForm,
            $this->_localFields
        );
    }
}