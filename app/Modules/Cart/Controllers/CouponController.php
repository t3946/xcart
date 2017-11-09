<?php
namespace Modules\Cart\Controllers;

use Modules\Cart\Models\CouponKitModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Traits\SmartyFrontendRenderTrait;

class CouponController extends FrontendController
{
    use SmartyFrontendRenderTrait;

    public function actionView($code)
    {
        if ($model = CouponKitModel::objects()->filter(['code' => $code, 'active' => true])->get())
        {
            echo $this->renderInSmarty('coupon/view.tpl', [
                'model' => $model
            ]);
        }
        else {
            $this->error(404);
        }
    }

}