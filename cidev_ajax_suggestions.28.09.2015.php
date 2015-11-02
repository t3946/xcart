<?php

require './auth.php';

if ($REQUEST_METHOD == 'POST') {

	if (
		$section_name == "products_also_bought_with_this_product"  || 
		$section_name == "recently_viewed_products"
	){

		x_load("product");

		$productids = array();

		if (!empty($productid)){
			$productids[] = $productid;
		}

		x_session_register("cart");
		if (!empty($cart["products"]) && is_array($cart["products"])){
			foreach ($cart["products"] as $k => $v){
				$productids[] = $v["productid"];
			}
		}


		$productids = implode("','", $productids);

		if ($section_name == "products_also_bought_with_this_product"){
			$igor_query = "
select RO.related_resource_id as needed_resource_id
from xcart_cidev_related_objects RO
        inner join xcart_products P ON P.productid = RO.related_resource_id and P.forsale = 'Y'
where RO.resource_id = '$productid' and RO.resource_type = 'OP' and RO.related_resource_type = 'P'  and RO.related_resource_id NOT IN ('$productids')
Order By RO.related_resource_orderby";
		}
		elseif ($section_name == "recently_viewed_products"){

			$meta_id = func_query_first_cell("SELECT id FROM xcart_cidev_surf_meta WHERE sessid='".$$XCART_SESSION_NAME."'");

			$igor_query = "
select SP.resource_id as needed_resource_id
from xcart_cidev_surf_path SP
        inner join xcart_products P ON P.productid = SP.resource_id and P.forsale = 'Y'
where SP.meta_id = '$meta_id' and SP.resource_type = 'P' and SP.resource_id NOT IN ('$productids')
Group By SP.resource_id
Order By SP.`position` desc";
		}


		$pids = func_query($igor_query);

//func_print_r($pids);

		if (!empty($pids)){
			$products = array();

			foreach ($pids as $k => $v){

				$productid = $v["needed_resource_id"];

				$product_info = func_select_product($productid, 0, false);

//func_print_r($product_info, $productid);
				if (!empty($product_info)){

					$product_feed_enabled = func_query_first_cell("SELECT d_enable_feed FROM $sql_tbl[manufacturers] WHERE manufacturerid='$product_info[manufacturerid]'");

					if ($product_feed_enabled == "Y" && empty($product_info["is_variants"]) && $product_info["r_avail"] <= 0){

					        if ($product_info["mult_order_quantity"] == "Y" && $product_info["min_amount"] > 1){
					                $product_info["price"] = $product_info["taxed_price"] = func_query_first_cell("SELECT price FROM $sql_tbl[pricing] WHERE productid='$product_info[productid]' AND quantity <= '$product_info[min_amount]' ORDER BY quantity DESC LIMIT 1");
					        }

					        $new_notify_in_stock_price = func_decreased_price($product_info["cost_to_us"], $product_info["taxed_price"], $product_info["map_price"]);
					        $product_info["new_notify_in_stock_price"] = $new_notify_in_stock_price;

#
##
						$product_info["price"] = $product_info["new_notify_in_stock_price"];
##
#

					}

					$product_info["product"] = str_replace("'", "&#39;", $product_info["product"]);

					$products[] = $product_info;
				}
			} // foreach ($pids as $k => $v){

		}

		if (!empty($products)){

			$count_products = count($products);

			$products_str = '{"items": [';
			foreach ($products as $k => $v){

				$products_str .= '{';
					$products_str .= '"productid": "'.$v["productid"].'",';
					$products_str .= '"clean_url": "'.$v["clean_url"].'",';
					$products_str .= '"src": "'.$v["tmbn_url"].'",';
					$products_str .= '"price": "'.$v["price"].'",';
					$products_str .= '"title": "'.addslashes($v["product"]).'"';
				$products_str .= '}';

				if (($count_products -1)!= $k) $products_str .= ',';
			}
			$products_str .= ']}';

			echo $products_str;

//			$smarty->assign("products", $products);



		} 
		else {
/*
$products_str = '{
    "items": [
        {
            "productid": "123",
            "clean_url": "123",
            "src": "../_shared/img/img1.jpg",
            "title": "Image 1"
        },
        {
            "productid": "123",
            "clean_url": "123",
            "src": "../_shared/img/img2.jpg",
            "title": "Image 2"
        },
        {
            "productid": "123",
            "clean_url": "123",
            "src": "../_shared/img/img3.jpg",
            "title": "Image 3"
        },
        {
            "productid": "123",
            "clean_url": "123",
            "src": "../_shared/img/img4.jpg",
            "title": "Image 4"
        },
        {
            "productid": "123",
            "clean_url": "123",
            "src": "../_shared/img/img5.jpg",
            "title": "Image 5"
        },
        {
            "productid": "123",
            "clean_url": "123",
            "src": "../_shared/img/img6.jpg",
            "title": "Image 6"
        }
    ]
}';

echo $products_str;
*/
		}



//func_print_r($products);

//		func_display('customer/main/ajax_carousel_products.tpl', $smarty);
	}
}
?>
