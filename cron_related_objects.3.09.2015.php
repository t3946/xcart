<?php
define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);


if ($config["cron_objects_collector_launched"] == "Y"){
#	die("Already launched"); 
}
db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cron_objects_collector_launched'"); 


$related_objects_collector = func_query_hash("SELECT * FROM $sql_tbl[related_objects_collector]", 'storefrontid', false);

$storefronts[0]["storefrontid"] = 0;
$storefronts[0]["domain"] = "www.artistsupplysource.com";

$start_time = time();


foreach ($storefronts as $storefrontid => $store_info){

	if (empty($related_objects_collector[$storefrontid])){

		$count_related_objects_collector = func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[related_objects_collector] WHERE storefrontid='$storefrontid'");

		if (empty($count_related_objects_collector)){
			db_query("INSERT INTO $sql_tbl[related_objects_collector] (storefrontid) VALUES ('$storefrontid')");
			$related_objects_collector = func_query_hash("SELECT * FROM $sql_tbl[related_objects_collector]", 'storefrontid', false);
		}
	}

	if ($related_objects_collector[$storefrontid]["collecting_period_backward_months"] <= 0){
		continue;
	}


//func_print_r($related_objects_collector[$storefrontid]);


	$categories = db_query($query="SELECT categoryid, order_by FROM $sql_tbl[categories] WHERE avail='Y' AND storefrontid='$storefrontid'");

	$counter = 0;


	while ($category = db_fetch_array($categories)) {

		$categoryid = $category["categoryid"];


		#
		# dynamic_settings
		#
                $dynamic_settings = "";

                $implode_arr = array();
                if ($related_objects_collector[$storefrontid]["add_to_cart"] == "Y") $implode_arr[] = "SM.goal_addtocart='Y'";
                if ($related_objects_collector[$storefrontid]["order_submit"] == "Y") $implode_arr[] = "SM.goal_order='Y'";
                if ($related_objects_collector[$storefrontid]["search"] == "Y") $implode_arr[] = "SM.goal_search='Y'";
                if ($related_objects_collector[$storefrontid]["checkout"] == "Y") $implode_arr[] = "SM.goal_checkout='Y'";

                if (!empty($implode_arr)){
                        $dynamic_settings = " AND (" . implode(" OR ", $implode_arr) .") ";
                }

                if ($related_objects_collector[$storefrontid]["mobile"] == "Y"){
                        $dynamic_settings .= " AND SM.is_mobile IN ('Y','N') ";
                } else {
                        $dynamic_settings .= " AND SM.is_mobile IN ('N') ";
                }


#
# STEP 1 START
#

		$sql_cat_prod_igor_query = "
Select
        SP.resource_id,
        LENGTH(GROUP_CONCAT(SM.goal_order)) - LENGTH(REPLACE(GROUP_CONCAT(SM.goal_order),'Y','')) As Sales
From xcart_products_categories PC 
        left join xcart_cidev_surf_path SP ON SP.resource_id = PC.productid and SP.resource_type  IN ('P')
        left join xcart_cidev_surf_meta SM ON SP.meta_id = SM.id 
Where 
        PC.categoryid = '$categoryid'
        $dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH)
Group By SP.resource_id
Order By Sales desc, COUNT(SP.id) desc
";

		$products = db_query($sql_cat_prod_igor_query);

		$P_NUM = array();
		$P_NUM_index = 0;
		$P_NUM_orderby = 10;

		while ($product = db_fetch_array($products)){

			$P_NUM[$P_NUM_index] = $product;
			$P_NUM[$P_NUM_index]["orderby"]  = $P_NUM_orderby;

			$P_NUM_orderby += 5;
			$P_NUM_index++;

			$counter++;
			if ($counter % 10 == 0) {
				func_flush(".");
				if($counter % 500 == 0) {
					func_flush("<br />\n");
				}
				func_flush();
			}
		}
		db_free_result($products);

		db_query("UPDATE $sql_tbl[products_categories] SET orderby='1000000' WHERE categoryid='$categoryid'");

		if (!empty($P_NUM)){
			foreach ($P_NUM as $k => $v){
				db_query("UPDATE $sql_tbl[products_categories] SET orderby='$v[orderby]' WHERE  productid='$v[resource_id]' AND categoryid='$categoryid'");
			}			
		}
#
# STEP 1 END
#


#
# STEP 2 START
#
$sql_cat_brand_igor_query = "
Select
	P.brandid,
	LENGTH(GROUP_CONCAT(SM.goal_order)) - LENGTH(REPLACE(GROUP_CONCAT(SM.goal_order),'Y','')) As Sales
From xcart_products_categories PC 
	left join xcart_cidev_surf_path SP ON SP.resource_id = PC.productid and SP.resource_type  IN ('P')
	left join xcart_cidev_surf_meta SM ON SP.meta_id = SM.id 
	left join xcart_products P ON P.productid = PC.productid
Where 
	PC.categoryid = '$categoryid'
        $dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH)
