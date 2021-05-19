<?php

namespace Modules\Subscribe\Controllers;

use Modules\Sites\Models\SiteModel;
use Modules\Subscribe\Models\SubscriberModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class SubscribeController extends FrontendController
{
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