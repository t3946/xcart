<?php


namespace Modules\Help\Controllers;

use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;

class HelpController extends FrontendController
{
    public function actionIndex()
    {
        return $this->display('help/base.tpl');
    }
}