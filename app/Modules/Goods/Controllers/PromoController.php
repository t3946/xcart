<?php
namespace Modules\Goods\Controllers;


use Modules\Goods\Models\ProductModel;

class PromoController extends AbstractCatalogController
{
    public function actionBestsellers()
    {

    }


    public function actionFeatured()
    {
        ProductModel::objects();
    }

    public function actionNew()
    {

    }

    public function actionViewed()
    {

    }
}