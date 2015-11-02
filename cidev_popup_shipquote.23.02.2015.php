<?php

require './auth.php';

if ($REQUEST_METHOD == 'POST')
 {
	if ($cidev_filter_mode == "show_state_city"){

		$s_zipcode_in_shipquoteform = trim($s_zipcode_in_shipquoteform);

		$state_city = func_query_first("SELECT state, primary_city FROM $sql_tbl[zip_code_info] WHERE zip='".addslashes($s_zipcode_in_shipquoteform)."' AND acceptable_cities=''");

		if (!empty($state_city)){
			$cidev_state_name = func_query_first_cell("SELECT state FROM $sql_tbl[states] WHERE country_code='US' AND code='$state_city[state]'");

			$smarty->assign('cidev_state_name', $cidev_state_name);
		        $smarty->assign('td_s_state_show_text', $cidev_state_name);
		        $smarty->assign('td_s_city_show_text', $state_city["primary_city"]);
	        	func_display('customer/main/cidev_shipquote_state_city_values.tpl', $smarty);
		}
		else {
			$state=func_query_first_cell("SELECT state FROM $sql_tbl[zip_code_info] WHERE zip='".addslashes($s_zipcode_in_shipquoteform)."' AND acceptable_cities!=''");
			if (!empty($state)){
				$cidev_state_name = func_query_first_cell("SELECT state FROM $sql_tbl[states] WHERE country_code='US' AND code='$state'");
	                        $smarty->assign('cidev_state_name', $cidev_state_name);
        	                $smarty->assign('td_s_state_show_text', $cidev_state_name);
                	        func_display('customer/main/cidev_shipquote_state_city_values.tpl', $smarty);
			}
		}
	}
	elseif ($cidev_filter_mode == "show_cities" && !empty($s_city_in_shipquoteform) && !empty($s_state_in_shipquoteform)){

		$show_cities = func_query("SELECT primary_city FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_shipquoteform)."' AND primary_city LIKE '".addslashes($s_city_in_shipquoteform)."%'");
		$smarty->assign('show_cities', $show_cities);
		func_display('customer/main/cidev_show_cities_js.tpl', $smarty);
	}
	elseif ($cidev_filter_mode == "show_zip"){

		if (!empty($s_city_in_shipquoteform)){

			$s_state_in_shipquoteform = trim($s_state_in_shipquoteform);
			$s_city_in_shipquoteform = trim($s_city_in_shipquoteform);

			$show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_shipquoteform)."' AND primary_city = '".addslashes($s_city_in_shipquoteform)."'");

### Chicago has tw zips ###
                        if (!empty($s_zipcode_in_shipquoteform)){
                                $tmp_show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_shipquoteform)."' AND primary_city='".addslashes($s_city_in_shipquoteform)."' AND zip='".addslashes($s_zipcode_in_shipquoteform)."'");
                                if ($tmp_show_zip == $s_zipcode_in_shipquoteform){
                                        $show_zip = $s_zipcode_in_shipquoteform;
                                }
                        }
###########################

### additional check ###
                        if (empty($show_zip)){
                                $acceptable_cities = func_query("SELECT acceptable_cities FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_shipquoteform)."' AND acceptable_cities LIKE '%".addslashes($s_city_in_shipquoteform)."%'");
                                $acceptable_cities_arr = array();
                                if (!empty($acceptable_cities) && is_array($acceptable_cities)){
                                        foreach ($acceptable_cities as $k => $v){
                                                $tmp_acceptable_cities_arr = explode(",", $v["acceptable_cities"]);
                                                if (!empty($tmp_acceptable_cities_arr) && is_array($tmp_acceptable_cities_arr)){
                                                        foreach ($tmp_acceptable_cities_arr as $kk => $vv){
                                                                $vv = trim($vv);
                                                                $acceptable_cities_arr[] = $vv;
                                                        }
                                                }
                                        }
                                }

                                if (!empty($acceptable_cities_arr)){

                                        $acceptable_cities_arr = array_unique($acceptable_cities_arr);
                                        foreach ($acceptable_cities_arr as $k => $v){
                                                if ($v == addslashes($s_city_in_shipquoteform)){
                                                        $show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_shipquoteform)."' AND acceptable_cities LIKE '%".addslashes($s_city_in_shipquoteform)."%'");
                                                        break;
                                                }
                                        }
                                }
                        }
