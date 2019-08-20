<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 26.04.2018
 * Time: 17:47
 */

namespace Modules\Main\Controllers;


use Modules\Main\Forms\ContactUsForm;
use Modules\Main\MainModule;
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

        $bread->add(MainModule::t('Contact us'), $request->getAbsoluteUrl());

        $form = new ContactUsForm();
        if ($this->getRequest()->getIsPost() && $form->populate($_POST)->isValid()) {
            Xcart::app()->flash->add(MainModule::t('Your message has been successfully sent'));
            $this->refresh();
        } else {
            if (in_array(MainModule::t('SKU or Order # not found'), $form->getErrors('product_sku'))) {
                $site = Xcart::app()->getModule('Sites')->getSite();
                $config = $site->getConfig();
                Xcart::app()->flash->error(MainModule::t('Wrong SKU or Order #, please call us at'). ' '. $config['cidev_top_header_code']);
            }

        }
        ($this->getBreadcrumbs());
        $this->display('contactForm/contactUs.tpl', [
            'form' => $form,
            'breadcrumbs' => Xcart::app()->breadcrumbs->set($bread)
        ]);
    }
}