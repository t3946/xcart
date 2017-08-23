<?php

namespace Modules\Product\Controllers;


use Modules\Brand\Models\BrandModel;
use Modules\Product\Models\ProductModel;
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
        if ($this->getRequest()->getIsPost() && isset($_POST['group']['submit'])) {
            if ($_POST['group']['products']) {
                foreach ($_POST['group']['products'] as $product_id => $product) {
                    $params = [
                        'productcode' => $_POST['group']['sku'],
                        'product' => $_POST['group']['title'],
                        'fulldescr' => $_POST['group']['description'],
                        'forsale' => 'Y',
                        'brandid' => $id,
                        'manufacturerid' => 0
                    ];
                }
            }

        }

        if ($brand = BrandModel::objects()->get(['brandid' => $id])) {

            $store = new GroupStore($_GET, $brand);

            if ($this->getRequest()->getIsAjax()) {

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