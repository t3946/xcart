<?php

namespace Modules\Goods\Controllers;

use Modules\Goods\Models\ProductModel;
use Xcart\App\Controller\Controller;

class ProductController extends Controller
{
    public function getDistributorProductList()
    {

        dd($_GET);
        $qs = ProductModel::objects()->getQuerySet();
        $filter = [];

        foreach ($_GET as $key => $value){
            $filter[$key] = $value;
        }
        /** @var ProductModel $product_models */
        $product_models = $qs->filter($filter)->all();

        $mass_of_all_mpn = [];

        foreach ($product_models as $product_model){
            $mass_of_all_mpn[] = $product_model->getMPN();
        }

//        var_dump( $mass_of_all_mpn );
    }
}