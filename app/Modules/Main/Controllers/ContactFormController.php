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
        if ($this->getRequest()->getIsPost() && $form->populate($_POST)) {

            if (!empty($_POST['ContactUsForm[company_name_full]'])) {
                $errors = [
                    400 => 'Bad Request',
                    402 => 'Payment Required',
                    404 => 'Not Found',
                    406 => 'Not Acceptable',
                    410 => 'Gone',
                    418 => 'I\'m a teapot',
                    429 => 'Too Many Requests ',
                    434 => 'Requested host unavailable',
                    451 => 'Unavailable For Legal Reasons',
                    500 => 'Internal Server Error',
                    503 => 'Service Unavailable ',
                ];
                $this->redirect('main:contact_us_form', [], array_rand($errors));
            }
            if ($form->isValid()) {
                Xcart::app()->flash->add('Your message has been successfully sent');
                $this->refresh();
            }
        }
        ($this->getBreadcrumbs());
        $this->display('contactForm/contactUs.tpl', [
            'form' => $form,
            'breadcrumbs' => Xcart::app()->breadcrumbs->set($bread)
        ]);
    }
}