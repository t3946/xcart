<?php

namespace Modules\Subscribe\Controllers;

use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class SubscribeController extends Controller
{
    public function launchMessage()
    {
        $request = $this->getRequest();
//        dd($request->getAbsoluteUrl(), $request->get);
//        dd($request);

        $res = Xcart::app()->mail->template(
            'nikolay@s3stores.com',
            'send',
            'mail/log_template.tpl',
            ['message' => "Email test: PASS"]
        );

    }
}