########################

			if (!empty($show_zip)){
		                $smarty->assign('show_zip', $show_zip);
		                func_display('customer/main/cidev_ship_form_show_zip.tpl', $smarty);
			}
		}
	}
	elseif ($cidev_filter_mode == "show_state_city_reg_form"){

		$s_zipcode_in_registerform = trim($s_zipcode_in_registerform);

                $state_city = func_query_first("SELECT state, primary_city FROM $sql_tbl[zip_code_info] WHERE zip='".addslashes($s_zipcode_in_registerform)."' AND acceptable_cities=''");

		if (!empty($state_city)){
			$cidev_state_name = func_query_first_cell("SELECT state FROM $sql_tbl[states] WHERE country_code='US' AND code='$state_city[state]'");

	                $smarty->assign('s_state_show_text', $cidev_state_name);
        	        $smarty->assign('s_city_show_text', $state_city["primary_city"]);
                	func_display('main/cidev_reg_form_state_city_values.tpl', $smarty);
		}
		else {
			$state=func_query_first_cell("SELECT state FROM $sql_tbl[zip_code_info] WHERE zip='".addslashes($s_zipcode_in_registerform)."' AND acceptable_cities!=''");
			if (!empty($state)){
				$cidev_state_name = func_query_first_cell("SELECT state FROM $sql_tbl[states] WHERE country_code='US' AND code='$state'");
				$smarty->assign('s_state_show_text', $cidev_state_name);
				func_display('main/cidev_reg_form_state_city_values.tpl', $smarty);
			}
		}
        }
	elseif ($cidev_filter_mode == "show_zip_reg_form"){

                if (!empty($s_city_in_registerform)){

			$s_state_in_registerform = trim($s_state_in_registerform);
			$s_city_in_registerform = trim($s_city_in_registerform);

                        $show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_registerform)."' AND primary_city='".addslashes($s_city_in_registerform)."'");

### Chicago has tw zips ###
			if (!empty($s_zipcode_in_registerform)){
				$tmp_show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_registerform)."' AND primary_city='".addslashes($s_city_in_registerform)."' AND zip='".addslashes($s_zipcode_in_registerform)."'");
				if ($tmp_show_zip == $s_zipcode_in_registerform){
					$show_zip = $s_zipcode_in_registerform;
				}
			}
###########################

### additional check ###
			if (empty($show_zip)){
				$acceptable_cities = func_query("SELECT acceptable_cities FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_registerform)."' AND acceptable_cities LIKE '%".addslashes($s_city_in_registerform)."%'");
				$acceptable_cities_arr = array();
				if (!empty($acceptable_cities) && is_array($acceptable_cities)){
					foreach ($acceptable_cities as $k => $v){
						$tmp_acceptable_cities_arr = explode(",", $v["acceptable_cities"]);
						if (!empty($tmp_acceptable_cities_arr) && is_array($tmp_acceptable_cities_arr)){
							foreach ($tmp_acceptable_cities_arr as $kk => $vv){
								$vv = trim($vv);
								$acceptable_cities_arr[] = $vv;
							}
						}
					}
				}
				
				if (!empty($acceptable_cities_arr)){
			
					$acceptable_cities_arr = array_unique($acceptable_cities_arr);
					foreach ($acceptable_cities_arr as $k => $v){
						if ($v == addslashes($s_city_in_registerform)){
							$show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($s_state_in_registerform)."' AND acceptable_cities LIKE '%".addslashes($s_city_in_registerform)."%'");
							break;
						}
					}
				}
			}