Group By P.brandid
Order By Sales desc, COUNT(SP.id) desc";

                $brands = db_query($sql_cat_brand_igor_query);

                $P_NUM = array();
                $P_NUM_index = 0;
                $P_NUM_orderby = 10;

                while ($brand = db_fetch_array($brands)){

                        $P_NUM[$P_NUM_index] = $brand;
                        $P_NUM[$P_NUM_index]["orderby"]  = $P_NUM_orderby;

                        $P_NUM_orderby += 5;
                        $P_NUM_index++;

                        $counter++;
                        if ($counter % 10 == 0) {
                                func_flush(".");
                                if($counter % 500 == 0) {
                                        func_flush("<br />\n");
                                }
                                func_flush();
                        }
                }
		db_free_result($brands);

                db_query("delete from xcart_cidev_related_objects where resource_id = '$categoryid' and resource_type = 'C' and related_type = 0 and related_resource_type = 'B'");

                if (!empty($P_NUM)){
                        foreach ($P_NUM as $k => $v){
                                db_query("insert into xcart_cidev_related_objects (resource_id, resource_type, related_resource_id, related_resource_type, related_resource_orderby,related_type) VALUES ('$categoryid','C', '$v[brandid]', 'B', '$v[orderby]', 0)");
                        }                       
                }
#
# STEP 2 END
#



		if ($category["order_by"] <= 500){
#
# STEP 3 START
#
$sql_cat_subcat_igor_query = "
Select categoryid, SUM(t.Views) As V, SUM(t.Sales) As S
From 
(Select 
                                C.categoryid,
                                COUNT(distinct SP2.id)  as Views,
                                LENGTH(GROUP_CONCAT( SM.goal_order)) - LENGTH(REPLACE(GROUP_CONCAT( SM.goal_order),'Y','')) As Sales
From xcart_categories C 
                        left join xcart_categories C2 ON C2.categoryid_path like CONCAT('%',C.categoryid,'%') and C2.avail = 'Y' and C2.storefrontid = 0
                        left join xcart_products_categories PC ON PC.categoryid =C2.categoryid
                        left join xcart_cidev_surf_path SP2 ON SP2.resource_id = PC.productid and SP2.resource_type  IN ('P')
                        left join xcart_cidev_surf_meta SM ON SP2.meta_id = SM.id 
                        left join xcart_products P ON P.productid = PC.productid
Where C.parentid = '$categoryid' 
$dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH)
Group By C.categoryid
UNION ALL
Select 
                                C.categoryid,
                                COUNT(distinct SP.id)  as Views,
                                LENGTH(GROUP_CONCAT( SM.goal_order)) - LENGTH(REPLACE(GROUP_CONCAT( SM.goal_order),'Y','')) As Sales
From xcart_categories C 
                        left join xcart_cidev_surf_path SP ON SP.resource_id = C.categoryid and SP.resource_type  IN ('C')
                        left join xcart_cidev_surf_meta SM ON SP.meta_id = SM.id 
Where C.parentid = '$categoryid' 
$dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH)
Group By C.categoryid) t
Group By t.categoryid
Order By S desc, V desc";

                $subcats = db_query($sql_cat_subcat_igor_query);

                $P_NUM = array();
                $P_NUM_index = 0;
                $P_NUM_orderby = 1;

                while ($subcat = db_fetch_array($subcats)){

                        $P_NUM[$P_NUM_index] = $subcat;
                        $P_NUM[$P_NUM_index]["orderby"]  = $P_NUM_orderby;

                        $P_NUM_orderby += 3;
                        $P_NUM_index++;

                        $counter++;
                        if ($counter % 10 == 0) {
                                func_flush(".");
                                if($counter % 500 == 0) {
                                        func_flush("<br />\n");
                                }
                                func_flush();
                        }
                }
		db_free_result($subcat);

                if (!empty($P_NUM)){
	                db_query("update xcart_categories C set C.order_by = 499 where C.parentid = '$categoryid'");

                        foreach ($P_NUM as $k => $v){
				db_query("update xcart_categories set order_by = '$v[orderby]' where categoryid = '$v[categoryid]'");
                        }
                }
