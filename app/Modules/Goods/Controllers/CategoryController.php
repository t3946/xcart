<?php

namespace Modules\Goods\Controllers;

use Modules\Goods\Models\CategoryModel;
use Modules\Meta\Types\MetaType;
use Xcart\App\Main\Xcart;

class CategoryController extends AbstractCatalogController
{
    public $view = 'catalog/category.tpl';
    public $filters = ['price', 'brand', 'filter'];

    public function actionViewOld($id, $slug)
    {
        $this->preView(CategoryModel::objects()->filter(['categoryid' => $id])->get());
    }

    public function actionViewIndex($id)
    {
        /** @var CategoryModel $category */
        if ($category = CategoryModel::objects()->filter(['categoryid' => $id])->get()) {
            $this->redirect($category->getAbsoluteUrl(), [], 301);
        }
    }

    public function actionView($sku)
    {
        $this->preView(CategoryModel::objects()->filter(['productcode' => $sku])->get());
    }

    public function preView($model)
    {
        if (!$model) {
            $this->redirect('catalog:list', [], 301);
        }

        /** @var \Modules\Sites\Models\SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        $this->setMetaBase(MetaType::CATEGORY, [
            'model' => $model,
            'site' => $site
        ]);

        $this->view_internal($model);
    }

    public function getQS($data)
    {
        return parent::getQS($data)
            ->filter([
                'categories__lft__gte' => $data->lft,
                'categories__rgt__lte' => $data->rgt,
                'categories__root' => $data->root,
            ]);
    }

    public function actionList()
    {
        $categories = CategoryModel::objects()->filter([
            'level' => 1,
            'active_product_count__gt' => 0,
            'avail' => 'Y',
            'storefrontid' => Xcart::app()->getModule('Sites')->getSite()
        ])->all();

        /** @var ProductModel[] $products */
        $products = \Modules\Goods\Helpers\SliderDataHelper::getSliderData('recently_viewed_products');

        echo $this->render('catalog/subcategory.tpl', [
            'categories' => $categories,
            'showSlider' => !empty($products)
        ]);
    }

    public function actionContact()
    {
        //echo '11';
        echo $this->render('pages/contact.tpl');
    }

    public function getAdvancedData($data = null): array
    {
        return [
            'analytics_source' => 'category_list'
        ];
    }
}