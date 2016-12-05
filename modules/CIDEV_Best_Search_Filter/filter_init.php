<?php

if ($gPage_status['match'] && $gPage_status['type'] == 'brand') {
    $b_ids = [$gPage_status['page_id'] => "Y"];
}

$aFilterSelected = null;
if (!empty($fv_ids))
    $aFilterSelected = array_keys($fv_ids);

if (!empty($f_id)) {
    $oFilter = \Xcart\Filter::model(['f_id' => $f_id]);
} else $oFilter = \Xcart\Filter::model();

$oFilter->setStoreFront(\Xcart\StoreFront::model(['storefrontid' => $current_storefront]))->
setCategory(\Xcart\Category::model(['categoryid' => $cat]));
if (!empty($aFilterSelected)) {
    $oFilter->setFilterValuesSelected(
        \Xcart\FilterValue::model()->findAll(\Xcart\SQLBuilder::getInstance()->addCondition('fv_id IN (' . implode(',', $aFilterSelected) . ')'))
    );
}
if (!empty($p_ids)) {
    $aPriceRange = array_keys($p_ids);
    $oFilter->setPriceRange(reset($aPriceRange));
}

if (!empty($b_ids)) {
    $oFilter->setBrandSelected(
        \Xcart\Brand::model()->findAll(\Xcart\SQLBuilder::getInstance()->addCondition('brandid IN (' . implode(',', array_keys($b_ids)) . ')'))
    );
}

$aFilterValues = $oFilter->getMoreBrands();
$smarty->assign("aBrandFilters", $aFilterValues);

if ($gPage_status['match'] && $gPage_status['type'] == 'brand')
{
    $selected_brandids = [];
    foreach ($aFilterValues as $aFilterValue) {
        $selected_brandids[] = $aFilterValue->getBrandId();
    }

    $smarty->assign('filter_selected_brandids', $selected_brandids);
}



$search_data["products"]["search_in_subcategories"] = "Y";
$filter_selected_and_found_brands = "";
$cidev_filters_tree_sorted = "";
$filter_found_fv_ids_count = "";


if ($f_mode == "clear") {
    $sorted_filter_values_id = "";
    $filter_selected_brandids = "";
    $filter_prices = "";
    $filter_min_price_selected = "";
    $filter_max_price_selected = "";
    /* x_session_save("filter_min_price_selected");
     x_session_save("filter_max_price_selected");
     x_session_save("filter_prices");
     x_session_save("filter_selected_brandids");
     x_session_save("sorted_filter_values_id");*/
    func_header_location($xcart_web_dir . "/" . $dispatched_request . "/");
} elseif ($f_mode == "f_search") {

    # Filter attributes
    if (!empty($fv_ids) && is_array($fv_ids)) {
        $sorted_filter_values_id = array();
        if (!empty($cidev_filters_tree)) {
            foreach ($cidev_filters_tree as $k => $v) {
                if (!empty($v["filter_values"]) && is_array($v["filter_values"])) {
                    foreach ($v["filter_values"] as $kk => $filter_value) {
                        foreach ($fv_ids as $fv_id => $vvv) {
                            if ($vvv == "Y" && $fv_id == $filter_value["fv_id"] && $filter_value["fv_active"] == "Y") {
                                $sorted_filter_values_id[$v["f_id"]][] = $fv_id;
                            }
                        }
                    }
                }
            }
        }

    } else {
        $sorted_filter_values_id = "";
    }

    if ((!empty($p_ids) && is_array($p_ids))) {
        $filter_min_price_selected = $filter_max_price_selected = null;
        foreach ($p_ids as $k => $v) {
            if ($v=='Y') {
                list($minprice, $max_price) = explode('_',$k);
                $filter_min_price_selected = (is_null($filter_min_price_selected)?$minprice:min($filter_min_price_selected, $minprice));
                $filter_max_price_selected = max($filter_max_price_selected,$max_price);
            }
        }
    }


    # Brands
    if ((!empty($b_ids) && is_array($b_ids))) {
        $filter_selected_brandids = array();
        foreach ($b_ids as $k => $v) {
            $filter_selected_brandids[] = $k;
        }
        $count_filter_selected_brandids = count($filter_selected_brandids);
    } else {
        $filter_selected_brandids = "";
    }
    $smarty->assign('filter_selected_brandids',$filter_selected_brandids);


    $cidev_redirect_url = $xcart_web_dir . "/" . $dispatched_request . "/";

    if ($count_filter_selected_brandids == "1" && $cidev_clean_url_type == "C") {
        $b_id = $filter_selected_brandids[0];
        $brand_clean_url = func_query_first_cell("SELECT clean_url FROM  $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$b_id'");
        $cidev_redirect_url .= $brand_clean_url . "/";
    }

    //func_header_location($cidev_redirect_url);
}


