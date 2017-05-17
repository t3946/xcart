<?php
namespace Modules\Product\Helpers;

use Modules\Product\Models\CategoryModel;
use Xcart\Helpers\ViewedRelatedProducts;

class ProductSortHelper
{
    /**
     * @param \Xcart\App\Orm\Manager|\Xcart\App\Orm\QuerySet $qs
     * @param CategoryModel $category
     *
     * @return \Xcart\App\Orm\Manager|\Xcart\App\Orm\QuerySet
     */
    public static function getOrderByRelevance($qs, $category)
    {
        $ta = $qs->getTableAlias();
        list($oldOrder, $orderOptions) = $qs->getQueryBuilder()->getOrder();
        

        if ($p_ids = (new ViewedRelatedProducts())->getRelated()) {
            $t_ids = [];

            $categories = CategoryModel::objects($category)->descendants(true)->valuesList(['pk'], true);

            foreach ($p_ids as $n => $product)
            {
                $push = false;
                $push_el = $product['productid'];

                if (in_array($push_el, $t_ids)) { continue; }
                if (count($t_ids) == 50) { break; }

                if (!empty($categories) && !empty($product['categoryid']))
                {
                    if (!empty(array_intersect($categories, $product['categoryid']))) {
                        $push = true;
                    }
                }

                if ($push) {
                    $t_ids[] = $push_el;
                }
            }

            if (!empty($t_ids))
            {
                array_unshift($oldOrder,
                              "IF(FIELD( {$ta}.productid, " . implode(',', $t_ids) . ") = 0,1,0)",
                              "FIELD( {$ta}.productid, " . implode(',', $t_ids) . ")"
                );
            }
        }

        $oldOrder[] = 'categories__order_by';
        $oldOrder[] = 'categories_link__orderby';
        $qs->with(['categories_link']) ->order($oldOrder);

        return $qs;
    }
}