#
# STEP 3 END
#
		} // if ($category["order_by"] <= 500)


#
# STEP 4 START
#
$sql_cat_prod_outer_igor_query="
Select PP.productid,
                        COUNT(SPS.id), LENGTH(GROUP_CONCAT( SMS.goal_order)) - LENGTH(REPLACE(GROUP_CONCAT( SMS.goal_order),'Y','')) As Sales
From 
(Select 
                                SM.id
From xcart_categories C 
                        left join xcart_categories C2 ON C2.categoryid_path like CONCAT('%',C.categoryid,'%') and C2.avail = 'Y' and C2.storefrontid = 0
                        left join xcart_products_categories PC ON PC.categoryid =C2.categoryid
                        left join xcart_cidev_surf_path SP2 ON SP2.resource_id = PC.productid and SP2.resource_type  IN ('P')
                        left join xcart_cidev_surf_meta SM ON SP2.meta_id = SM.id 
Where C.parentid = '$categoryid'  
$dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH) 
UNION 
Select 
                                SM.id
From xcart_categories C 
                        left join xcart_cidev_surf_path SP ON SP.resource_id = C.categoryid and SP.resource_type  IN ('C')
                        left join xcart_cidev_surf_meta SM ON SP.meta_id = SM.id 
Where C.parentid = '$categoryid' 
$dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH)
) t
                left join xcart_cidev_surf_meta SMS ON SMS.id = t.id
                left join xcart_cidev_surf_path SPS ON SPS.meta_id = t.id and SPS.resource_type = 'P'
                left join xcart_products_categories PCS ON PCS.productid = SPS.resource_id
                left join xcart_categories CS ON CS.categoryid = PCS.categoryid
                left join xcart_products PP ON PP.productid = PCS.productid 
where  CS.avail = 'Y' and CS.categoryid_path not like CONCAT('%','$categoryid','%') and PP.productid is not NULL
Group By PP.productid
Order By Sales desc, COUNT(SPS.id) desc";

                $cat_prod_outers = db_query($sql_cat_prod_outer_igor_query);

                $P_NUM = array();
                $P_NUM_index = 0;
                $P_NUM_orderby = 10;

                while ($cat_prod_outer = db_fetch_array($cat_prod_outers)){

                        $P_NUM[$P_NUM_index] = $cat_prod_outer;
                        $P_NUM[$P_NUM_index]["orderby"]  = $P_NUM_orderby;

                        $P_NUM_orderby += 5;
                        $P_NUM_index++;

                        $counter++;
                        if ($counter % 10 == 0) {
                                func_flush(".");
                                if($counter % 500 == 0) {
                                        func_flush("<br />\n");
                                }
                                func_flush();
                        }
                }
		db_free_result($cat_prod_outer);

                if (!empty($P_NUM)){
                        db_query("delete from xcart_cidev_related_objects where resource_id = '$categoryid' and resource_type = 'C' and related_type = 1 and related_resource_type = 'P'");

                        foreach ($P_NUM as $k => $v){
                                db_query("insert into xcart_cidev_related_objects (resource_id, resource_type, related_resource_id, related_resource_type, related_resource_orderby,related_type) VALUES ('$categoryid','C', '$v[productid]', 'P', '$v[orderby]', 1)");
                        }
                }

