<?php

namespace Modules\Subscribe\Controllers;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class SubscribeController extends Controller
{
    public function sendMessage()
    {
        $request = $this->getRequest();

        $domain = $request->getDomain();

        /** @var SiteModel $site_model */
        $site_model = SiteModel::objects()->get(['domain' => $domain]);

        $sfid = $site_model->storefrontid;

        $email = $request->get->get('subscribe')['email'];

        $email_validator = new EmailValidator();

        if ($email_validator->validate($email)) {
            $res = Xcart::app()->mail->template(
                $email,
                'Subscribe to our newsletter',
                'subscribe_mail.tpl',
                [
                    'message' => "",
                    'email' => $email,
                    'sfid' => $sfid
                ]
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