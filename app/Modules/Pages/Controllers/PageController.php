<?php

namespace Modules\Pages\Controllers;

use Mindy\QueryBuilder\Q\QOr;
use Modules\Meta\Types\MetaType;
use Modules\Pages\Models\Page;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

/**
 * Class PageController
 * @package Modules\Pages
 */
class PageController extends FrontendController
{
    public $defaultAction = 'view';

    /**
     * @param Page $model
     * @return string
     */
    protected function getView(Page $model): string
    {
        return 'pages/' . $model->getView();
    }

    public function actionView($url = null)
    {
        /** @var SiteModel $site_model */
        $site_model = Xcart::app()->getModule('Sites')->getSite();

        $filter = [];

        if (empty($url)){
            $filter['is_index'] = true;
        }
        else {
            $filter['url'] = ltrim($url, '/');
        }

        $filter[] = new QOr(['sites__through__storefront_id' => $site_model->storefrontid, 'sites__through__storefront_id__isnull' => true]);

        /** @var Page $model */
        $model = Page::objects()
            ->published()
            ->get($filter);

        if ($model === null) {
            $this->error(404);
        }

        if (!$model->sites->filter(['storefrontid' => $site_model->storefrontid])->get() && $model->sites->all()){
            $this->error(404);
        }

        // Remove duplicate of index page
        if ($model->is_index && !empty($url)) {
            $this->error(404);
        }

        $this->setMetaBase(MetaType::PAGE);

        $this->setCanonical($model);
        $this->fetchBreadrumbs($model);

        echo $this->actionInternal($model);
    }

    protected function fetchBreadrumbs(Page $model): void
    {
        if (!$model->is_index) {
            /** @var Page[] $pages */
            $pages = $model->tree()->ancestors()->order(['level'])->all();
            foreach ($pages as $page) {
                $this->addTitle($page->name);
                $this->addBreadcrumb($page->name, $page->getAbsoluteUrl());
            }
            $this->addTitle($model->name);
            $this->addBreadcrumb($model->name, $model->getAbsoluteUrl());
        }
    }

    public function actionInternal(Page $model): string
    {
        $bread = new Breadcrumbs();

        $bread->add($model->name, $model->getAbsoluteUrl());

        $pager = new Pagination($model->getChildrenQuerySet(), [], new QuerySetDataSource());
        return $this->render($this->getView($model), [
            'model' => $model,
            'pager' => $pager,
            'breadcrumbs' => Xcart::app()->breadcrumbs->set($bread)
        ]);
    }
}
