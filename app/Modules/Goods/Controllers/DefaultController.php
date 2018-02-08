<?php

namespace Modules\Goods\Controllers;

use Modules\Goods\Helpers\TabDataHelper;
use Modules\Goods\Models\ProductModel;
use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class DefaultController extends FrontendController
{
    public function actionViewOld($id, $slug)
    {
        $this->view_internal(ProductModel::objects()->filter(['productid' => $id])->get());
    }
    
    public function actionView($sku)
    {
        $this->view_internal(ProductModel::objects()->filter(['productcode' => $sku])->get());
    }

    /**
     * @param ProductModel|null $model
     *
     * @throws \Xcart\App\Exceptions\HttpException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    private function view_internal($model = null)
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
            $this->redirect($model->getAbsoluteUrl(true), 301);
        }

        $this->setMetaTemplate('products:base', [
            'model' => $model,
            'category' => $model->getMainCategory(),
            'site' => $site,
        ]);

        $this->display('product/product.tpl', [
            'model' => $model,
            'breadcrumbs' => $model->getBreadcrumbs(),
            'tabs' => TabDataHelper::getTabsFromManufacturer($model->manufacturerid),
        ]);


//        SurfingHelper::logSurfPath(['resource_type' => SurfPathModel::GOAL_TYPE_PRODUCT, 'resource_id' => $model->pk]);
    }

}