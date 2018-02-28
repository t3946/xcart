<?php
/**
 * Created by PhpStorm.
 * User: tsukasa
 * Date: 27.02.2018
 * Time: 19:11
 */

require "include/categories.php";
require "include/products.php";

use Mindy\QueryBuilder\Q\QOr;
use Modules\Core\Components\Profiler;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

if ($cat) {
    Profiler::getInstance()->addPoint();
    $location = $location ?: [];
    $subcategories = [];
    $objects_per_page = intval($config["Appearance"]["products_per_page"]);
    $sort = $sort ?? $config["Appearance"]["products_order"];

    $sort_fields = array(
        "title" => func_get_langvar_by_name("lbl_product_name"),
        "price" => func_get_langvar_by_name("lbl_price"),
        "orderby" => func_get_langvar_by_name("lbl_default")
    );

    $sort_direction = $sort_direction ? 1 : 0;
    $sort_string = $sort_direction ? '-' : '';


    switch ($sort)
    {
        case "productcode":
            $sort_string .= "productcode";
            break;
        case "orderby":
            $sort_string .= "categories__through__orderby";
            break;
        case "quantity":
            $sort_string .= "avail";
            break;
        case "price":
            $sort_string .= "quick_prices__price";
            break;
        case "title":
        default:
            $sort_string .= "product";
    }


    /** @var CategoryModel $oCategory */
    $oCategory = CategoryModel::objects()->get(['pk' => $cat]);

    if (!$oCategory->isRoot()) {
        /** @var CategoryModel $model */
        foreach ($oCategory->getObjects()->parents()->all() as $model) {
            $location[] = [
                $model->getFrontendName(),
                $model->getAbsoluteUrl(),
            ];
        }
    }

    $location[] = [$oCategory->getFrontendName()];


    if (!$oCategory->isLeaf()) {
        foreach ($oCategory->getObjects()->children()->filter(['active_product_count__gt' => 0]) as $model) {
            $subcategories[] = $model->getAttributes();
        }
    }

    $qs = ProductModel::objects();
    $ta = $qs->getTableAlias();

    $qs->filter([
        'forsale' => 'Y',

        new QOr([
            ['group_root__isnull' => true],
            ['group_root__raw' => " = `{$ta}`.`productid`"]
        ]),

        'categories__lft__gte' => $oCategory->lft,
        'categories__rgt__lte' => $oCategory->rgt,
        'categories__root' => $oCategory->root,
    ]);

    $qs->order([$sort_string]);

    $pager = new Pagination($qs, ['pageSize' => $objects_per_page, 'pageKey' => 'p'], new QuerySetDataSource());
    Profiler::getInstance()->addPoint();
    $products = [];

    Profiler::getInstance()->addPoint();
    $product_models = $pager->paginate();

    Profiler::getInstance()->addPoint();
    func_products_globals($product_models);
    Profiler::getInstance()->addPoint();
    /** @var ProductModel $model */
    foreach ($product_models as $model) {
        $t = func_product_prepare($model);
        $t['oProduct'] = $model;

        $products[] = $t;
    }
    Profiler::getInstance()->addPoint();

    $current_category = func_get_category_data($cat);

    Profiler::getInstance()->addPoint();
    $smarty->assign("current_category", $current_category);
    $smarty->assign("subcategories", $subcategories);
    $smarty->assign("qsubcats", count($subcategories));
    $smarty->assign("products", $products);

    $smarty->assign("sort", $sort);
    $smarty->assign('sort_direction', $sort_direction);
    $smarty->assign("sort_fields", $sort_fields);

    $smarty->assign('ajax_navigation_page', $pager->getPage());
    $smarty->assign('first_item', 1);
    $smarty->assign('last_item', $pager->getPagesCount());
    $smarty->assign('total_items', $qs->count() );
//    $smarty->assign('show_next_products', $pager->getPage() < $pager->getPagesCount() ? 'Y' : 'N');


    $smarty->assign("main","catalog");
    $smarty->assign("location", $location);
    $smarty->assign("bench_name", "home.php");
    $smarty->assign("cidev_subcategories_products_count", array_map(function($category){
        return [
            'categoryid' => $category['categoryid'],
            'supplemental_category' => $category['supplemental_category'],
            'count_products' => $category['active_product_count'],
        ];
        },$subcategories));


    $smarty->assign("search_prefilled", [
        'sort_direction' => $sort_direction,
        'sort_field' => $sort,
    ]);

    func_display("customer/home.tpl",$smarty);

}


Profiler::getInstance()->addPoint();
Profiler::getInstance()->stop('trace');
