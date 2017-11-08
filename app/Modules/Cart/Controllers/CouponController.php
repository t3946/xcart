<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 08.11.17
 * Time: 22:32
 */

namespace Modules\Cart\Controllers;

use Modules\Cart\Models\CouponKitModel;
use Xcart\App\Controller\FrontendController;

class CouponController extends FrontendController
{

    public function actionView($code)
    {
        if ($model = CouponKitModel::objects()->filter(['code' => $code, 'active' => true])->get())
        {
            echo $this->render('coupon/view.tpl', [
                'model' => $model
            ]);
        }
        else {
            $this->error(404);
        }
    }

}