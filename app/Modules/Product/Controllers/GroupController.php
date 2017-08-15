<?php

namespace Modules\Product\Controllers;


use Modules\Product\Stores\GroupStore;
use Xcart\App\Controller\PrototypeAdminController;

class GroupController extends PrototypeAdminController
{
    public function group_list()
    {
        $store = new GroupStore();

        $brands = $store->getBrands();

        var_dump($brands);
    }
}