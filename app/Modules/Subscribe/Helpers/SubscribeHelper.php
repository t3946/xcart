<?php

namespace Modules\Subscribe\Helpers;

use Modules\Sites\Models\SiteModel;
use Modules\Subscribe\Models\SubscriberModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Validation\EmailValidator;

class SubscribeHelper
{

    public static function getHashData($email, $code): string
    {
        return md5($email.$code);
    }

    public static function sendMessage($email): array
    {
        /** @var SiteModel $site_model */
        $site_model = Xcart::app()->getModule('Sites')->getSite();

        $sfid = $site_model->storefrontid;

        $nonce = SubscribeHelper::getHashData($email, $sfid);

        $email_validator = new EmailValidator();

        if ($email_validator->validate($email)) {
            /** @var SubscriberModel $subscriber */
            $subscriber = SubscriberModel::objects()->get(['email' => $email, 'sfid' => $sfid]);

            if ($subscriber && $subscriber->subscribe) {

                return ['success' => 'You have already subscribed'];
                //$this->redirect('/');
            }

            if (!$subscriber) {
                (new SubscriberModel(['email' => $email, 'sfid' => $sfid, 'nonce' => $nonce]))->save();
            }

//            Xcart::app()->mail->template(
//                $email,
//                'Subscribe to our newsletter',
//                'subscribe_mail.tpl',
//                [
//                    'key' => $nonce,
//                    'role' => $sfid,
//                ]
//            );

            return ['success' => 'Confirmation email was sent. Please check your inbox.'];
        }
        else {
            return ['error' => 'Your email address is invalid. Please enter a valid address.'];
        }

    }

}