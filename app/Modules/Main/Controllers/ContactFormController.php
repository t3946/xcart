<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 26.04.2018
 * Time: 17:47
 */

namespace Modules\Main\Controllers;


use Modules\Main\Forms\ContactUsForm;
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
        $form = new ContactUsForm();
        if ($this->getRequest()->getIsPost() && $form->populate($_POST)->isValid()) {
            Xcart::app()->flash->add('Your message has been sent successfully');
            $this->refresh();
        }

        $this->display('contactForm/contactUs.tpl', [
            'form' => $form
        ]);
    }
}