if ($cat_with_one_brand_filter != 'Y') {
    if (!empty($filter_selected_brandids)) {
        $count_filter_selected_brandids = count($filter_selected_brandids);
        if ($count_filter_selected_brandids == "1" && $cidev_clean_url_type == "C") {
            $b_id = $filter_selected_brandids[0];
            $brand_clean_url = func_query_first_cell("SELECT clean_url FROM  $sql_tbl[clean_urls] WHERE resource_type='M' AND resource_id='$b_id'");
            $cidev_redirect_url = $xcart_web_dir . "/" . $dispatched_request . "/" . $brand_clean_url . "/";
            if (!empty($_GET)) {
                $aParams = $_GET;
                unset($aParams['request_uri']);
                $cidev_redirect_url .= '?'.urldecode(http_build_query($aParams));
            }
            func_header_location($cidev_redirect_url);
        }
    }
} else {
    if (empty($filter_selected_brandids) && !empty($brandid_in_url)) {
        $filter_selected_brandids[0] = $brandid_in_url;
        //x_session_save("filter_selected_brandids");
    }
}


$search_data['products']['sorted_filter_values_id'] = $sorted_filter_values_id;
$search_data['products']['filter_selected_brandids'] = $filter_selected_brandids;
$search_data['products']['filter_prices'] = $filter_prices;


if ($filter_max_price_selected > 0) {
    $search_data['products']['price_min'] = $filter_min_price_selected;
    $search_data['products']['price_max'] = $filter_max_price_selected;
} else {
    $search_data['products']['price_min'] = "";
    $search_data['products']['price_max'] = "";
}


if (!empty($filter_selected_brandids) && is_array($filter_selected_brandids)) {

    $imploded_brandids = implode(",", $filter_selected_brandids);
    $filter_selected_brands = func_query("SELECT brandid, brand FROM $sql_tbl[brands] WHERE brandid IN ($imploded_brandids) ORDER BY brand");

    if (!empty($filter_selected_brands) && is_array($filter_selected_brands)) {
        foreach ($filter_selected_brands as $k => $v) {
            $filter_selected_brands[$k]["selected"] = 'Y';
        }
    }
}

if (!isset($sort))
    $sort = $config["Appearance"]["products_order"];
if (!isset($sort_direction))
    $sort_direction = 0;

if (isset($_GET['p']) && is_numeric($_GET['p'])) {
    $first_page = 0;
    $objects_per_page = intval($_GET['p'])*intval($config["Appearance"]["products_per_page"]);
    $smarty->assign('ajax_navigation_page', intval($_GET['p']));
}

$mode = "search";
include $xcart_dir . "/include/search.php";


#
##
###


    /*
     *https://s3stores.teamwork.com/tasks/6258406
     */
    $bOptimisedSelectBrands = true;
    if ($bOptimisedSelectBrands) {
        if (isset($current_category) && is_array($current_category) && !empty($current_category) && isset($current_category['parentid'])
            && $current_category['parentid'] == 0 && isset($current_category['subcategory_count']) && $current_category['subcategory_count'] > 0) {
            if (!empty($cidev_filters_tree) && is_array($cidev_filters_tree)){
                unset($cidev_filters_tree);
            }
        }
    }

    $search_query_count_NEW = str_replace("  ", " ", $search_query_count_NEW);
