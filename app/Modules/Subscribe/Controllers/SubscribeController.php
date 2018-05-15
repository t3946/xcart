<?php

namespace Modules\Subscribe\Controllers;

use Xcart\App\Controller\Controller;

class SubscribeController extends Controller
{
    public function launchMessage()
    {
        $request = $this->getRequest();
//        dd($request->getAbsoluteUrl());
//        dd($request);

//        $res = Xcart::app()->mail->template(
//            'nikolay@s3stores.com',
//            'send',
//            'mail/log_template.tpl',
//            ['message' => "Email test: PASS"]
//        );

    }
}