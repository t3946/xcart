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
        $store = new GroupStore(
            [
                'sfid' => Xcart::app()->request->session->get('current_storefront')
            ]
        );

        echo $this->renderInternal('group/brand_list.tpl',
            [
                'brands' => $store->getBrands(),
            ]
        );
    }

    public function group_products($id = null)
    {
        $model = null;

        if ($id) {
            $model = ProductModel::objects()->get(['productid' => $id]);
        }

        if ($this->getRequest()->getIsPost() & $model) {
            $store = new GroupStore(array_merge($_POST['group'],
                [
                    'brandid' => $model->brandid,
                ]
            ), $model);

            $store->updateGroupProduct();

            $this->redirect('product:group_products', [], 303);
        }

        $store = new GroupStore($_GET, $model);

        if ($this->getRequest()->getIsAjax()) {

            echo $this->render('group/group_products.tpl',
                [
                    'products' => $store->getGroupNewProducts(),
                ]
            );

        } else {

            echo $this->renderInternal('group/group_product_list.tpl',
                [
                    'products' => $store->getGroupProducts(),
                    'level' => $store->data['level'],
                    'sfid' => Xcart::app()->request->session->get('current_storefront')
                ]
            );
        }
    }

    public function group($id)
    {
        if ($this->getRequest()->getIsPost()) {

            $store = new GroupStore(array_merge($_POST['group'], ['brandid' => $id]));

            $store->createGroupProduct();

            $this->redirect('product:group', ['id' => $id], 303);
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
        /** @var CategoryModel[] $cat */
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