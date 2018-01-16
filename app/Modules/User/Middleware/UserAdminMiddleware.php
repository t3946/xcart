<?php

namespace Modules\User\Middleware;


use Modules\User\Models\UserModel;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;

class UserAdminMiddleware extends Middleware
{
    public $isProcessRequest = true;

    public function processHttpRequest($request)
    {
        if ($request->get->has('identify_admin')) {
            if ($model = UserModel::objects()->get(['login' => $request->get->get('identify_admin')])) {

                $identifiers = array_merge(Xcart::app()->request->session->get('identifiers') ?: [], ['login' => $model->login, 'login_type' => 'A']);

                Xcart::app()->request->session->add('identifiers', $identifiers);
            }
        }
    }
}