########################

			if (!empty($show_zip)){
        		        $smarty->assign('s_zip_show_text', $show_zip);
		                func_display('main/cidev_reg_form_show_zip.tpl', $smarty);
			}
                }
        }
        elseif ($cidev_filter_mode == "show_state_city_reg_form_b"){

		$b_zipcode_in_registerform = trim($b_zipcode_in_registerform);

                $state_city = func_query_first("SELECT state, primary_city FROM $sql_tbl[zip_code_info] WHERE zip='".addslashes($b_zipcode_in_registerform)."' AND acceptable_cities=''");

		if (!empty($state_city)){
			$cidev_state_name = func_query_first_cell("SELECT state FROM $sql_tbl[states] WHERE country_code='US' AND code='$state_city[state]'");

        	        $smarty->assign('b_state_show_text', $cidev_state_name);
	                $smarty->assign('b_city_show_text', $state_city["primary_city"]);
        	        func_display('main/cidev_reg_form_state_city_values_b.tpl', $smarty);
		}
		else {
			$state=func_query_first_cell("SELECT state FROM $sql_tbl[zip_code_info] WHERE zip='".addslashes($b_zipcode_in_registerform)."' AND acceptable_cities!=''");
			if (!empty($state)){
				$cidev_state_name = func_query_first_cell("SELECT state FROM $sql_tbl[states] WHERE country_code='US' AND code='$state'");
				$smarty->assign('b_state_show_text', $cidev_state_name);
				func_display('main/cidev_reg_form_state_city_values_b.tpl', $smarty);
			}
		}
        }
        elseif ($cidev_filter_mode == "show_zip_reg_form_b"){

                if (!empty($b_city_in_registerform)){

			$b_state_in_registerform = trim($b_state_in_registerform);
			$b_city_in_registerform = trim($b_city_in_registerform);

                        $show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($b_state_in_registerform)."' AND primary_city='".addslashes($b_city_in_registerform)."'");

### Chicago has tw zips ###
                        if (!empty($b_zipcode_in_registerform)){
                                $tmp_show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($b_state_in_registerform)."' AND primary_city='".addslashes($b_city_in_registerform)."' AND zip='".addslashes($b_zipcode_in_registerform)."'");
                                if ($tmp_show_zip == $b_zipcode_in_registerform){
                                        $show_zip = $b_zipcode_in_registerform;
                                }
                        }
###########################

### additional check ###
                        if (empty($show_zip)){
                                $acceptable_cities = func_query("SELECT acceptable_cities FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($b_state_in_registerform)."' AND acceptable_cities LIKE '%".addslashes($b_city_in_registerform)."%'");
                                $acceptable_cities_arr = array();
                                if (!empty($acceptable_cities) && is_array($acceptable_cities)){
                                        foreach ($acceptable_cities as $k => $v){
                                                $tmp_acceptable_cities_arr = explode(",", $v["acceptable_cities"]);
                                                if (!empty($tmp_acceptable_cities_arr) && is_array($tmp_acceptable_cities_arr)){
                                                        foreach ($tmp_acceptable_cities_arr as $kk => $vv){
                                                                $vv = trim($vv);
                                                                $acceptable_cities_arr[] = $vv;
                                                        }
                                                }
                                        }
                                }

                                if (!empty($acceptable_cities_arr)){

                                        $acceptable_cities_arr = array_unique($acceptable_cities_arr);
                                        foreach ($acceptable_cities_arr as $k => $v){
                                                if ($v == addslashes($b_city_in_registerform)){
                                                        $show_zip = func_query_first_cell("SELECT zip FROM $sql_tbl[zip_code_info] WHERE state='".addslashes($b_state_in_registerform)."' AND acceptable_cities LIKE '%".addslashes($b_city_in_registerform)."%'");
                                                        break;
                                                }
                                        }
                                }
                        }
########################

			if (!empty($show_zip)){
		                $smarty->assign('b_zip_show_text', $show_zip);
	        	        func_display('main/cidev_reg_form_show_zip_b.tpl', $smarty);
			}
                }
        }

}
?>
