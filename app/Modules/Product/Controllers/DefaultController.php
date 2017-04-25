<?php

namespace Modules\Product\Controllers;

use Modules\Product\Models\ProductModel;
use Xcart\App\Controller\Controller;

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
    }
}