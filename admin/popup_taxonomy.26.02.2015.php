<?php

require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register('google_product_taxonomy');

/*

if ($REQUEST_METHOD == "POST" || $shipping_error == "Y") {

	if ($mode == 'checkout') {
?>
<script type="text/javascript">
<!--
if (window.opener)
	window.opener.location = 'cart.php?mode=checkout&toreg=1';
window.close();
-->
</script>
<?php
		exit;
	}

	if (func_is_cart_empty($cart) || empty($cart["shipping_groups"]))	
		func_close_window();

}
*/

if (empty($google_product_taxonomy) || !is_array($google_product_taxonomy)){

	$google_product_taxonomy = array();
//	$max_count = 0;
//	$counter = 0;
	$google_product_taxonomy_arr = func_query("SELECT * FROM xcart_cidev_google_product_taxonomy");
	foreach ($google_product_taxonomy_arr as $k => $v){
		$value_arr = explode(">", $v["value"]);
		foreach ($value_arr as $kk => $vv){
			$vv = trim($vv);
			$vv = str_replace(" ", "_", $vv);
			$value_arr[$kk] = $vv;
		}
		$google_product_taxonomy_arr[$k]["value_arr"] = $value_arr;
/*
		$count_value_arr = count($value_arr);
		$google_product_taxonomy_arr[$k]["count_value_arr"] = $count_value_arr;
		if ($count_value_arr > $max_count){
			$max_count = $count_value_arr;
		}
*/


		if (!empty($value_arr[0])){
			if (!is_array($google_product_taxonomy[$value_arr[0]])) $google_product_taxonomy[$value_arr[0]] = array();
		}
		if (!empty($value_arr[1])){
			if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]] = array();
		}
		if (!empty($value_arr[2])){
		       if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]] = array();
		}
		if (!empty($value_arr[3])){
		        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]] = array();
		}
		if (!empty($value_arr[4])){
		        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]] = array();
		}
		if (!empty($value_arr[5])){
		        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]] = array();
		}
                if (!empty($value_arr[6])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]] = array();
                }
                if (!empty($value_arr[7])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]] = array();
                }
                if (!empty($value_arr[8])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]] = array();
                }
                if (!empty($value_arr[9])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]][$value_arr[9]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]][$value_arr[9]] = array();
                }
                if (!empty($value_arr[10])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]][$value_arr[9]][$value_arr[10]])) $google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]][$value_arr[9]][$value_arr[10]] = array();
                }


		unset($value_arr);
	}

	x_session_save("google_product_taxonomy");
}

//func_print_r($max_count, $google_product_taxonomy);
//func_print_r($google_product_taxonomy_arr);

$smarty->assign("google_product_taxonomy", $google_product_taxonomy);
$smarty->assign("id", $id);

func_display("admin/main/popup_taxonomy.tpl",$smarty);
?>
