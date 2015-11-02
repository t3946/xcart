<?php

//print_r($_POST);
//print_r($_GET);

if ($_POST["cidev_filter_mode"] == "load_more_products_SKU"){

/*
	$sku = $_POST["sku"];
	$_GET["sku"] = $sku;
	$mode = "search";
	$_GET["mode"] = $mode;
*/
	require "./top.inc.php";

	$search_all_website = true;
	$current_area = 'C';
	$page_pos = 500;

	require "./init.php";

}
else {
	require './auth.php';
}

if ($REQUEST_METHOD == 'POST')
 {

	if ($cidev_filter_mode == "load_more_products"){

		x_session_register("search_data");

		$ajax_navigation_page = $ajax_navigation_page_next;
		$search_data["products"]["page"] = $ajax_navigation_page;

		$remember_search_data_products = $search_data["products"];

		$mode = "search";
		$ajax_load_more_products = "Y";

		include $xcart_dir."/include/search.php";
	
		$search_data["products"] = $remember_search_data_products;
		x_session_save("search_data");

		$smarty->assign('ajax_navigation_page', $ajax_navigation_page);
		$smarty->assign('ajax_search_data', $search_data["products"]);
		$smarty->assign('show_next_products', 'Y');

		$smarty->assign("products",$products);

		func_display('customer/main/infinite_products.tpl', $smarty);
	}
	elseif ($cidev_filter_mode == "load_more_products_SKU"){

                x_session_register("search_data");

                $search_data = array();
                $search_data['products'] = array('by_sku' => 1,
                                     'forsale' => 'Y',
                                     'substring' => trim($sku)
                               );


                $ajax_navigation_page = $ajax_navigation_page_next;
                $search_data["products"]["page"] = $ajax_navigation_page;

                $remember_search_data_products = $search_data["products"];

                $mode = "search";
                $ajax_load_more_products = "Y";

                include $xcart_dir."/include/search.php";

                $search_data["products"] = $remember_search_data_products;
                x_session_save("search_data");

                $smarty->assign('ajax_navigation_page', $ajax_navigation_page);
                $smarty->assign('ajax_search_data', $search_data["products"]);
                $smarty->assign('show_next_products', 'Y');
                $smarty->assign("products",$products);

                func_display('customer/main/infinite_products.tpl', $smarty);
	}
	elseif ($cidev_filter_mode == "load_more_e_products"){

		x_session_register("e_search_data");
                x_session_register("search_data");

//$cat = 248;

		$e_search_data["substring"] = $e_search_data_substring;

                $ajax_navigation_page = $ajax_navigation_page_next;
                $search_data["products"]["page"] = $ajax_navigation_page;

                $remember_search_data_products = $search_data["products"];

                $mode = "search";
                $ajax_load_more_products = "Y";

                include $xcart_dir."/include/search.php";

                $search_data["products"] = $remember_search_data_products;
                x_session_save("search_data");


		$page = $ajax_navigation_page;
		include $xcart_dir."/elastic_search.php";

//func_print_r($e_search_data);

/*
		$smarty->assign('first_item', $e_search_data["products_per_page"]*($e_search_data["page"]-1)+1);
		$smarty->assign('last_item', $e_search_data["products_per_page"]*$e_search_data["page"]);
		$smarty->assign('total_items', $e_search_data["total"]);
*/

		$smarty->assign('products_template', $products_template);
		$smarty->assign('ajax_navigation_page', $page);
		$smarty->assign('show_next_products', 'Y');

		func_display('customer/main/infinite_products.tpl', $smarty);
	}
}
?>
