<?php

namespace Modules\Goods\Controllers\Api;


use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Controller\Controller;

class ApiAnalyticController extends Controller
{

    public function actionPath()
    {
        SurfingHelper::logSurfPath([
            'resource_type' => SurfPathModel::GOAL_TYPE_PRODUCT,
            'resource_id' => $productid,
        ]);
    }
}