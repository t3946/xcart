<?php

require "./auth.php";
require $xcart_dir."/include/states.php";

if ($mode != "generate_dladfhakdyiwha2938ydhaekdfhjdcnkady8e") die("Inorrect mode");

$path = $xcart_dir."/skin1_kolin/US_City_List/"; 

if (!empty($states) && is_array($states)){

 $us_state_codes = array();

 foreach ($states as $k => $v){
  if ($v["country_code"] == "US"){
	$us_state_codes[] = $v["state_code"];
  }
 }

 if (!empty($us_state_codes)){
  foreach ($us_state_codes as $k => $state){

	$cities = func_query("SELECT primary_city, acceptable_cities FROM $sql_tbl[zip_code_info] WHERE state='$state'");

	$cities_arr = array();
	if (!empty($cities) && is_array($cities)){
		foreach ($cities as $kk => $vv){
			$cities_arr[] = trim($vv["primary_city"]);

			$acceptable_cities_arr = explode(",", $vv["acceptable_cities"]);
			if (!empty($acceptable_cities_arr) && is_array($acceptable_cities_arr)){
				foreach ($acceptable_cities_arr as $kkk => $vvv){
					$vvv = trim($vvv);
					if (!empty($vvv)){
						$cities_arr[] = $vvv;
					}
				}
			}
		}
	}

	if (!empty($cities_arr)){
		$cities_arr = array_unique($cities_arr);
		asort($cities_arr);
		$cities_arr = array_values($cities_arr);

		$cities_arr_count_min_one = count($cities_arr) - 1;

		$state_file = $path.strtolower($state).".js";

		if (is_file($state_file)){
			unlink($state_file);
		}

		$fp = func_fopen($state_file, 'a+', true);
		if ($fp !== false) {

			fwrite($fp, "var city = [");
			foreach ($cities_arr as $kk => $vv){

				$str = '"'.$vv.'"';
				if ($cities_arr_count_min_one != $kk){
					$str .= ',';
				}
				$str .= "\n";

				fwrite($fp, $str);
			}
			fwrite($fp, "];");
		}
		fclose($fp);
	}

	unset($cities_arr);
  }
 }

}

print"Done!";
?>
