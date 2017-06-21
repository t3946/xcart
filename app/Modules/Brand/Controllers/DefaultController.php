<?php
namespace Modules\Brand\Controllers;

use Modules\Brand\Models\BrandModel;
use Modules\Product\Controllers\CategoryController;
use Modules\Product\Models\ProductModel;

class DefaultController extends CategoryController
{
    public $view = 'catalog/brand.tpl';
    public $filters = ['price', 'filter'];

    public function actionViewOld($id, $slug)
    {
        $this->view_internal(BrandModel::objects()->filter(['brandid' => $id])->get());
    }

    public function actionView($sku)
    {
//        $this->view_internal(BrandModel::objects()->filter(['productcode' => $sku])->get());
    }

    public function actionList() {

    }

    public function getQS($data)
    {
        return ProductModel::objects()
                           ->filter([ 'forsale' => 'Y', 'brand__brandid' => $data->brandid, ]);
    }
}