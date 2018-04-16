<?php
namespace Modules\Goods\Controllers;


use Modules\Goods\Models\ProductModel;

class PromoController extends AbstractCatalogController
{
    public function actionBestsellers()
    {
        echo '123';
    }


    public function actionFeatured()
    {

        $this->jsonResponse(['val' =>'123']);

        ProductModel::objects();
    }

    public function actionNew()
    {

    }

    public function actionViewed()
    {

    }
}