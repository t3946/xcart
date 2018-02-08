<?php


namespace Modules\Amp\Controllers;

use Mindy\QueryBuilder\Expression;
use Modules\Amp\Models\AmpProductModel;
use Modules\Goods\Models\CategoryModel;
use Modules\Meta\Types\MetaType;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Modules\Amp\Helpers\AmpHelper;

class AmpController extends FrontendController
{
    public function index($id, $slug)
    {
        $this->redirect('amp:product', ['id' => $id, 'slug' => $slug]);
    }

    public function amp($id, $slug)
    {
        /** @var AmpProductModel $model */
        $model = AmpProductModel::objects()->get(['productid' => $id]);

        if (!$model) {
            $this->redirect('/', [], 301);
        }
        elseif ($model->forsale != "Y") {
            /** @var CategoryModel $category */
            $category = $model->getMainCategory();

            if ($category && $category->avail != 'Y') {
                $category = $category->getObjects()->ancestors()->filter(['avail' => 'Y'])->limit(1)->get();
            }

            if ($category) {
                $this->redirect($category->getAbsoluteUrl(), [], 301);
            }
            $this->redirect('/', [], 301);
        }

        if ( $model )
        {

            /** @var \Modules\Sites\Models\SiteModel $site */
            $site = Xcart::app()->getModule('Sites')->getSite();

            if ( ( !$site->isWork() ) || (!$model->sites->filter(['storefrontid__in' => [$site->storefrontid]])->count())  ){
                $this->redirect('/');
            }

            $u_slug = $model->clean_url->getSlugPart();
            if ($slug != $u_slug) {
                $this->redirect('amp:product', ['id' => $id, 'slug' => $u_slug],301);
            }

            $category = $model->getMainCategory();

            $this->setMetaBase(MetaType::PRODUCT, [
                'model' => $model,
                'category' => $category
            ]);


            $cids = explode('/',$category->categoryid_path);
            if ($cids) {
                $categories = CategoryModel::objects()
                                      ->filter(['categoryid__in' =>$cids])
                                      ->order([new Expression('FIELD(categoryid, '.implode(',', $cids).')')])
                                      ->all();
            }

            $this->display('product/amp.tpl', [
                'model' => $model,
                'category' => $category,
                'categories' =>$categories,
                'helper' => new AmpHelper($model),
            ]);

        }
    }
}
