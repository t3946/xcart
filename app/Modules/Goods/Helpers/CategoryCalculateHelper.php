<?php
/**
 * Created by PhpStorm.
 * User: tsukasa
 * Date: 31.01.2018
 * Time: 16:10
 */

namespace Modules\Goods\Helpers;


use Mindy\QueryBuilder\Q\QOr;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;

class CategoryCalculateHelper
{
    public static function reCalcProductsCount(CategoryModel $model)
    {
        $ta = ProductModel::objects()->getQuerySet()->getTableAlias();
        $qor = new QOr(['group_root__raw' => " = `{$ta}`.`productid`", 'group_root__isnull' => true]);

        $model->global_product_count = ProductModel::objects()
            ->with(['categories'])
            ->filter([
                'categories__lft__gte' => $model->lft,
                'categories__rgt__lte' => $model->rgt,
                'categories__root' => $model->root,
            ])
            ->count();

        $model->active_product_count = ProductModel::objects()
            ->with(['categories'])
            ->filter([
                'forsale' => 'Y',
                'categories__lft__gte' => $model->lft,
                'categories__rgt__lte' => $model->rgt,
                'categories__root' => $model->root,
                'categories__avail' => 'Y',
                $qor
            ])
            ->count();

        $model->product_count = $model->products->filter(['forsale' => 'Y', $qor])->count();
        $model->subcategory_count = $model->objects()->descendants()->count();

        $model->save(['global_product_count', 'active_product_count', 'product_count', 'subcategory_count']);
    }

    public static function recalcParents(int $pk, bool $include_self = false)
    {
        /** @var CategoryModel $thisModel */
        if ($pk && $thisModel = CategoryModel::objects()->get(['pk' => $pk])) {

            if ($models = $thisModel->getObjects()->ancestors($include_self)->all()) {
                /** @var CategoryModel $model */
                foreach ($models as $model)
                {
                    self::reCalcProductsCount($model);
                }
            }
        }
    }

    public static function recalcChildrens(int $pk, bool $include_self = false)
    {
        /** @var CategoryModel $thisModel */
        if ($pk && $thisModel = CategoryModel::objects()->get(['pk' => $pk])) {

            if ($models = $thisModel->getObjects()->descendants($include_self)->all()) {
                /** @var CategoryModel $model */
                foreach ($models as $model)
                {
                    self::reCalcProductsCount($model);
                }
            }
        }
    }
}