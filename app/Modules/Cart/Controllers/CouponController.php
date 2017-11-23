<?php
namespace Modules\Cart\Controllers;

use Modules\Cart\Models\CouponKitModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Traits\SmartyFrontendRenderTrait;

class CouponController extends FrontendController
{
    use SmartyFrontendRenderTrait;

    public function actionView($code)
    {
        if ($this->getRequest()->session->has('coupon_code'))
        {
            $scode = $this->getRequest()->session->get('coupon_code');

            if (strtoupper($code) == strtoupper($scode) && $model = CouponKitModel::objects()->filter(['code' => $code, 'active' => true])->get())
            {
                echo $this->renderInSmarty('coupon/view.tpl', [
                    'model' => $model
                ]);
            }
        }

        if (empty($model)) {
            $this->error(404);
        }
    }

}