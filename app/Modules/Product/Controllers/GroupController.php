<?php

namespace Modules\Product\Controllers;


use Modules\Brand\Models\BrandModel;
use Modules\Product\Stores\GroupStore;
use Xcart\App\Controller\PrototypeAdminController;

class GroupController extends PrototypeAdminController
{
    public function group_list()
    {
        $store = new GroupStore();

        echo $this->renderInternal('group/brand_list.tpl',
            [
                'brands' => $store->getBrands(),
            ]
        );
    }

    public function group($id)
    {
        if ($brand = BrandModel::objects()->get(['brandid' => $id])) {

            $store = new GroupStore($_GET, $brand);

            if (isset($store->data['ajax'])) {

                echo $this->render('group/product/group_rows.tpl',
                    [
                        'brands' => $store->getLevels(),
                        'level' => $store->data['level']
                    ]
                );

                echo  $this->render('group/group_products.tpl',
                    [
                        'products' => $store->getModels(),
                        'parent_level' => $store->data['level'] - 1
                    ]
                );

            } else {
                echo $this->renderInternal('group/group_list.tpl',
                    [
                        'brands' => $store->getLevels(),
                        'level' => $store->data['level']
                    ]
                );
            }
        }
    }
}