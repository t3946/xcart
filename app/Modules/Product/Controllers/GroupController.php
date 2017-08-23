<?php

namespace Modules\Product\Controllers;


use Modules\Brand\Models\BrandModel;
use Modules\Product\Models\ProductModel;
use Modules\Product\Stores\GroupStore;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;

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
        $mask = null;

        if ($this->getRequest()->getIsPost()) {

            $data = $_POST['group'];

            $params = [
                'productcode' => trim($data['sku']),
                'product' => trim($data['title']),
                'fulldescr' => $data['description'],
                'original_provider' => Xcart::app()->user->login,
                'forsale' => 'Y',
                'brandid' => $id,
                'manufacturerid' => $data['manufactuerid'],
            ];
            if (isset($data['truncate_checkbox'])) {
                $params['group_option'] = $mask = trim($data['truncate_mask']);
            }

            $root = new ProductModel($params);
            $root->save();
            $root->group_root = $root->productid;
            $root->save();
            
            if ($_POST['group']['products']) {
                /** @var ProductModel[] $products */
                if ($products = ProductModel::objects()->filter(['productid__in' => array_keys($data['products'])])) {
                    foreach ($products as $product) {
                        $product->group_root = $root->productid;
                        if (isset($data['truncate_checkbox'])) {
                            $product->product = trim(preg_replace("/^({$mask})/", '', $product->product));
                        }
                        $product->save();
                    }
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
                        'id' => $id,
                        'brands' => $store->getLevels(),
                        'level' => $store->data['level']
                    ]
                );
            }
        }
    }
}