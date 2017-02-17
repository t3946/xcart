<?php

//require './auth.php';  #uses xid

#
## ALWAYS USE IT if you do not require auth.php
###
define('AREA_TYPE', 'C'); // if add this, then xid is used.

define('x_session_save_to_db__do_not_use', 'Y');

require "./top.inc.php";
require "./init.php"; #uses xid.X


$current_area="C";
###
##
#

list($products, $sGoogleAnaliticsParam) = Xcart\Helpers\SliderData::getSliderData($section_name, $productid);

if ($REQUEST_METHOD == 'POST') {
	if (!empty($products)){

		$count_products = count($products);

		$products_str = '{"items": [';
		foreach ($products as $k => $v){

			$products_str .= '{';
				$products_str .= '"productid": "'.$v["productid"].'",';
				$products_str .= '"clean_url": "'.$v["clean_url"].'",';
				$products_str .= '"src": "'.(empty($v["tmbn_url_T"]) ? $v["tmbn_url"] : $v["tmbn_url_T"]).'",';
				$products_str .= '"price": "'.$v["price"].'",';

				$products_str .= '"category": "'.func_add_slashes($v["category"]).'",';
				$products_str .= '"brand": "'.func_add_slashes($v["brand"]).'",';

				$products_str .= '"product": "'.func_add_slashes(str_replace(array("\r","\n"),"",$v["product"])).'",';

                if ($v['oSplash']) {
                    $products_str .= '"splash": "' . $v['oSplash']->image_path . '",';
                }

				$N_key = $k + 1;
				$products_str .= '"N_key": "'.$N_key.'",';
				if (!empty($sGoogleAnaliticsParam)) $products_str .= '"ga_param": "'.$sGoogleAnaliticsParam.'",';

				$products_str .= '"title": "'.addslashes(str_replace(array("\r","\n"),"",$v["product"])).'"';
			$products_str .= '}';

			if (($count_products -1)!= $k) $products_str .= ',';
		}
		$products_str .= ']}';

		echo $products_str;

	}

}
