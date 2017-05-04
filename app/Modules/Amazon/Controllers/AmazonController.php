<?php

namespace Modules\Amazon\Controllers;


use Modules\Amazon\Stores\AmazonStore;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\Connection;

class AmazonController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $amazonStore = new AmazonStore([]);

        echo $this->renderInternal('amazon/index.tpl',
            [
                'amazon_products' => $amazonStore->getAmazonProducts()
            ]
        );
    }

}