//func_print_r($cidev_filters_tree);
    if (!empty($cidev_filters_tree) && is_array($cidev_filters_tree)) {
        foreach ($cidev_filters_tree as $k => $v) {
            if (!empty($v["filter_values"]) && is_array($v["filter_values"])) {
                foreach ($v["filter_values"] as $kk => $tree_filter_values) {

                    if (!empty($sorted_filter_values_id) && is_array($sorted_filter_values_id)) {
                        foreach ($sorted_filter_values_id as $k_sorted => $fv_ids) {
                            if (!empty($fv_ids) && is_array($fv_ids)) {
                                foreach ($fv_ids as $fv_id) {
                                    if ($fv_id == $tree_filter_values["fv_id"]) {
                                        $cidev_filters_tree[$k]["filter_values"][$kk]["selected"] = 'Y';
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($filter_found_fv_ids) && is_array($filter_found_fv_ids)) {
                        foreach ($filter_found_fv_ids as $fv_id) {
                            if ($fv_id == $tree_filter_values["fv_id"]) {
                                $cidev_filters_tree[$k]["filter_values"][$kk]["found"] = 'Y';

                                if ($cidev_filters_tree[$k]["filter_values"][$kk]["selected"] == 'Y') {
                                    $cidev_filters_tree[$k]["filter_values"][$kk]["selected_and_found"] = 'Y';
                                }
                            }
                        }
                    }
                }
            }
        }


###
        /*
                $seach_query_fv_count = $search_query_count_NEW;
                $seach_query_fv_count_arr = explode("GROUP BY", $seach_query_fv_count);
        //print($seach_query_fv_count);
                foreach ($cidev_filters_tree as $k => $v){
                        if (!empty($v["filter_values"]) && is_array($v["filter_values"])){
                                foreach ($v["filter_values"] as $kk => $tree_filter_values) {
                        if ($tree_filter_values["found"] == "Y" || $tree_filter_values["selected"] == "Y"){

                        $filter_fv_sub_query = "$sql_tbl[cidev_filter_products].fv_id='".$tree_filter_values["fv_id"]."'";
        //                $seach_query_fv_count = $seach_query_fv_count_arr[0] . " AND " . $filter_fv_sub_query . " GROUP BY " . $seach_query_fv_count_arr[1];
                $seach_query_fv_count = $seach_query_fv_count_arr[0] . " AND " . $filter_fv_sub_query . " GROUP BY $sql_tbl[products].productid";
        //print("<br /><br />");
        //print($seach_query_fv_count);

                        $seach_query_fv_count_products = db_query($seach_query_fv_count);
                        $filter_count_fv = db_num_rows($seach_query_fv_count_products);
                        db_free_result($seach_query_fv_count_products);
                        $cidev_filters_tree[$k]["filter_values"][$kk]["count_products"] = $filter_count_fv;


                        }
                                }
                        }
            }
        */
###


        foreach ($cidev_filters_tree as $k => $v) {
            if (!empty($v["filter_values"]) && is_array($v["filter_values"])) {

                $tmp_selected_fv_arr = array();
                $tmp_without_selected_fv_arr = $v["filter_values"];

                foreach ($v["filter_values"] as $kk => $tree_filter_values) {
                    if ($tree_filter_values["selected"] == "Y") {
                        $tmp_selected_fv_arr[] = $tree_filter_values;
                        unset($tmp_without_selected_fv_arr[$kk]);
                    }
                }

                $show_N_fvalues = 5;
                if (!empty($tmp_selected_fv_arr) && is_array($tmp_selected_fv_arr)) {

                    $count_tmp_selected_fv_arr = count($tmp_selected_fv_arr);
                    if ($count_tmp_selected_fv_arr > $show_N_fvalues) {
                        $show_N_fvalues = $count_tmp_selected_fv_arr;
                    }

                    $new_filter_values = array();

                    foreach ($tmp_selected_fv_arr as $kn => $vn) {
                        $new_filter_values[] = $vn;
                    }

                    if (!empty($tmp_without_selected_fv_arr) && is_array($tmp_without_selected_fv_arr)) {
                        foreach ($tmp_without_selected_fv_arr as $kn => $vn) {
                            $new_filter_values[] = $vn;
                        }
                    }

                    $cidev_filters_tree[$k]["filter_values"] = $new_filter_values;
                }

//$show_N_fvalues = 1;

                $cidev_filters_tree[$k]["show_N_fvalues"] = $show_N_fvalues;
            }
        }

        $cidev_filters_tree_sorted = $cidev_filters_tree;
        $smarty->assign("cidev_filters_tree_sorted", $cidev_filters_tree_sorted);
    }
    //x_session_save("cidev_filters_tree_sorted");
    //x_session_save("filter_found_fv_ids_count");

//func_print_r($sorted_filter_values_id);
//func_print_r($cidev_filters_tree_sorted);


    if (!empty($filter_found_brands) && is_array($filter_found_brands)) {

        $filter_selected_and_found_brands = $filter_found_brands;

        if (!empty($filter_selected_brands) && is_array($filter_selected_brands)) {
            foreach ($filter_selected_brands as $k => $v) {
                $the_same_brand_is_found = false;
                foreach ($filter_selected_and_found_brands as $kk => $vv) {
                    if ($v["brandid"] == $vv["brandid"]) {
                        $the_same_brand_is_found = true;
                        $filter_selected_and_found_brands[$kk]["selected_and_found"] = 'Y';
                        $filter_selected_and_found_brands[$kk]["selected"] = 'Y';
                    }
                }

                if (!$the_same_brand_is_found) {
                    $filter_selected_and_found_brands[] = $v;
                }
            }
        }

    } else {
        if (!empty($filter_selected_brands) && is_array($filter_selected_brands)) {
            $filter_selected_and_found_brands = $filter_selected_brands;
        }
    }



    $filter_prices_old = $filter_prices;
    $filter_prices = "";




    if ($filter_max_price_selected > 0) {
        $filter_min_price = $filter_min_price_selected;
        $filter_max_price = $filter_max_price_selected;
    } else {
        $search_query_prices = $search_query_count_NEW;
        $search_query_prices = preg_replace('/SELECT(.*?)FROM/is', "SELECT xcart_pricing.price FROM", $search_query_prices);
        $search_query_max_price = $search_query_prices . " ORDER BY xcart_pricing.price DESC LIMIT 1";
        $filter_max_price = func_query_first_cell($search_query_max_price);
        $filter_min_price = 0;
    }

    if ($filter_max_price > 0) {
        $filter_range_price = ($filter_max_price - $filter_min_price) / 5;

        $start_price_flag = false;
        foreach ($filter_price_ranges as $k => $v) {
            $next_k = $k + 1;
            if ($filter_range_price > $v) {
                $filter_range_step_price = $filter_price_ranges[$next_k];
            }
        }

        for ($i = 0; $i < 5; $i++) {
            $min_price = $i * $filter_range_step_price + $filter_min_price;
            $max_price = $min_price + $filter_range_step_price;

            $filter_prices[$min_price.'_'.$max_price]["min_price"] = $min_price;
            $filter_prices[$min_price.'_'.$max_price]["max_price"] = $max_price;

            if (!empty($filter_prices_old) && is_array($filter_prices_old)) {
                if ($filter_prices_old[$min_price.'_'.$max_price]["min_price"] == $min_price && $filter_prices_old[$min_price.'_'.$max_price]["max_price"] == $max_price) {
                    $filter_prices[$min_price.'_'.$max_price]["selected"] = $filter_prices_old[$min_price.'_'.$max_price]["selected"];
                }
            }

            if ($max_price > $filter_max_price) {
                break;
            }
        }
    }



    if (!empty($filter_prices)) {

        if (strpos($search_query_count_NEW, '((xcart_pricing.price >=') !== false) {
            $filter_price_is_checked = true;
        } else {
            $search_query_prices_range_arr = explode("GROUP BY", $search_query_count_NEW);
            $filter_price_is_checked = false;
        }

        foreach ($filter_prices as $k => $v) {

            $filter_price_sub_query = "((xcart_pricing.price >='" . $v["min_price"] . "' AND xcart_pricing.price <='" . $v["max_price"] . "'))";

            if ($filter_price_is_checked) {
                $search_query_prices_range = $search_query_count_NEW;
                $search_query_prices_range = preg_replace('/\(\(xcart_pricing.price >=(.*?)\)\)/is', $filter_price_sub_query, $search_query_prices_range);
            } else {
                $search_query_prices_range = $search_query_prices_range_arr[0] . " AND " . $filter_price_sub_query . " GROUP BY " . $search_query_prices_range_arr[1];
            }

            $search_query_prices_range_products = db_query($search_query_prices_range);
            $count_search_query_prices_range_products = db_num_rows($search_query_prices_range_products);
            db_free_result($search_query_prices_range_products);
            $filter_prices[$k]["count_products"] = $count_search_query_prices_range_products;
        }
        $smarty->assign("filter_prices", $filter_prices);

        $smarty->assign("filter_min_price_selected", $filter_min_price_selected);
        $smarty->assign("filter_max_price_selected", $filter_max_price_selected);
    }
    $smarty->assign("fv_ids_arr", $fv_ids_arr);

