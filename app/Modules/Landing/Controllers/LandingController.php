<?php

namespace Modules\Landing\Controllers;

use Modules\Goods\Models\ProductModel;
use Modules\Landing\Helpers\LandingHelper;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class LandingController extends FrontendController
{
    public function index($id = null)
    {
        echo $this->render('product/landing.tpl', [

        ]);
    }
}