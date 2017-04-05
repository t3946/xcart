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

}