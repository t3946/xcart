<?php

namespace Modules\Product\Controllers;

use Modules\Product\Models\ProductModel;
use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class DefaultController extends Controller
{
    public function view_old($id, $slug)
    {
        $this->view_internal(ProductModel::objects()->filter(['productid' => $id])->get());
    }
    
    public function view($sku)
    {
        $this->view_internal(ProductModel::objects()->filter(['productcode' => $sku])->get());
    }

    /**
     * @param ProductModel|null $model
     *
     * @throws \Xcart\App\Exceptions\HttpException
     */
    private function view_internal($model = null)
    {
        if (!$model)
        {
            $this->error();
        }

        echo "<h1>{$model->product}</h1>";

        func_dump($model);

        SurfingHelper::logSurfPath(['resource_type' => SurfPathModel::GOAL_TYPE_PRODUCT, 'resource_id' => $model->pk]);
    }
}