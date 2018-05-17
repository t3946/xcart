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

        /** @var SiteModel $site_model */
        $site_model = Xcart::app()->getModule('Sites')->getSite();

        $sfid = $site_model->storefrontid;

        $email = $request->get->get('subscribe')['email'];

        $nonce = SubscribeHelper::getHashData($email, $sfid);

        $email_validator = new EmailValidator();

        if ($email_validator->validate($email)) {

            (new SubscriberModel(['email' => $email, 'sfid' => $sfid, 'nonce' => $nonce]) )->save();

            echo Xcart::app()->mail::renderTemplate(
              'subscribe_mail.tpl',
              [
                  'key' => $nonce,
              ]
            );

//            $res = Xcart::app()->mail->template(
//                $email,
//                'Subscribe to our newsletter',
//                'subscribe_mail.tpl',
//                [
//                    'key' => $nonce,
//                ]
//            );
        }

        $this->redirect($request->getDomain());
    }

    public function getSubscribe()
    {
        $request = $this->getRequest();
        $nonce = $request->post->get('hide');

        $sub_model = SubscriberModel::objects()->get(['nonce' => $nonce]);
        $sub_model->subscribe = true;
        $sub_model->nonce = '';
        $sub_model->update(['subscribe', 'nonce']);

        $this->redirect('/');

//        $this->redirect(SiteModel::objects()->get(['storefrontid' => $sub_model->sfid])->domain);

    }

    public function getUnsubscribe()
    {

    }
}