#
# STEP 4 END
#


#
# STEP 5 START
#
$sql_cat_cat_outer_igor_query = "
Select CS.categoryid,
                        COUNT(SPS.id), LENGTH(GROUP_CONCAT( SMS.goal_order)) - LENGTH(REPLACE(GROUP_CONCAT( SMS.goal_order),'Y','')) As Sales
From 
(Select 
                                SM.id
From xcart_categories C 
                        left join xcart_categories C2 ON C2.categoryid_path like CONCAT('%',C.categoryid,'%') and C2.avail = 'Y' and C2.storefrontid = 0
                        left join xcart_products_categories PC ON PC.categoryid =C2.categoryid
                        left join xcart_cidev_surf_path SP2 ON SP2.resource_id = PC.productid and SP2.resource_type  IN ('P')
                        left join xcart_cidev_surf_meta SM ON SP2.meta_id = SM.id 
Where C.parentid = '$categoryid'
$dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH)
UNION 
Select 
                                SM.id
From xcart_categories C 
                        left join xcart_cidev_surf_path SP ON SP.resource_id = C.categoryid and SP.resource_type  IN ('C')
                        left join xcart_cidev_surf_meta SM ON SP.meta_id = SM.id 
Where C.parentid = '$categoryid'
$dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH)
) t
                left join xcart_cidev_surf_meta SMS ON SMS.id = t.id
                left join xcart_cidev_surf_path SPS ON SPS.meta_id = t.id and SPS.resource_type = 'C'
                left join xcart_categories CS ON CS.categoryid = SPS.resource_id
where  CS.avail = 'Y' and CS.categoryid_path not like CONCAT('%','$categoryid','%') and CS.categoryid is not NULL
Group By CS.categoryid
Order By Sales desc, COUNT(SPS.id) desc";

                $cat_cat_outers = db_query($sql_cat_cat_outer_igor_query);

                $P_NUM = array();
                $P_NUM_index = 0;
                $P_NUM_orderby = 10;

                while ($cat_cat_outer = db_fetch_array($cat_cat_outers)){

                        $P_NUM[$P_NUM_index] = $cat_cat_outer;
                        $P_NUM[$P_NUM_index]["orderby"]  = $P_NUM_orderby;

                        $P_NUM_orderby += 5;
                        $P_NUM_index++;

                        $counter++;
                        if ($counter % 10 == 0) {
                                func_flush(".");
                                if($counter % 500 == 0) {
                                        func_flush("<br />\n");
                                }
                                func_flush();
                        }
                }
		db_free_result($cat_cat_outer);

                if (!empty($P_NUM)){
                        db_query("delete from xcart_cidev_related_objects where resource_id = '$categoryid' and resource_type = 'C' and related_type = 1 and related_resource_type = 'C'");

                        foreach ($P_NUM as $k => $v){
                                db_query("insert into xcart_cidev_related_objects (resource_id, resource_type, related_resource_id, related_resource_type, related_resource_orderby,related_type) VALUES ('$categoryid','C', '$v[categoryid]', 'C', '$v[orderby]', 1)");
                        }
                }
#
# STEP 5 END
#

#
# STEP 6 START
#
$sql_cat_attrs_igor_query = "
Select SP.resource_id, GROUP_CONCAT(SP.additional_data) As FV
From xcart_cidev_surf_path SP 
                        left join xcart_cidev_surf_meta SM ON SP.meta_id = SM.id 
