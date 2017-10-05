<?php

namespace Modules\Product\Controllers;


use Modules\Brand\Models\BrandModel;
use Modules\Product\Helpers\ProductHelper;
use Modules\Product\Models\CategoryModel;
use Modules\Product\Models\ProductCategoriesModel;
use Modules\Product\Models\ProductModel;
use Modules\Product\ProductModule;
use Modules\Product\Stores\GroupStore;
use Modules\Sites\Models\SiteModel;
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

        $store = new GroupStore(
            array_merge($_GET,
                [
                    'sfid' => Xcart::app()->request->session->get('current_storefront')
                ]
            ), $model);

        if ($this->getRequest()->getIsAjax()) {

            $this->jsonResponse(
                [
                    'html' => $this->render('group/group_products.tpl',
                        [
                            'products' => $store->getGroupNewProducts(),
                            'level' => $store->data['level'],
                            'site' => Xcart::app()->getModule('Sites')->getSite(),
                        ]
                    )
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

            $store = new GroupStore(array_merge($_POST['group'],
                [
                    'brandid' => $id,
                    'sku' => ProductHelper::getNewGroupSKU($_POST['group']['manufacturerid'])
                ])
            );

            $model = $store->createGroupProduct();

            if ($this->getRequest()->getIsAjax()) {

                if ($model) {
                    $images = null;

                    foreach ($model->childs->order(['group_order'])->limit(4) as $key => $product) {
                        $images .= $this->renderSmarty(
                            'group_thumbnail.tpl',
                            ['product' => $product]
                        );
                    }

                    $this->jsonResponse(
                        [
                            'result' => $this->render('group/product/_group_result.tpl',
                                [
                                    'images' => $images,
                                    'model' => $model
                                ]
                            )
                        ]);
                }
                return;
            }

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

                $res = '';

                $store->level = $store->data['level'];

                $brands = $store->getLevels();

                if ($store->level > 3) {
                    $store->defaultPagerPageSize = 200;
                } else {
                    $store->defaultPagerPageSize = 50;
                }

                if ($products = $store->getModels()) {

                    if (count($brands) === 1) {

                        if ($store->data['group_phrase'] = ProductHelper::getFirstSame(
                            array_map(
                                function ($p) {
                                    return $p->product;
                                },
                                $products)
                        )) {
                            $store->level = ProductHelper::getGroupLevel($store->data['group_phrase']) + 2;
                            $brands = $store->getLevels();
                            $products = $store->getModels();
                        }
                    }
                }

                $res .= $this->render('group/product/group_rows.tpl',
                    [
                        'brands' => $brands,
                        'level' => $store->level
                    ]
                );

                $res .= $this->render('group/group_products.tpl',
                    [
                        'products' => $products,
                        'parent_level' => $store->level - 1,
                        'site' => SiteModel::objects()->get(['storefrontid' => Xcart::app()->request->session->get('current_storefront')])
                    ]
                );
                $this->jsonResponse([
                    'html' => $res,
                    'group_phrase' => $store->data['group_phrase']
                ]);

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

    public function images()
    {
        $res = '';
        if ($this->getRequest()->getIsAjax()) {
            if (isset($_GET['products'])) {
                if ($products = ProductModel::objects()->filter(
                    [
                        'productid__in' => $_GET['products']
                    ])->all()) {
                    foreach ($products as $key => $product) {
                        $res .= $this->renderSmarty(
                            'group_thumbnail.tpl',
                            ['product' => $product]
                        );
                    }
                }
            }
        }
        echo $res;
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

    public function group_remove()
    {
        if ($this->getRequest()->getIsAjax()) {
            if (isset($_POST['product_id']) && $model = ProductModel::objects()->get(
                    [
                        'productid' => $_POST['product_id']
                    ]
                )) {
                $model->setAttributes([
                    'group_root' => null,
                    'group_order' => 255
                ]);
                $model->save();
            }

        }
    }

    public function group_add()
    {
        $res = [];

        if ($this->getRequest()->getIsAjax()) {
            if (isset($_POST['product_id'])) {

                /** @var ProductModel $model */

                if ($model = ProductModel::objects()->get(
                    [
                        'productid' => $_POST['product_id']
                    ]
                )) {
                    /** @var ProductModel $add_model */

                    if ($add_model = ProductModel::objects()->get(['productcode' => $_POST['add_sku']])) {
                        if ($model->isGroupRoot()) {

                            if (!$add_model->isGroupRoot() && !$add_model->isGroupChild()) {
                                $add_model->group_root = $model->productid;
                                $add_model->save();
                            } else {
                                $res['error'] = ProductModule::t('SKU is group root or child');
                            }

                        } elseif (!$model->isGroupRoot() && !$model->isGroupChild()) {

                            if ($add_model->isGroupRoot()) {
                                $model->group_root = $add_model->productid;
                                $model->save();
                            } else {
                                $res['error'] = ProductModule::t('SKU is not group root');
                            }
                        }
                    }
                }
            }
        }
        $this->jsonResponse($res);
    }
}