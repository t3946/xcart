<?php

namespace Modules\User\Controllers;


use Modules\Sites\Models\SiteModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class UserController extends FrontendController
{
    public function remember_admin_user($slug)
    {
        if ($slug) {

            Xcart::app()->request->cookie->add(Xcart::app()->request->session->getSessionKey() . "A_remember", $slug);

            Xcart::app()->request->redirect('/');
        }
    }
}