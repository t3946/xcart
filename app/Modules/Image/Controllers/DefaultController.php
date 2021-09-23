<?php

namespace Modules\Image\Controllers;

use Xcart\App\Controller\FrontendController;

class DefaultController extends FrontendController
{
    public function actionGet()
    {
        $arg = func_get_args();
        d($arg);
    }
}