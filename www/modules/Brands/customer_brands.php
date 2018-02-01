<?php

#
# customer_brands.php, random
#
if (!defined('XCART_START')) {
    header("Location: ../");
    die("Access denied");
}

if (!empty($active_modules['Multiple_Storefronts'])) {
    $brandidssql = "Select Distinct P.brandid from $sql_tbl[products_sf] PS inner join $sql_tbl[products] P ON P.productid = PS.productid and P.forsale = 'Y'  where PS.sfid = $current_storefront /*Group By B.brandid*/";
    $sf_join = "/* INNER JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid=$sql_tbl[brands].brandid*/";
    $sf_join = " INNER JOIN $sql_tbl[products] ON $sql_tbl[products].brandid = $sql_tbl[brands].brandid ";
    $sf_join = $sf_join . "INNER JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid ";
    $sf_condition = "/* AND $sql_tbl[brands_sf].sfid=$current_storefront*/";
    $sf_condition = " AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[products_sf].sfid = $current_storefront /*AND $sql_tbl[products].productid IS NOT NULL*/ ";
}
else {
    $sf_join = '';
    $sf_condition = '';
}

$brands_products = func_query_hash($brandidssql, 'brandid', false, true, true);
$brands_menu = count($brands_products);

if ($brands_menu > 0) {
    if ($config["Brands"]["brands_limit"] > 0)
        $smarty->assign("show_other_brands", $brands_menu > $config["Brands"]["brands_limit"]);

    $brands_menu = func_query("SELECT $sql_tbl[brands].brandid, $sql_tbl[brands].brand, /*$sql_tbl[brands].* */ /*IFNULL($sql_tbl[brands_lng].brand,*/ ($sql_tbl[brands].brand) as brand, /*IFNULL($sql_tbl[brands_lng].descr,*/ ( $sql_tbl[brands].descr) as descr"
        . " FROM $sql_tbl[brands] "
        . " /*LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$shop_language'*/"
        . " WHERE $sql_tbl[brands].avail = 'Y' AND $sql_tbl[brands].brandid IN ('" . implode("', '", array_keys($brands_products)) . "')"
        . " ORDER BY orderby, brand" . (($config["Brands"]["brands_limit"] > 0) ? " LIMIT " . $config["Brands"]["brands_limit"] : ""));

    $smarty->assign("brands_menu", $brands_menu);

    if ($config["Brands"]["brands_columns"] > 0) {
        $additional_count = ($config["Brands"]["brands_limit"] > 0 && $brands_menu > $config["Brands"]["brands_limit"]) ? 2 : 1;
        $smarty->assign("brands_per_column", ceil((count($brands_menu) + $additional_count) / $config["Brands"]["brands_columns"]));
        $smarty->assign('brands_column_percent', 100 / $config['Brands']['brands_columns']);
    }

    if (!empty($brands_menu) && is_array($brands_menu)) {
        $cidev_letters_arr = array();

        foreach ($brands_menu as $k => $v)
        {
            $first_letter = strtoupper($v["brand"]{0});

            if (preg_match('/^\d*$/', $first_letter) == true) {
                $first_letter = "0-9";
            }

            $brands_menu[$k]["first_letter"] = $first_letter;
            $cidev_letters_arr[] = $first_letter;

            $brands_menu[$k]["descr"] = trim($v["descr"]);

            $brandid_brands_info[$v["brandid"]] = $brands_menu[$k];
        }

        $cidev_letters_arr = array_values(array_unique($cidev_letters_arr));
        $smarty->assign("cidev_letters_arr", $cidev_letters_arr);
        $smarty->assign("brandid_brands_info", $brandid_brands_info);

        $cidev_brands_menu = $brands_menu;
        $smarty->assign("cidev_brands_menu", $cidev_brands_menu);

        $count_cidev_brands_menu = count($cidev_brands_menu);
        $count_cidev_letters_arr = count($cidev_letters_arr);
        $count_in_row = ($count_cidev_brands_menu) / 3;

        $smarty->assign("count_in_row", $count_in_row);

    }
}
