<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 31.05.2018
 * Time: 11:57
 */

namespace Modules\Main\Controllers;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Controller\FrontendController;

class TestController extends FrontendController
{
    public function actionTest(): void
    {
        $dist = DistributorModel::objects()->get(['manufacturerid' => 12]);

        var_dump($dist->isGoodTimeToSendEmail());
    }
}