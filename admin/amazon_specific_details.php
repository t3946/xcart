<?php
define('USE_TRUSTED_POST_VARIABLES',1);
define('USE_TRUSTED_SCRIPT_VARS',1);
$trusted_post_variables = array("add_inq_subject");

require "./auth.php";
require $xcart_dir."/include/security.php";

if (empty($productid)){
	die("Empty productid");
}

$product = func_query_first_cell("SELECT product FROM $sql_tbl[products] WHERE productid='$productid'");

$location[] = array($product, "product_modify.php?productid=$productid");
$location[] = array("Amazon specific details", "");

if ($REQUEST_METHOD == 'POST'){

        if ($mode == "update"){

		if (empty($id)){
	
			$id = func_query_first_cell("SELECT id FROM $sql_tbl[products_amz_fields] WHERE productid='$productid'");

			 if (empty($id)){
				db_query("INSERT INTO $sql_tbl[products_amz_fields] (productid) VALUES ('$productid')");
				$id = db_insert_id();
			}
		}

		$query_data = array(
                        "amazon_product" => $amazon_product,
                        "amazon_bulletpoint1" => $amazon_bulletpoint1,
                        "amazon_bulletpoint2" => $amazon_bulletpoint2,
                        "amazon_bulletpoint3" => $amazon_bulletpoint3,
                        "amazon_bulletpoint4" => $amazon_bulletpoint4,
                        "amazon_bulletpoint5" => $amazon_bulletpoint5,
                        "amazon_searchterms1" => $amazon_searchterms1,
                        "amazon_searchterms2" => $amazon_searchterms2,
                        "amazon_searchterms3" => $amazon_searchterms3,
                        "amazon_searchterms4" => $amazon_searchterms4,
                        "amazon_searchterms5" => $amazon_searchterms5,
                        "amazon_product_type" => $amazon_product_type,
                        "amazon_category_item_type" => $amazon_category_item_type
		);

		func_array2update("products_amz_fields", $query_data, "id = '$id'");

                $top_message["content"] = 'Done.';
                $top_message["type"] = "I";
                func_header_location("amazon_specific_details.php?productid=$productid");
        }
}

$amazon_specific_details = func_query_first("SELECT * FROM $sql_tbl[products_amz_fields] WHERE productid='$productid'");
if (empty($amazon_specific_details)){
	$amazon_specific_details= array(
                        "id" => '0',
                        "productid" => $productid,
                        "amazon_product" => '',
                        "amazon_bulletpoint1" => '',
                        "amazon_bulletpoint2" => '',
                        "amazon_bulletpoint3" => '',
                        "amazon_bulletpoint4" => '',
                        "amazon_bulletpoint5" => '',
                        "amazon_searchterms1" => '',
                        "amazon_searchterms2" => '',
                        "amazon_searchterms3" => '',
                        "amazon_searchterms4" => '',
                        "amazon_searchterms5" => '',
                        "amazon_product_type" => '',
                        "amazon_category_item_type" => ''
	);
}

$smarty->assign("amazon_specific_details", $amazon_specific_details);
$smarty->assign("product", $product);
$smarty->assign("main", "amazon_specific_details");
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
