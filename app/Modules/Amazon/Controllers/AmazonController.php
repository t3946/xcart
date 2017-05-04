<?php

namespace Modules\Amazon\Controllers;


use Xcart\App\Controller\PrototypeAdminController;

class AmazonController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        echo $this->renderInternal('reports/search.tpl', array_merge(
                [

                ])
        );
    }

}