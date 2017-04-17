<?php
namespace Modules\Demo\Controllers;


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

        echo $this->render('demo/catalog/index.tpl', [
            'breadcrumbs' => $breadcrumbs->get()
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