<?php


namespace Modules\AMP\Controllers;

use Modules\AMP\Models\AmpProductModel;
use Modules\Product\Models\ProductModel;
use Modules\Sites\Helpers\CurrentSiteHelper;
use Xcart\App\Controller\FrontendController;
use Modules\Sites\SitesModule;
use Xcart\App\Main\Xcart;

class AmpController extends FrontendController
{
    public function amp($id)
    {
        /*if ($model = AmpProductModel::objects()->get(['productid' => $id]) && ($exep = CurrentSiteHelper::check(Xcart::app()->request)))*/
        if ($model = AmpProductModel::objects()->get(['productid' => $id]) )
        {
                echo $this->render('product/amp.tpl', [
                    'model' => $model,
                ]);

        } else {
            $this->redirect('/');
        }
    }
}
