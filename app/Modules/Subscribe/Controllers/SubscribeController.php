<?php

namespace Modules\Subscribe\Controllers;

use Modules\Sites\Models\SiteModel;
use Modules\Subscribe\Helpers\SubscribeHelper;
use Modules\Subscribe\Models\SubscriberModel;
use Modules\Subscribe\SubscribeModule;
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

        Xcart::app()->flash->success("Confirmation email was sent. Please check your inbox.");

        $sfid = $site_model->storefrontid;

        $email = $request->get->get('subscribe')['email'];

        $nonce = SubscribeHelper::getHashData($email, $sfid);

        $email_validator = new EmailValidator();

        if ($email_validator->validate($email)) {

            if (!(SubscriberModel::objects()->get(['email' => $email, 'sfid' => $sfid]))) {
                (new SubscriberModel(['email' => $email, 'sfid' => $sfid, 'nonce' => $nonce]))->save();

//                echo Xcart::app()->mail::renderTemplate(
//                    'subscribe_mail.tpl',
//                    [
//                        'key' => $nonce,
//                    ]
//                );

            Xcart::app()->mail->template(
                $email,
                'Subscribe to our newsletter',
                'subscribe_mail.tpl',
                [
                    'key' => $nonce,
                    'role' => $sfid,
                ]
            );
            }
        }

        $this->redirect('/');
    }

    public function getSubscribe()
    {
        $request = $this->getRequest();
        $nonce = $request->post->get('hide');
        $sfid = $request->post->get('role');

        if ($nonce) {
            if ($sub_model = SubscriberModel::objects()->get(['nonce' => $nonce])) {

                $sub_model->subscribe = true;
                $sub_model->nonce = '';
                $sub_model->update(['subscribe', 'nonce']);

                /** @var SiteModel $site_model */
                $site_model = SiteModel::objects()->get(['storefrontid' => $sub_model->sfid]);

                Xcart::app()->flash->success("Thank you! Subscription confirmed");

                $this->redirect($site_model->getAbsoluteUrl());
            } else {
                /** @var SiteModel $site_model */
                if ($sfid !== null && $site_model = SiteModel::objects()->get(['storefrontid' => $sfid])) {

                    Xcart::app()->flash->success("You have already subscribed");

                    $this->redirect($site_model->getAbsoluteUrl());
                }
            }
        }

    }

    public function getUnsubscribe()
    {

    }
}