<?php

//require './auth.php';  #uses xid

#
## ALWAYS USE IT if you do not require auth.php
###
define('AREA_TYPE', 'C'); // if add this, then xid is used.

define('x_session_save_to_db__do_not_use', 'Y');

require "./top.inc.php";
require "./init.php"; #uses xid.X

include_once $xcart_dir."/include/class/classBrands.php";
include_once $xcart_dir."/include/class/classElasticSearch.php";

$current_area="C";
###
##
#

if ($REQUEST_METHOD == 'POST') {

//	x_session_start($$XCART_SESSION_NAME);

	x_load("product");

#
##
###
//	/* x_session_register("cart"); */ <--- You need to use $XCART_SESSION_VARS["cart"] if you want just READ it 

	x_session_register("cart");
###
##
#

	$productids = array();
	if (!empty($productid)){
		$productids[] = $productid;
	}


//	if (!empty($XCART_SESSION_VARS["cart"]["products"]) && is_array($XCART_SESSION_VARS["cart"]["products"])){
	if (!empty($cart["products"]) && is_array($cart["products"])){
		foreach ($cart["products"] as $k => $v){
			$productids[] = $v["productid"];
		}
	}

	$sGoogleAnaliticsParam = "";

	if (
		$section_name == "products_also_bought_with_this_product"  || 
		$section_name == "related_products"  || 
		$section_name == "recently_viewed_products"
	){

		$productids = implode("','", $productids);

		if ($section_name == "products_also_bought_with_this_product"){
			$p_query = "
select RO.related_resource_id as needed_resource_id
from xcart_cidev_related_objects RO
        inner join xcart_products P ON P.productid = RO.related_resource_id and P.forsale = 'Y'
where RO.resource_id = '$productid' and RO.resource_type = 'OP' and RO.related_resource_type = 'P'  and RO.related_resource_id NOT IN ('$productids')
Order By RO.related_resource_orderby
limit 20";
		}
		elseif ($section_name == "recently_viewed_products"){

			$meta_id = func_query_first_cell("SELECT id FROM xcart_cidev_surf_meta WHERE sessid='".$$XCART_SESSION_NAME."'");

			$p_query = "
select SP.resource_id as needed_resource_id
from xcart_cidev_surf_path SP
        inner join xcart_products P ON P.productid = SP.resource_id and P.forsale = 'Y'
where SP.meta_id = '$meta_id' and SP.resource_type = 'P' and SP.resource_id NOT IN ('$productids')
Group By SP.resource_id
Order By SP.`position` desc";
		}
		elseif ($section_name == "related_products"){

        	        $avail_condition = "";
	                if ($config["General"]["unlimited_products"] == "N" && $config["General"]["disable_outofstock_products"] == "Y") {
                	        $avail_condition = "AND $sql_tbl[products].avail > 0";
        	        }

	                $p_query = "SELECT $sql_tbl[products].productid as needed_resource_id FROM $sql_tbl[product_links], $sql_tbl[products] WHERE $sql_tbl[products].productid=$sql_tbl[product_links].productid2 AND $sql_tbl[product_links].productid1='$productid' AND $sql_tbl[products].forsale = 'Y' AND $sql_tbl[products].productid NOT IN ('$productids') $avail_condition GROUP BY $sql_tbl[products].productid ORDER BY $sql_tbl[product_links].orderby, product";
		}

		$pids = func_query($p_query);

		switch ($section_name) {
			case 'products_also_bought_with_this_product': $sGoogleAnaliticsParam = 'customer_also_bought_carousel';
				break;
			case 'related_products': $sGoogleAnaliticsParam = 'related_products_carousel';
				break;
			case 'recently_viewed_products': $sGoogleAnaliticsParam = 'recently_viewed_carousel';
				break;
		}

	}
	elseif ($section_name == "similar_products"){

		$classElastic = new classElasticSearch($config["ElasticSearch_options"],$site_domain);
		$classElastic->setSource("*._id");
		$classElastic->setType("product");
		$classElastic->setSize(30);
		$classElastic->setProductId($productid);
		x_session_register("variant_id_for_point9");
		$variant_id = $variant_id_for_point9;
		if ($is_robot == 'Y' || defined("IS_ROBOT")) {
			$variant_id = Get_AB_Variant(9);
		}
		switch ($variant_id) {
			case 0:
				$similar_productids = func_query_first_cell("SELECT similar_productids FROM $sql_tbl[products] WHERE productid='$productid'");

				if (!empty($similar_productids)){

					$similar_productids_arr = explode(",", $similar_productids);

					if (!empty($similar_productids_arr) && is_array($similar_productids_arr)){
						foreach ($similar_productids_arr as $k => $v){

							$needed_resource_id = trim($v);
							if (!in_array($needed_resource_id, $productids)){
								$pids[$k]["needed_resource_id"] = $needed_resource_id;
							}
						}
					}
				}
				$sGoogleAnaliticsParam = 'similar_products_carousel';
			break;
			case 1:
				$classElastic->setSearchQuery($classElastic->getQuerySimilarProductsBrands());
				$res = $classElastic->query();
				foreach ($res["hits"]["hits"] as $key => $sValue){
					if ($sValue["_id"] != $productid) {
						$pids[]["needed_resource_id"] = $sValue["_id"];
					}
				}
				$sGoogleAnaliticsParam = 'similar_products_all_carousel';
				break;
			case 2:
				$classBrands = new classBrands();
				$aBrand = $classBrands->getBrandByProductId($productid);
				$classElastic->setSearchQuery($classElastic->getQuerySimilarProductsBrands($aBrand['brand']));
				$res = $classElastic->query();
				foreach ($res["hits"]["hits"] as $key => $sValue){
					if ($sValue["_id"] != $productid) {
						$pids[]["needed_resource_id"] = $sValue["_id"];
					}
				}
				unset($aBrand);
				$sGoogleAnaliticsParam = 'similar_products_other_brands_carousel';
				break;

		}

		unset($classElastic);


	}



########################
//if ($section_name == "products_also_bought_with_this_product"){
//$pids="";
//}
########################

//func_print_r($pids);

	if (!empty($pids)){
		$products = array();

		foreach ($pids as $k => $v){

			$productid = $v["needed_resource_id"];

			$product_info = func_select_product($productid, 0, false);

			if (!empty($product_info)){

				$product_info["product"] = str_replace("'", "&#39;", $product_info["product"]);

				$products[] = $product_info;

			} // if (!empty($product_info))
		} // foreach ($pids as $k => $v)
	} // if (!empty($pids))


	if (!empty($products)){

		$count_products = count($products);

		$products_str = '{"items": [';
		foreach ($products as $k => $v){

			$products_str .= '{';
				$products_str .= '"productid": "'.$v["productid"].'",';
				$products_str .= '"clean_url": "'.$v["clean_url"].'",';
				$products_str .= '"src": "'.$v["tmbn_url"].'",';
				$products_str .= '"price": "'.$v["price"].'",';

				$products_str .= '"category": "'.func_add_slashes($v["category"]).'",';
				$products_str .= '"brand": "'.func_add_slashes($v["brand"]).'",';

				$products_str .= '"product": "'.func_add_slashes($v["product"]).'",';

				$N_key = $k + 1;
				$products_str .= '"N_key": "'.$N_key.'",';
				if (!empty($sGoogleAnaliticsParam)) $products_str .= '"ga_param": "'.$sGoogleAnaliticsParam.'",';

				$products_str .= '"title": "'.addslashes($v["product"]).'"';
			$products_str .= '}';

			if (($count_products -1)!= $k) $products_str .= ',';
		}
		$products_str .= ']}';

		echo $products_str;

	}

} // if ($REQUEST_METHOD == 'POST')
?>
