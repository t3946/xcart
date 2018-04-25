<?php

namespace Modules\Landing\Controllers;

use Modules\Goods\Models\ProductModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class LandingController extends FrontendController
{
    public function index($id)
    {
        /** @var ProductModel $model */
        $model = ProductModel::objects()->get(['productid' => $id]);

        if (!$model || $model->forsale != 'Y' || $model->isGroupRoot()) {
            $this->error();
        }

        if ($model) {

            /** @var \Modules\Sites\Models\SiteModel $site */
            $site = Xcart::app()->getModule('Sites')->getSite();

            if ((!$site->isWork()) || (!$model->sites->filter(['storefrontid__in' => [$site->storefrontid]])->count())) {
                $this->error();
            }

            echo $this->render('product/landing.tpl', [
                'model' => $model,
            ]);
        }
    }
}