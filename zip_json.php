<?php

require './auth.php';

if (!empty($type)) {
	switch ($type) {
		case 'state':
			$aStates = func_query_column("SELECT state FROM ". $sql_tbl['states']." WHERE country_code = '$country_code'");
			print json_encode($aStates);
			exit;
			break;
	}
}

$states = func_query("SELECT $sql_tbl[states].stateid, $sql_tbl[states].state, $sql_tbl[states].code AS state_code, $sql_tbl[states].country_code FROM $sql_tbl[states], $sql_tbl[countries] WHERE $sql_tbl[states].country_code=$sql_tbl[countries].code AND $sql_tbl[countries].active='Y' AND $sql_tbl[states].country_code='US'");

if (isset($zip) && $zip != ""){

###  TESTS  !!!!!
//$zipcode = $zip{0}.$zip{1};
$zipcode = $zip;
###
	if ($zipcode != ""){

                $address_info = func_query($qqq="SELECT 
                        Z.zip,
                        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(Z.primary_city,',',Z.acceptable_cities), ',', ZH.n), ',', -1)) As city,
                        Z.state
                From xcart_zip_code_info Z
                        left join xcart_zip_code_info_helper ZH ON 1=1
                Where Z.zip like CONCAT('".addslashes($zipcode)."','%')
                Group By Z.zip, 2
                HAVING city != ''");

		if (!empty($address_info) && is_array($address_info)){
			foreach ($address_info as $k => $v){
				foreach ($states as $kk => $vv){
					if ($vv["state_code"] == $v["state"]){
						$address_info[$k]["state_name"] = $vv["state"];
					}
				}
			}
		}

//print($qqq);

		$address_info_json = json_encode($address_info);
		print($address_info_json);
	}
}
elseif (isset($city)){

	if (!empty($state)) {
		$sStateCondition = " AND state = '".addslashes($state)."' ";
	}
	$address_info = func_query("SELECT
                        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(CONCAT(Z.primary_city,',',Z.acceptable_cities), ',', ZH.n), ',', -1)) As city,
                        Z.state
                From xcart_k.xcart_zip_code_info Z
                        left join xcart_k.xcart_zip_code_info_helper ZH ON 1=1
                Where CONCAT(Z.primary_city,',',Z.acceptable_cities) like CONCAT('%','".addslashes($city)."','%')
                $sStateCondition
                Group By 1,2
                HAVING city != '' and city like CONCAT('%','".addslashes($city)."','%') ORDER BY 1");

                if (!empty($address_info) && is_array($address_info)){
                        foreach ($address_info as $k => $v){
                                foreach ($states as $kk => $vv){
                                        if ($vv["state_code"] == $v["state"]){
                                                $address_info[$k]["state_name"] = $vv["state"];
                                        }
                                }
                        }
                }

                $address_info_json = json_encode($address_info);
                print($address_info_json);
}


?>
