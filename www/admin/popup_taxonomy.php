<?php

require "./auth.php";
require $xcart_dir."/include/security.php";

/*x_session_register('google_product_taxonomy');
x_session_register('google_product_taxonomy_id');
x_session_register('google_categories_short');
x_session_register('google_categories_full');*/

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

//$google_product_taxonomy='';

if (empty($google_product_taxonomy) || !is_array($google_product_taxonomy) || empty($google_product_taxonomy_id) || !is_array($google_product_taxonomy_id) || empty($google_categories_short) || !is_array($google_categories_short) || empty($google_categories_full) || !is_array($google_categories_full)){

	$google_product_taxonomy = array();
	$google_product_taxonomy_id = array();
	$google_categories_short = array();
//	$max_count = 0;
//	$counter = 0;
	$google_product_taxonomy_arr = func_query("SELECT * FROM xcart_cidev_google_product_taxonomy");
	foreach ($google_product_taxonomy_arr as $k => $v){
		$value_arr = explode(" > ", $v["value"]);

		$value_arr_id = array();
		$value_arr_queue = array();

		foreach ($value_arr as $kk => $vv){
//			$vv = trim($vv);

			$value_arr_queue[$kk] = $vv;
			$value_arr_queue_str = implode(" > ", $value_arr_queue);
			$g_id = func_query_first_cell($qqq="SELECT id FROM xcart_cidev_google_product_taxonomy WHERE value='".addslashes($value_arr_queue_str)."'");

			$google_categories_short[$g_id] = $vv;
			$google_categories_full[$g_id] = $value_arr_queue_str;

//if (empty($g_id)){
//func_print_r($qqq);
//die("asd");
//}
			$value_arr_id[$kk] = $g_id;


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
			if (!is_array($google_product_taxonomy[$value_arr[0]])){
				$google_product_taxonomy[$value_arr[0]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]] = array();
			}
		}
		if (!empty($value_arr[1])){
			if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]] = array();
			}
		}
		if (!empty($value_arr[2])){
		        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]] = array();
			}
		}
		if (!empty($value_arr[3])){
		        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]][$value_arr_id[3]] = array();
			}
		}
		if (!empty($value_arr[4])){
		        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]][$value_arr_id[3]][$value_arr_id[4]] = array();
			}
		}
		if (!empty($value_arr[5])){
		        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]][$value_arr_id[3]][$value_arr_id[4]][$value_arr_id[5]] = array();

//func_print_r($google_product_taxonomy_id, $value_arr_id);
//die();

			}
		}
                if (!empty($value_arr[6])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]][$value_arr_id[3]][$value_arr_id[4]][$value_arr_id[5]][$value_arr_id[6]] = array();
			}
                }
                if (!empty($value_arr[7])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]][$value_arr_id[3]][$value_arr_id[4]][$value_arr_id[5]][$value_arr_id[6]][$value_arr_id[7]] = array();
			}
                }
                if (!empty($value_arr[8])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]][$value_arr_id[3]][$value_arr_id[4]][$value_arr_id[5]][$value_arr_id[6]][$value_arr_id[7]][$value_arr_id[8]] = array();
			}
                }
                if (!empty($value_arr[9])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]][$value_arr[9]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]][$value_arr[9]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]][$value_arr_id[3]][$value_arr_id[4]][$value_arr_id[5]][$value_arr_id[6]][$value_arr_id[7]][$value_arr_id[8]][$value_arr_id[9]] = array();
			}
                }
                if (!empty($value_arr[10])){
                        if (!is_array($google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]][$value_arr[9]][$value_arr[10]])){
				$google_product_taxonomy[$value_arr[0]][$value_arr[1]][$value_arr[2]][$value_arr[3]][$value_arr[4]][$value_arr[5]][$value_arr[6]][$value_arr[7]][$value_arr[8]][$value_arr[9]][$value_arr[10]] = array();
				$google_product_taxonomy_id[$value_arr_id[0]][$value_arr_id[1]][$value_arr_id[2]][$value_arr_id[3]][$value_arr_id[4]][$value_arr_id[5]][$value_arr_id[6]][$value_arr_id[7]][$value_arr_id[8]][$value_arr_id[9]][$value_arr_id[10]] = array();
			}
                }


		unset($value_arr);
	}

/*	x_session_save("google_product_taxonomy");
	x_session_save("google_product_taxonomy_id");
	x_session_save("google_categories_short");
	x_session_save("google_categories_full");*/
}

//func_print_r($google_categories_full);
//func_print_r($google_product_taxonomy_id);
//func_print_r($google_product_taxonomy);
//func_print_r($google_product_taxonomy_arr);
//func_print_r($last_taxonomy);

$open_cats = array();
if (!empty($last_taxonomy) && !empty($google_categories_full[$last_taxonomy])){

	$value_arr_queue = array();
	$last_taxonomy_full_str_arr = explode(" > ", $google_categories_full[$last_taxonomy]);
	foreach ($last_taxonomy_full_str_arr as $k =>$v){
		$value_arr_queue[$k] = $v;
                $value_arr_queue_str = implode(" > ", $value_arr_queue);
                $g_id = func_query_first_cell($qqq="SELECT id FROM xcart_cidev_google_product_taxonomy WHERE value='".addslashes($value_arr_queue_str)."'");
		if (!empty($g_id)){
			$open_cats[$g_id] = "Y";
		}
	}
}
$smarty->assign("open_cats", $open_cats);

//func_print_r($open_cats);

$smarty->assign("google_product_taxonomy", $google_product_taxonomy);
$smarty->assign("google_product_taxonomy_id", $google_product_taxonomy_id);
$smarty->assign("google_categories_short", $google_categories_short);
$smarty->assign("google_categories_full", $google_categories_full);
$smarty->assign("id", $id);
$smarty->assign("last_taxonomy", $last_taxonomy);

func_display("admin/main/popup_taxonomy.tpl",$smarty);
?>