Where SP.additional_data != '' and SP.resource_type = 'C' and SP.resource_id = '$categoryid'
$dynamic_settings and SM.storefrontid = '$storefrontid' and FROM_UNIXTIME(SM.`date`) > DATE_ADD(NOW(), INTERVAL - ".$related_objects_collector[$storefrontid]["collecting_period_backward_months"]." MONTH)
Group By SP.resource_id";

                $cat_attrs = db_query($sql_cat_attrs_igor_query);

                $P_NUM = array();
                $P_NUM_index = 0;

                while ($cat_attr = db_fetch_array($cat_attrs)){

                        $P_NUM[$P_NUM_index] = $cat_attr;

                        $P_NUM_index++;

                        $counter++;
                        if ($counter % 10 == 0) {
                                func_flush(".");
                                if($counter % 500 == 0) {
                                        func_flush("<br />\n");
                                }
                                func_flush();
                        }
                }
		db_free_result($cat_attr);

		if (!empty($P_NUM)){

			$A1_index = 0;
			$A1 = array();
			$A1_FV = array();
			$A1_FV_all = array();

			$A2_index = 0;
			$A2 = array();
			$A2_FT_all = array();

			foreach ($P_NUM as $k => $v){
				if (!empty($v["FV"])){
					$FV_arr = explode(",", $v["FV"]);
//$FV_arr[]=13335; //////////////////////////////////////////////////////////////////
//$FV_arr[]=13337; //////////////////////////////////////////////////////////////////
//$FV_arr[]=14017; //////////////////////////////////////////////////////////////////
					if (!empty($FV_arr)){
						foreach ($FV_arr as $FV){
							$FV = trim($FV);

							if (!in_array($FV, $A1_FV)){
								$A1_FV[] = $FV;
							}

							$A1_FV_all[] = $FV;
						}
					}
				}
			}

			if (!empty($A1_FV)){

				$A1_FV_all_count_values = array_count_values($A1_FV_all);

				foreach ($A1_FV as $k => $v){
					$A1[$A1_index]["FV"] = $v;
					$A1[$A1_index]["FT"] = func_query_first_cell("select f_id as FT from xcart_cidev_filter_values FV where FV.fv_id = '$v'");
					$A1[$A1_index]["V"] = $A1_FV_all_count_values[$v];

					if (!empty($A1[$A1_index]["FT"])){
						$A2_FT_all[] = $A1[$A1_index]["FT"];
					}

					$A1_index++;
				}
			
				if (!empty($A2_FT_all)){

					$A2_FT_all_count_values = array_count_values($A2_FT_all);

					foreach ($A2_FT_all_count_values as $FT => $FT_count){
						$A2[$A2_index]["FT"] = $FT;
						$A2[$A2_index]["V"] = $FT_count;
						$A2_index++;
					}
				}
			}


			if (!empty($A1) && !empty($A2)){

				$A1 = my_array_sort($A1, "FV", SORT_DESC);
				$A1 = my_array_sort($A1, "FT", SORT_DESC);
				$A1 = array_values($A1);

				$FT = "";
				foreach($A1 as $k => $v){
					if ($FT != $v["FT"]){
						$orderby = 10;
						$FT = $v["FT"];
					}

					$A1[$k]["orderby"] = $orderby;
	
					$orderby += 5;
				}

				$A2 = my_array_sort($A2, "V", SORT_DESC);
				$A2 = array_values($A2);

				$orderby = 10;
				foreach($A2 as $k => $v){
					$A2[$k]["orderby"] = $orderby;
					$orderby += 5;
				}


				db_query("delete from xcart_cidev_related_objects where resource_id = '$categoryid' and resource_type = 'C' and related_type = 0 and (related_resource_type = 'FT' or related_resource_type = 'FV')");


				foreach($A2 as $k2 => $v2){

					db_query("insert into xcart_cidev_related_objects (resource_id, resource_type, related_resource_id, related_resource_type, related_resource_orderby,related_type) VALUES ('$categoryid','C', '$v2[FT]', 'FT', '$v2[orderby]', 0)");

					foreach($A1 as $k1 => $v1){
						if ($v1["FT"] == $v2["FT"]){
							db_query("insert into xcart_cidev_related_objects (resource_id, resource_type, related_resource_id, related_resource_type, related_resource_orderby,related_type) VALUES ('$categoryid','C', '$v1[FV]', 'FV', '$v1[orderby]', 0)");
						}
					}
				}

			}

//func_print_r($A1, $A2);
//func_print_r($P_NUM);
//func_print_r($A1_FV, $A1_FV_all, $A1,  $A1_FV_all_count_values);
//die("===");

		} // if (!empty($P_NUM))
