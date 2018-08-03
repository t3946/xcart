<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 26.04.2018
 * Time: 17:47
 */

namespace Modules\Main\Controllers;


use Modules\Main\Forms\ContactUsForm;
use Modules\Meta\Types\MetaType;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class ContactFormController extends FrontendController
{
    /**
     *  Форма связаться с нами
     * @throws \Exception
     */
    public function actionContactUs(): void
    {
        $request = $this->getRequest();

        $this->setMetaBase(MetaType::PAGE);

        $bread = new Breadcrumbs();

        $bread->add('Contact us', $request->getAbsoluteUrl());

        $form = new ContactUsForm();
        if ($this->getRequest()->getIsPost() && $form->populate($_POST)->isValid()) {
            Xcart::app()->flash->add('Your message has been successfully sent');
            $this->refresh();
        } else {
            if (in_array('SKU or Order # not found', $form->getErrors('product_sku'))) {
                Xcart::app()->flash->error('Wrong SKU or Order #, please call us at 1-800-929-2431');
            }

        }
        ($this->getBreadcrumbs());
        $this->display('contactForm/contactUs.tpl', [
            'form' => $form,
            'breadcrumbs' => Xcart::app()->breadcrumbs->set($bread)
        ]);
    }
}