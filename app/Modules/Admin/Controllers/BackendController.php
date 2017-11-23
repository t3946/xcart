<?php

namespace Modules\Admin\Controllers;

use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Traits\SmartyRenderTrait;

class BackendController extends Controller
{
    use SmartyRenderTrait;

    public function beforeAction($action, $params)
    {
        $user = Xcart::app()->auth->getUser();

        if (!$user || $user->getIsGuest()) {
            $this->getRequest()->redirect('admin:login');
        }
        elseif (!$user->getIsSuperuser()) {
            $this->error(403);
        }
    }

    public function renderInSmarty($view, array $params = [])
    {
        return $this->renderSmarty("admin/home.tpl", [
            'single_mode' => true,
            'main'        => 'raw_html',
            'content'     =>  $this->render($view, $params),
        ]);
    }
}