#
# STEP 6 END
#

	} // while ($category = db_fetch_array($categories))
	db_free_result($categories);


#
# STEP 7 START
#
		$sql_products_igor_query =
" Select
        P.productid
from xcart_products P
        inner join xcart_order_details OD ON OD.productid = P.productid
        left join xcart_orders O ON O.orderid = OD.orderid
Where P.forsale = 'Y' and O.cb_status IN ('P','N','O','Q','R','V','IO','I','H') and O.storefrontid = '$storefrontid'
Group By P.productid";

                $products = db_query($sql_products_igor_query);

                while ($product = db_fetch_array($products)){

			$sql_r_products_igor_query = "
Select
            (Select GROUP_CONCAT(OD2.productid)
            From xcart_order_details OD2
                    inner join xcart_orders O2 ON O2.orderid = OD2.orderid and O2.cb_status IN ('P','N','O','Q','R','V','IO','I','H') and O2.storefrontid = '$storefrontid'
                    inner join xcart_products P2 ON P2.productid = OD2.productid and P2.forsale = 'Y'
            Where OD2.orderid IN 
                    (Select OD3.orderid From xcart_order_details OD3
                     where OD3.productid = '$product[productid]'
                     /*Group By OD3.orderid*/
                    ) 
                    and OD2.productid != '$product[productid]'
        ) As Related_productids
from xcart_products P
Where P.productid = '$product[productid]'
Group By P.productid";


			db_query("delete from xcart_cidev_related_objects where resource_id = '$product[productid]' and resource_type = 'OP' and related_type = 1 and related_resource_type = 'P'");


	                $P_NUM = array();
        	        $P_NUM_index = 0;
                	$P_NUM_orderby = 10;
	                $Related_productids_arr_all = array();


			$r_products = db_query($sql_r_products_igor_query);
			while ($r_product = db_fetch_array($r_products)){

				if (!empty($r_product["Related_productids"])){

					$Related_productids_arr = explode(",", $r_product["Related_productids"]);

					if (!empty($Related_productids_arr)){
						foreach ($Related_productids_arr as $r_productid){
							$r_productid = trim($r_productid);

                                                       	if (!empty($r_productid)){
                                                               	$Related_productids_arr_all[] = $r_productid;
							}
						}
					}
				}

	                        $counter++;
        	                if ($counter % 10 == 0) {
                	                func_flush(".");
                        	        if($counter % 500 == 0) {
                                	        func_flush("<br />\n");
	                                }
        	                        func_flush();
                	        }

			}
			db_free_result($r_products);


	                if (!empty($Related_productids_arr_all)){

        	                $Related_productids_array_count_values = array_count_values($Related_productids_arr_all);

	                        foreach ($Related_productids_array_count_values as $productid => $v){
        	                        $P_NUM[$P_NUM_index]["productid"] = $productid;
                	                $P_NUM[$P_NUM_index]["v"] = $v;
                        	        $P_NUM_index++;
	                        }

        	                $P_NUM = my_array_sort($P_NUM, "v", SORT_DESC);

	                        foreach ($P_NUM as $k => $v){
        	                        $P_NUM[$k]["orderby"] = $P_NUM_orderby;

                        	        db_query("insert into xcart_cidev_related_objects (resource_id, resource_type, related_resource_id, related_resource_type, related_resource_orderby,related_type) VALUES ('$product[productid]','OP', '$v[productid]', 'P', '$P_NUM_orderby', 1)");

					$P_NUM_orderby += 5;
	                        }
        	        }

		}
		db_free_result($products);

#
# STEP 7 END
#

} // foreach ($storefronts as $storefrontid => $store_info)

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cron_objects_collector_launched'");

print"<br />DONE!";

?>
