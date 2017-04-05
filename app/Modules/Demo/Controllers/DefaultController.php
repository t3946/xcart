<?php
namespace Modules\Demo\Controllers;


use Xcart\App\Controller\Controller;

class DefaultController extends Controller
{
    public $defaultAction = 'index';

    public function index()
    {
        echo $this->render('demo/home.tpl');
    }

    public function catalogIndex()
    {
        echo $this->render('demo/catalog/index.tpl');
    }

    public function catalogBrand()
    {
        echo $this->render('demo/catalog/brand.tpl');
    }

    public function catalogSearch()
    {
        echo $this->render('demo/catalog/search.tpl');
    }

}