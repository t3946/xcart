<?php

namespace Modules\Goods\Controllers;

use Modules\Goods\Helpers\ProductSortHelper;
use Modules\Goods\Helpers\TabDataHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductVideosModel;
use Modules\Meta\Types\MetaType;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class DefaultController extends FrontendController
{
    public function actionViewOld($id, $slug): void
    {
        $this->view_internal(ProductModel::objects()->filter(['productid' => $id])->get());
    }
    
    public function actionView($sku): void
    {
        $this->view_internal(ProductModel::objects()->filter(['productcode' => $sku])->get());
    }

    /**
     * @param ProductModel|null $model
     *
     * @throws \Xcart\App\Exceptions\HttpException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    private function view_internal($model = null): void
    {
        /** @var \Modules\Sites\Models\SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        if (!$model) {
            $this->error();
        }

        if (!$model->isForSale()) {
            $this->error(410);
        }


        if (!$model->checkSite($site->pk)) {
            $this->redirect($model->getAbsoluteUrl(true), [],301);
        }

        $category = $model->getMainCategory();

        $this->setMetaBase(MetaType::PRODUCT, [
            'model' => $model,
            'category' => $category,
            'site' => $site
        ]);

        $params = [
            'model' => $model,
            'breadcrumbs' => Xcart::app()->breadcrumbs->set($model->getBreadcrumbs()),
            'tabs' => TabDataHelper::getTabsFromManufacturer($model->manufacturerid),
            'category' => $category,
        ];

        /** @var ProductVideosModel $video_models */
        if ($video_models = ProductVideosModel::objects()->filter(['product_id' => $model->productid])->all() ) {
            $params['videos'] = $video_models;
        }


            if ($model->isGroupRoot()) {
            $pager = new Pagination($model->getFrontendChilds(), [
                'pageSize' => 25,
                'view' => 'core/pager/front_endless.tpl',
                'pageKey' => 'page'
            ], new QuerySetDataSource());

            if ($this->getRequest()->getIsAjax()) {
                $pagerView = $pager->createView();

                $this->jsonResponse([
                    'href' => $pagerView->hasNextPage() ? $pagerView->getUrl($pager->getPage() + 1) : false,
                    'content' => $this->render('catalog/category.tpl', [ 'model' => $model, 'pager' => $pager,]),
                    'page_count' => $this->render('catalog/parts/_page_count.tpl', [ 'model' => $model, 'pager' => $pager,]),
                ]);
                Xcart::app()->end();
            }
            else {
                $orderBy = Xcart::app()->request->session->get('category_sort', ProductSortHelper::$default);

                $params = array_merge($params, [
                    'pager' => $pager->setPage(0),
                    'sort'  => $orderBy,
                    'sort_arr'  => ProductSortHelper::$orderBy,
                ]);
            }
        }

        $this->display('product/product.tpl', $params);
    }
}