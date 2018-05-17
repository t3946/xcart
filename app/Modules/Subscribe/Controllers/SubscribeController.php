<?php

namespace Modules\Subscribe\Controllers;

use Modules\Sites\Models\SiteModel;
use Modules\Subscribe\Helpers\SubscribeHelper;
use Modules\Subscribe\Models\SubscriberModel;
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

        $nonce = SubscribeHelper::getHashData($email, $sfid);

         /** TODO построить урл */
//        Xcart::app()->router;
        $url = '';


        $email_validator = new EmailValidator();

        if ($email_validator->validate($email)) {

            (new SubscriberModel(['email' => $email, 'sfid' => $sfid, 'nonce' => $nonce]) )->save();

            $res = Xcart::app()->mail->template(
                $email,
                'Subscribe to our newsletter',
                'subscribe_mail.tpl',
                [
                    'key' => $nonce,
                    'url' => $url,
                ]
            );
        }

        $this->redirect($request->getDomain());
    }

    public function getSubscribe()
    {
        $request = $this->getRequest();

        $nonce = $request->post->get('hidden');

        $sub_model = SubscriberModel::objects()->get(['nonce' => $nonce]);
        $sub_model->subscribe = true;
        $sub_model->nonce = '';
        $sub_model->update(['subscribe', 'nonce']);

        $this->redirect(SiteModel::objects()->get(['storefrontid' => $sub_model->sfid])->domain);

    }

    public function getUnsubscribe()
    {

    }
}