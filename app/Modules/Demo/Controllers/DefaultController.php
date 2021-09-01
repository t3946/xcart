<?php
namespace Modules\Demo\Controllers;


use Modules\Product\Models\ProductModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;
use Xcart\Manufacturer;

class DefaultController extends FrontendController
{
    public $defaultAction = 'index';

    public function index()
    {
        echo $this->render('demo/home.tpl');
    }

    public function catalogIndex()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Painting and Painting Accessories', '/');
        $breadcrumbs->add('Oil Painting sets');

        $params = [
            'forsale' => 'Y',
//            'manufacturerid__in' => array_map(function ($item) { return $item['manufacturerid']; },
//                Manufacturer::objects()->limit(rand(1, 10))->order(['?'])->valuesList(['manufacturerid'])
//            ),
        ];

        $t_models = ProductModel::objects()
                                ->filter($params)
                                ->limit(rand(1000, 5000))
                                ->order([(rand(0,1) ? '' : '-').'productid'])
                                ->asArray()
                                ->all();

        if (empty($t_models)) {
            $this->refresh();
            die();
        }

        $ns = array_rand($t_models, 100);

        $models  = [];
        foreach ($ns as $number) {
            $models[] = new ProductModel($t_models[$number]);
        }

        $this->display('demo/catalog/index.tpl', [
            'breadcrumbs' => $breadcrumbs->get(),
            'models' => $models,
        ]);
    }

    public function catalogBrand()
    {
        echo $this->render('demo/catalog/brand.tpl');
    }

    public function catalogSearch()
    {
        echo $this->render('demo/catalog/search.tpl');
    }

    public function product()
    {
        echo $this->render('demo/product/product.tpl');
    }

}