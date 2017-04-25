<?php
namespace Modules\Demo\Controllers;


use Modules\Product\Models\ProductModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class DefaultController extends Controller
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
        
//        $t_models = ProductModel::objects()->filter(['lock_forsale__in' => ['N', '']])->limit(120)->order(['?'])->all();
        $t_models = ProductModel::objects()->filter(['lock_forsale__in' => ['N', '']])->limit(120)->all();

        $ns = array_rand($t_models, 10);

        $models  = [];
        foreach ($ns as $number) {
            $models[] = $t_models[$number];
        }

        echo $this->render('demo/catalog/index.tpl', [
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