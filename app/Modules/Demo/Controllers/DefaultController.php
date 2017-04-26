<?php
namespace Modules\Demo\Controllers;


use Modules\Product\Models\ProductModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Controller\Controller;
use Xcart\Manufacturer;

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

        $params = ['lock_forsale__in' => ['N', '']];


        if ($m_ids = Manufacturer::objects()->limit(rand(0,10))->order(['?'])->valuesList(['manufacturerid'])) {
            $params['manufacturerid__in'] = array_map(function($item){ return $item['manufacturerid'];}, $m_ids);
        }

        $t_models = ProductModel::objects()
                                ->filter($params)
                                ->limit(5000)
                                ->order([(rand(0,1) ? '' : '-').'productid'])
                                ->asArray()->all();

        if (!$t_models) {
            $this->refresh();
        }

        $ns = array_rand($t_models, 100);

        $models  = [];
        foreach ($ns as $number) {
            $models[] = new ProductModel($t_models[$number]);
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