<?php


namespace Modules\AMP\Controllers;

use Mindy\QueryBuilder\Expression;
use Modules\AMP\Models\AmpProductModel;
use Modules\Product\Models\CategoryModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Modules\AMP\Helpers\AMPHelper;

class AmpController extends FrontendController
{
    public function amp($id, $slug)
    {
        /** @var AmpProductModel $model */
        if ($model = AmpProductModel::objects()->get(['productid' => $id]) )
        {

            /** @var \Modules\Sites\Models\SiteModel $site */
            $site = Xcart::app()->getModule('Sites')->getSite();

//            if (!$model->sites->filter(['storefrontid__in' => [$site->storefrontid]])->count()) {
//                $this->redirect('/');
//            }

            $u_slug = $model->url->getSlugPart();
            if ($slug != $u_slug) {
                $this->redirect('amp:product', ['id' => $id, 'slug' => $u_slug],301);
            }

            $category = $model->getMainCategory();
            $this->setMetaTemplate('product:base', [
                'model' => $model,
                'category' => $category,
                'site' => $site,
            ]);


            $cids = explode('/',$category->categoryid_path);
            if ($cids) {
                $categories = CategoryModel::objects()
                                      ->filter(['categoryid__in' =>$cids])
                                      ->order([new Expression('FIELD(categoryid, '.implode(',', $cids).')')])
                                      ->all();
            }

            echo $this->render('product/amp.tpl', [
                'model' => $model,
                'category' => $category,
                'categories' =>$categories,
                'helper' => new AMPHelper($model),
            ]);

        } else {
            $this->redirect('/');
        }
    }
}
