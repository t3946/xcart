<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 26.04.2018
 * Time: 17:47
 */

namespace Modules\Main\Controllers;


use Modules\Main\Forms\ContactUsForm;
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

        $bread = new Breadcrumbs();

        $bread->add('Contact us', $request->getAbsoluteUrl());

        $form = new ContactUsForm();
        if ($this->getRequest()->getIsPost() && $form->populate($_POST)->isValid()) {
            Xcart::app()->flash->add('Your message has been sent successfully');
            $this->refresh();
        }
        ($this->getBreadcrumbs());
        $this->display('contactForm/contactUs.tpl', [
            'form' => $form,
            'breadcrumbs' => Xcart::app()->breadcrumbs->set($bread)
        ]);
    }
}