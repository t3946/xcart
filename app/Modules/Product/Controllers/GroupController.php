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
                $brand = $store->getLevel();
                echo $this->render('group/product/group.tpl',
                    [
                        'brand' => $brand,
                        'group_phrase' => $brand->getNotModelAttribute('group_phrase'),
                        'count' => $brand->getNotModelAttribute('count'),
                    ]
                );
            } else {
                echo $this->renderInternal('group/group_list.tpl',
                    [
                        'brands' => $store->getLevels(),
                    ]
                );
            }
        }
    }
}