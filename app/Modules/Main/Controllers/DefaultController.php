<?php
namespace Modules\Main\Controllers;

use Modules\Sites\SitesModule;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class DefaultController extends FrontendController
{
    public $defaultAction= 'index';

    public function index()
    {
//        $this->redirect('demo:index');

        //@TODO: To future
//        /** @var SitesModule $module */
//        $module = Xcart::app()->getModule('Sites');
//
//        $module = $module->getSite()->getDefaultModule();
//        $controller = new \Modules\Demo\Controllers\DefaultController($this->getRequest());
//        $controller->run(null, func_get_args());


        $this->display('home.tpl', []);
    }
}