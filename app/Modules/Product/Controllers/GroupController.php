<?php

namespace Modules\Product\Controllers;


use Modules\Brand\Models\BrandModel;
use Modules\Product\Models\CategoryModel;
use Modules\Product\Models\ProductCategoriesModel;
use Modules\Product\Models\ProductModel;
use Modules\Product\Models\ProductStorefrontModel;
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
        if ($this->getRequest()->getIsPost()) {

            $data = $_POST['group'];

            $store = new GroupStore(array_merge($data, ['brandid' => $id]));
            $params = $store->groupParams();

            $root = new ProductModel($params);
            $root->save();
            $root->group_root = $root->productid;
            $root->save();

            (new ProductStorefrontModel(
                [
                    'productid' => $root->productid,
                    'sfid' => $data['sfid']
                ])
            )->save();

            (new ProductCategoriesModel(
                [
                    'categoryid' => $data['category'],
                    'productid' => $root->productid,
                    'main' => 'Y'
                ]
            ))->save();

            if ($_POST['group']['products']) {
                /** @var ProductModel[] $products */
                if ($products = ProductModel::objects()->filter(['productid__in' => array_keys($data['products'])])) {
                    foreach ($products as $product) {
                        $product->group_root = $root->productid;
                        if (isset($data['truncate_checkbox'])) {
                            $product->product = trim(preg_replace("/^({$params['group_mask']})/", '', $product->product));
                        }

                        /** @var ProductCategoriesModel $p_cat */
                        if ($p_cat = $product->category_main->all()) {
                            foreach($p_cat as $cat) {
                                $cat->categoryid = $data['category'];
                                $cat->save();
                            }
                        }

                        $product->save();
                    }
                }
            }
        }

        if ($brand = BrandModel::objects()->get(['brandid' => $id])) {

            $store = new GroupStore(
                array_merge($_GET,
                    [
                        'sfid' => Xcart::app()->request->session->get('current_storefront')
                    ]
                ), $brand);

            if ($this->getRequest()->getIsAjax()) {

                echo $this->render('group/product/group_rows.tpl',
                    [
                        'brands' => $store->getLevels(),
                        'level' => $store->data['level']
                    ]
                );

                echo $this->render('group/group_products.tpl',
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
                        'level' => $store->data['level'],
                        'sfid' => $store->data['sfid']
                    ]
                );
            }
        }
    }

    public function categories()
    {
        /** @var CategoryModel $cat */
        $cat = [];
        if ($this->getRequest()->getIsAjax()) {
            if (isset($_GET['products'])) {
                if ($prc = ProductCategoriesModel::objects()
                    ->filter(
                        [
                            'productid__in' => $_GET['products'],
                            'main' => 'Y'
                        ]
                    )->all()) {
                    foreach ($prc as $pc) {
                        if (!isset($cat[$pc->categoryid])) {
                            $cat[$pc->categoryid] = $pc->category;
                        }
                    }

                    echo $this->render('group/categories.tpl',
                        [
                            'categories' => $cat,
                        ]
                    );
                }
            }
        }
    }
}