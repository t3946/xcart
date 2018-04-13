<?php

namespace Modules\Order\Controllers;

use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class CheckoutController extends FrontendController
{
    public function actionShipping()
    {
        $app = Xcart::app();

        if ($app->request->getIsPost()) {
            $app->user;
        }

        //d($app->getUser()->getAttributes());

        echo $this->render('checkout/shipping.tpl', [

        ]);
    }
}