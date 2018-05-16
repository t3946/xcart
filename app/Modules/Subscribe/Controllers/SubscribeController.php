<?php

namespace Modules\Subscribe\Controllers;

use Modules\Sites\Models\SiteModel;
use Modules\Subscribe\Helpers\SubscribeHelper;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class SubscribeController extends FrontendController
{
    public function sendMessage()
    {
        $request = $this->getRequest();

        $domain = $request->getDomain();

        /** @var SiteModel $site_model */
        $site_model = SiteModel::objects()->get(['domain' => $domain]);

        $sfid = $site_model->storefrontid;

        $email = $request->get->get('subscribe')['email'];

        $key = SubscribeHelper::getHashData($email, $sfid);

        $email_validator = new EmailValidator();

        if ($email_validator->validate($email)) {
            $res = Xcart::app()->mail->template(
                $email,
                'Subscribe to our newsletter',
                'subscribe_mail.tpl',
                [
                    'key' => $key,
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