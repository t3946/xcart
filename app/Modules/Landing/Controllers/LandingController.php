<?php

namespace Modules\Landing\Controllers;

use Modules\Landing\Forms\OrderForm;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class LandingController extends FrontendController
{
    public function index($id = null)
    {
        Xcart::app()->request->session->open();
        echo $this->render('product/landing.tpl');
    }

    public function order()
    {
        Xcart::app()->request->session->open();
        $form = new OrderForm();

        if ($this->getRequest()->getIsPost()) {
            $form->populate($_POST);

            if ($form->isValid()) {
                $form->send();
                Xcart::app()->flash->success('Thanks!');
                $this->redirect('landing:product');
            }
        }

        echo $this->render('product/landing_checkout.tpl', [
            'form' => $form
        ]);
    }
}