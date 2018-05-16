<?php

namespace Modules\Subscribe\Controllers;

use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class SubscribeController extends Controller
{
    public function sendMessage()
    {
        $request = $this->getRequest();
//        dd($request->getDomain());
//        dd($request->getAbsoluteUrl(), $request->get);
//        dd($request);


        $email = $request->get->get('subscribe')['email'];

//        dd($email);

        $email_validator = new EmailValidator();

        if ($email_validator->validate($email)) { dd($email);
            $res = Xcart::app()->mail->template(
                $email,
                'Subscribe to our newsletter',
                'subscribe_mail.tpl',
                ['message' => ""]
            );
        }

    }

    public function getSubscribe()
    {
        $request = $this->getRequest();


    }

    public function getUnsubscribe()
    {

    }
}