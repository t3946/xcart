<?php

namespace Modules\Goods\Controllers;

use Xcart\App\Controller\FrontendController;
use Xcart\App\Validation\EmailValidator;

class NotifyStockController extends FrontendController
{
    public function getCustomerClaim()
    {
        $request = $this->getRequest();

        $email_validator = new EmailValidator();

        $email = $request->post->get('email');

        if (!$email || trim($email) == '' || !$email_validator->validate($email)){
            /** TODO Доработать условие */
        }
        else {
            /** TODO Тоже придумать что-нибудь */
        }
    }

}