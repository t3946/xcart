<?php
@set_time_limit(0);

require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data");

$location[] = array("Amazon FBA restocking report", "");

if ($REQUEST_METHOD=="POST") {

    if ($mode == "search"){

	$search_data["amazon_fba_restocking_report"]["Amazon_FBA_Restocking_period_days"] = $Amazon_FBA_Restocking_period_days;
	$search_data["amazon_fba_restocking_report"]["Amazon_FBA_Report_depth_months"] = $Amazon_FBA_Report_depth_months;
	$search_data["amazon_fba_restocking_report"]["Amazon_FBA_Report_Tau"] = $Amazon_FBA_Report_Tau;

	x_session_save("search_data");

        func_header_location("amazon_fba_restocking_report.php?mode=search");
    }
}

if ($mode == "search"){

/*

        if (!empty($page) && $search_data["amazon_fba_restocking_report"]["page"] != intval($page)) {
                # Store the current page number in the session
                $search_data["amazon_fba_restocking_report"]["page"] = $page;
        } else {
		if (!empty($page)){
			$search_data["amazon_fba_restocking_report"]["page"] = $page;
		}
		else {
	                $search_data["amazon_fba_restocking_report"]["page"] = 1;
		}
        }
	x_session_save("search_data");

	$data['_objects_per_page'] = 30;
*/

	$where_arr = array();
	$where = "";

	if (!empty($search_data["amazon_fba_restocking_report"]["Amazon_FBA_Report_depth_months"])){
//		$where_arr[] = "";
	}

	if (!empty($where_arr)){
		$where = "WHERE ".implode(" AND ", $where_arr);
	}


/*	
	$total_items = func_query_first_cell($q="SELECT COUNT(*) FROM $sql_tbl[product_question] $where");

        if (!empty($data['_objects_per_page'])) {
                #
                # Prepare the page navigation
                #
                $page = $search_data["amazon_fba_restocking_report"]["page"];
                $objects_per_page = $data['_objects_per_page'];
                $total_nav_pages = ceil($total_items/$objects_per_page)+1;

                include $xcart_dir."/include/navigation.php";

                $sort_string .= " LIMIT $first_page, $objects_per_page";
        }
*/


	$Amazon_FBA_Report_depth_months = $search_data["amazon_fba_restocking_report"]["Amazon_FBA_Report_depth_months"];
	$Amazon_FBA_Restocking_period_days = $search_data["amazon_fba_restocking_report"]["Amazon_FBA_Restocking_period_days"];
	$Amazon_FBA_Report_Tau = $search_data["amazon_fba_restocking_report"]["Amazon_FBA_Report_Tau"];


	$cur_time = time();
	$START_TIME_Amazon_FBA_Report_depth_months = mktime(0, 0, 0, date("m")-$Amazon_FBA_Report_depth_months, date("d"),   date("Y"));
	$total_days_in_depth = floor(($cur_time - $START_TIME_Amazon_FBA_Report_depth_months)/(60*60*24));
	$count_periods = floor($total_days_in_depth/$Amazon_FBA_Restocking_period_days);

	### re-assign because was floor-ed
		$total_days_in_depth = $count_periods*$Amazon_FBA_Restocking_period_days;
		$START_TIME_Amazon_FBA_Report_depth_months = mktime(0, 0, 0, date("m"), date("d")-$total_days_in_depth,   date("Y"));
	###
//func_print_r($cur_time, $START_TIME_Amazon_FBA_Report_depth_months, $total_days_in_depth, $count_periods);

	$fba_report_query = "
		Select Distinct OD.productid, P.amazon_fba_avail, P.productcode, P.product, P.cost_to_us,
                        (Select F.cpr_LandedPrice 
                         From xcart_cidev_amazon_fba_products F
                         Where F.productid = OD.productid
                         Order By F.report_date desc
                         Limit 1) As fba_min_price,
                         cidev_get_minimum_amazon_price(OD.productid) As our_min_price,
                         cidev_get_amazon_FBA_lastorder_price(OD.productid) as last_order_price,
                         IF((cidev_get_amazon_FBA_lastorder_price(OD.productid) - cidev_get_minimum_amazon_price(OD.productid)) >= 0, 'N', 'Y') as discounted_product,
                         P.upc as upc,
			 M.manufacturer As Supplier,
(SELECT count(F.id) 
                 FROM xcart_cidev_amazon_fba_products F
                 Where F.productid = P.productid and F.lis_InStockSupplyQuantity>0 and F.report_date > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL -$Amazon_FBA_Report_depth_months MONTH))) As StockingDays

		From xcart_orders O
	        left join xcart_order_details OD ON OD.orderid = O.orderid
        	inner join xcart_products P ON P.productid = OD.productid and P.forsale = 'Y'
		left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid
		Where O.amazon_fulfillment_channel = 'AFN' and O.date > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL -$Amazon_FBA_Report_depth_months MONTH))
	";

	$fba_report_query = db_query($fba_report_query);

	$total_Extended = 0;
	$fba_report = array();
        while($p = db_fetch_array($fba_report_query)) {

/*
		$p["stockings_array"] = func_query("Select P.productid, OD.productid, O.orderid, FROM_UNIXTIME(O.date), OD.amount, 
                (SELECT count(F.id) 
                 FROM xcart_cidev_amazon_fba_products F
                 Where F.productid = P.productid and F.lis_InStockSupplyQuantity>0 and F.report_date > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL -$Amazon_FBA_Report_depth_months MONTH))) As StockingDays
From xcart_products P
        left join xcart_order_details OD ON OD.productid = P.productid
        inner join xcart_orders O ON O.orderid = OD.orderid and O.amazon_fulfillment_channel = 'AFN' and O.date > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL -$Amazon_FBA_Report_depth_months MONTH))
Where P.productid = '$p[productid]'");

		if (!empty($p["stockings_array"])){

	                $p["total_amount"] = 0;
        	        $StockingDays = 0;

			foreach ($p["stockings_array"] as $k => $v){
				$p["total_amount"] += $v["amount"];
				$v["date_time"] = strtotime($v["FROM_UNIXTIME(O.date)"]);
				$p["stockings_array"][$k]["date_time"] = $v["date_time"];
				$StockingDays++;
			}


			$p["n"] = $p["total_amount"]/($StockingDays/$Amazon_FBA_Restocking_period_days);

			$p["StockingDays"] = $StockingDays;
		}
*/



/*
                        $stockings_array = func_query("
Select 
            FROM_UNIXTIME(F.report_date) As ReportDate, 
            IF(F.lis_InStockSupplyQuantity>0 OR (SUM(IF(O.amazon_fulfillment_channel='AFN',OD.amount,0))>0),1,0) As IsInStock, 
            SUM(IF(O.amazon_fulfillment_channel='AFN',OD.amount,0)) As SaleAmount
From xcart_cidev_amazon_fba_products F
            left join xcart_order_details OD ON OD.productid = F.productid
            left join xcart_orders O ON O.orderid = OD.orderid and DATE(FROM_UNIXTIME(O.date)) = DATE(FROM_UNIXTIME(F.report_date))
Where F.productid = '$p[productid]' and F.report_date > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL -$Amazon_FBA_Report_depth_months MONTH))
Group By F.report_date
Order By F.report_date desc
                        ");
*/


                        $stockings_array = func_query("
Select 
            FROM_UNIXTIME(F.report_date) As ReportDate, 
            IF(F.lis_InStockSupplyQuantity>0 OR (SUM(IF(O.amazon_fulfillment_channel='AFN',OD.amount,0))>0),1,0) As IsInStock, 
            SUM(IF(O.amazon_fulfillment_channel='AFN',OD.amount,0)) As SaleAmount
From xcart_cidev_amazon_fba_products F
            left join xcart_order_details OD ON OD.productid = F.productid
            left join xcart_orders O ON O.orderid = OD.orderid and DATE(FROM_UNIXTIME(O.date)) = DATE(FROM_UNIXTIME(F.report_date))
Where F.productid = '$p[productid]' /*and F.report_date > UNIX_TIMESTAMP(DATE_ADD(NOW(), INTERVAL -$Amazon_FBA_Report_depth_months MONTH))*/
Group By F.report_date
Having IsInStock>0
Order By F.report_date desc
Limit $total_days_in_depth
                        ");

			if (!empty($stockings_array)){
				foreach ($stockings_array as $k => $v){
					if ($v["IsInStock"] < 1){
						unset($stockings_array[$k]);
						continue;
					}

					$stockings_array[$k]["date_time"] = strtotime($v["ReportDate"]);
				}
			}
			$p["StockingDays"] = count($stockings_array);
			$stockings_array = array_values($stockings_array);
//			func_print_r($stockings_array);
			if (!empty($stockings_array)){

				$Pi = 0;
				$N = 0;
				$Ncp = array();

				$count_stockings_array = count($stockings_array);
//				print("<br>".$p["productid"]." <br>");
				for ($i=0; $i < $count_stockings_array; $i+=$Amazon_FBA_Restocking_period_days){
					$Ncp[$Pi] = 0;

					$min_val = min($count_stockings_array, $i+$Amazon_FBA_Restocking_period_days);
//					print("i: ".$i."<br>");
					$last_sd = 0;
					for ($j=$i; $j< $min_val; $j++){
						 $Ncp[$Pi] += $stockings_array[$j]["SaleAmount"];
						 $last_sd++;
//						 print("j = ".$j." min_val = ".$min_val." sa = ".$stockings_array[$j]["SaleAmount"]."<br>");
					}
//					print("Ncp = ".$Ncp[$Pi]." j ".$last_sd."\n");
					$Ncp[$Pi] = ($Ncp[$Pi] * ($Amazon_FBA_Restocking_period_days/$last_sd));
					$N += $Ncp[$Pi];
					$Pi++;
				}

				$p["N"] = $N;
				$p["Pi"] = ceil($p["StockingDays"]/$Amazon_FBA_Restocking_period_days);

				$p["N_avg"] = price_format($N/$p["Pi"]);

				$sigma = 0;
//				func_print_r($Ncp);
				foreach ($Ncp as $i => $v){
					$sigma += ($v - $p["N_avg"]) * ($v - $p["N_avg"]);
				}
				
//				print($sigma."<br>");
				$p["sigma"] = sqrt($sigma / $p["Pi"]);
				$p["sigma"] = price_format($p["sigma"]);

				$p["Re_stock_quantity"] = ceil(price_format($p["N_avg"] - $p["sigma"]));
				$Re_stock_qty_normalized = $p["Re_stock_quantity"];
				if ($Re_stock_qty_normalized<0) 
				    {
				    $Re_stock_qty_normalized = price_format(0.00);
				    }
				$Extended = price_format(ceil($Re_stock_qty_normalized)*$p["cost_to_us"]);
				$p["Extended"] = $Extended;

				$total_Extended += $p["Extended"];
			}


//func_print_r($stockings_array);
//die();

		$p["fba_min_price"] = price_format($p["fba_min_price"]);
		$p["our_min_price"] = price_format($p["our_min_price"]);
        $p["last_order_price"] = price_format($p["last_order_price"]);

		$p["stockings_array"] = $stockings_array;
		$fba_report[$p["productid"]] = $p;

//func_print_r($fba_report[$p["productid"]]);
//die();

        }
        db_free_result($tmp_products1);


/*
	if (!empty($fba_report) && is_array($fba_report)){
          $i = 0;
          for ($j=$count_periods; $j>0; $j--){

		$sp_start = $START_TIME_Amazon_FBA_Report_depth_months + ($j - 1)*$Amazon_FBA_Restocking_period_days*60*60*24;

		if ($i == 0){
			$sp_end = $START_TIME_Amazon_FBA_Report_depth_months + $j*$Amazon_FBA_Restocking_period_days*60*60*24;
		} else {
			$sp_end = $START_TIME_Amazon_FBA_Report_depth_months + (($j*$Amazon_FBA_Restocking_period_days) - 1)*60*60*24;
		}
        

		$sp_start_date = date("d/m/Y", $sp_start);
		$sp_end_date = date("d/m/Y", $sp_end);

		foreach ($fba_report as $productid => $p){

			$fba_report[$productid]["n_arr"][$i] = 0;

			if (!empty($p["stockings_array"]) && is_array($p["stockings_array"])){

				$total_amount = 0;
				$StockingDays = 0;
				foreach ($p["stockings_array"] as $k => $v){
					if ($sp_start_date<=$v["date_time"] && $v["date_time"]<=$sp_end){
						$total_amount += $v["amount"];
						$StockingDays++;
					}
				}
				$days_tmp = $StockingDays/$Amazon_FBA_Restocking_period_days;

				if ($days_tmp != 0){
					$fba_report[$productid]["n_arr"][$i] = $total_amount/($days_tmp);
				}
			}
		}
                
	        $i++;
          }

	
		foreach ($fba_report as $productid => $p){
			if (!empty($p["n_arr"]) && is_array($p["n_arr"])){
				$sigma = 0;

				foreach ($p["n_arr"] as $i => $n_v){
					$sigma += ($n_v - $p["n"])*($n_v - $p["n"]);
				}

				$sigma = sqrt( (1/($p["StockingDays"]/$Amazon_FBA_Restocking_period_days))*$sigma );

				$fba_report[$productid]["sigma"] = $sigma;

				$fba_report[$productid]["Re_stock_quantity"] = $p["n"] - $sigma;
			}
		}

	} // if (!empty($fba_report) && is_array($fba_report))
*/

	if ($debug == "Y"){
		func_print_r($fba_report);
	}

	$fba_report_sorted = my_array_sort($fba_report,'Re_stock_quantity', SORT_DESC);

	if ($download == "1"){
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=\"report1.csv\"");

		$header_arr[0] = "SKU";
		$header_arr[1] = "Product name";
		$header_arr[2] = "Supplier";
		$header_arr[3] = "Our FBA quantity";
		$header_arr[4] = "FBA buybox price";
		$header_arr[5] = "OUR minimum price";
        $header_arr[6] = "FBA last order price";
        $header_arr[7] = "Discounted product";
		$header_arr[8] = "Stocking Days";
		$header_arr[9] = "Restocking period";
		$header_arr[10] = "N average";
		$header_arr[11] = "Sigma";
		$header_arr[12] = "Re-stock quantity";
		$header_arr[13] = "Cost to us";
		$header_arr[14] = "Extended";
        $header_arr[15] = "UPC";

		$header_line = implode("\t", $header_arr);
		print($header_line."\r\n");

		if (!empty($fba_report_sorted) && is_array($fba_report_sorted)){
			foreach ($fba_report_sorted as $k => $v){
				$line_arr = array();
				$line_arr[0] = $v["productcode"];
				$line_arr[1] = $v["product"];
				$line_arr[2] = $v["Supplier"];
				$line_arr[3] = $v["amazon_fba_avail"];
				$line_arr[4] = $v["fba_min_price"];
				$line_arr[5] = $v["our_min_price"];
                $line_arr[6] = $v["last_order_price"];
                $line_arr[7] = $v["discounted_product"];
				$line_arr[8] = $v["StockingDays"];
				$line_arr[9] = $search_data["amazon_fba_restocking_report"]["Amazon_FBA_Restocking_period_days"];
				$line_arr[10] = $v["N_avg"];
				$line_arr[11] = $v["sigma"];
				$line_arr[12] = $v["Re_stock_quantity"];
				$line_arr[13] = $v["cost_to_us"];
				$line_arr[14] = $v["Extended"];
                $line_arr[15] = $v["upc"];

		                $line = implode("\t", $line_arr);
		                unset($line_arr);

				print($line."\r\n");
			}
		}
		die();
	}

        # Assign the Smarty variables
        $smarty->assign("fba_report", $fba_report_sorted);
        $smarty->assign("total_Extended", price_format($total_Extended));

/*
        $smarty->assign("navigation_script", "amazon_fba_restocking_report.php?mode=search");
        $smarty->assign("total_items", $total_items);
        $smarty->assign("first_item", $first_page+1);
        $smarty->assign("last_item", min($first_page+$objects_per_page, $total_items));
*/




	$report2 = func_query("
Select 
            P.productcode As 'SKU',
            P.product As 'Product',
            M.manufacturer As 'Supplier',
            SUM(OD.amount) As 'Sold amount',
            COUNT(distinct O.orderid) As 'Sales',
            PR.price As 'X-Cart price',
            MAX(OD.price) As 'Maximum sale price (MFN price)',
            MAX(OD.price) - PR.price As 'Price delta',
            cidev_get_amazon_price(P.productid) As `Amazon price`,
            cidev_get_minimum_amazon_price(P.productid) As 'Our minimum FBA price'
            
From xcart_products P
        left join xcart_order_details OD ON OD.productid = P.productid
        inner join xcart_orders O ON O.orderid = OD.orderid and O.amazon_fulfillment_channel = 'MFN'
        inner join xcart_order_groups OG ON OG.orderid = O.orderid and OG.manufacturerid = P.manufacturerid and OG.cb_status = 'P'
        left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid
        left join xcart_pricing PR ON PR.productid = P.productid and PR.quantity = 1
where P.forsale = 'Y' and /*P.productcode like 'ALV-%' and*/ O.date > UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL -$Amazon_FBA_Report_depth_months MONTH))
Group By P.productid
HAVING GROUP_CONCAT(O.amazon_fulfillment_channel) like '%MFN%' and MAX(OD.price) - PR.price > 0
Order By (SUM(OD.amount)*COUNT(distinct O.orderid)*IF(MAX(OD.price) - PR.price<=0,1,ABS(MAX(OD.price) - PR.price)*10)) desc
	");

        if ($download == "2" && !empty($report2)){
                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=\"report2.csv\"");

		$header_arr = array();

		foreach ($report2 as $k => $v){
		  if (!empty($v) && is_array($v)){

		    $line_arr = array();
		    foreach($v as $kk => $vv){
			if ($k == "0"){
				$header_arr[] = $kk;
			} else {
				$line_arr[] = $vv;
			}
		    }

		    if ($k == "0"){
			    $header_line = implode("\t", $header_arr);
			    print($header_line."\r\n");
		    }
		    else {
                            $line = implode("\t", $line_arr);
                            unset($line_arr);

                            print($line."\r\n");
		    }
		  }
		}
                die();
        }

	$smarty->assign("report2", $report2);

/*
        $report3 = func_query("
Select 
            P.productcode As 'SKU',
            P.product As 'Product',
            M.manufacturer As 'Supplier',
            SUM(OD.amount) As 'Sold amount',
            COUNT(distinct O.orderid) As 'Sales',
            PR.price As 'X-Cart price',
            MAX(OD.price) As 'Maximum sale price',
            MAX(OD.price) - PR.price As 'Price delta',
            cidev_get_amazon_price(P.productid) As `Amazon price`,
            cidev_get_minimum_amazon_price(P.productid) As 'Our minimum FBA price',
            M.d_enable_feed As 'Has inventory feed'
From xcart_products P
        left join xcart_order_details OD ON OD.productid = P.productid
        inner join xcart_orders O ON O.orderid = OD.orderid and O.amazon_fulfillment_channel != 'AFN' and O.amazon_fulfillment_channel != 'MFN'
        inner join xcart_order_groups OG ON OG.orderid = O.orderid and OG.manufacturerid = P.manufacturerid and OG.cb_status = 'P'
        left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid
        left join xcart_pricing PR ON PR.productid = P.productid and PR.quantity = 1 
where P.forsale = 'Y' and O.date > UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL -$Amazon_FBA_Report_depth_months MONTH)) and M.d_enable_feed = 'Y'
Group By P.productid
Order By (SUM(OD.amount)*COUNT(distinct O.orderid)*IF(MAX(OD.price) - PR.price<=0,1,ABS(MAX(OD.price) - PR.price)*10)) desc
        ");
*/

$report3 = func_query("
Select 
			P.productcode As 'SKU',
			P.product As 'Product name',
			M.manufacturer As 'Distributor',
			M.d_enable_feed As 'Has inventory feed',
			SUM(OD.amount) As 'Units sold',
			COUNT(distinct O.orderid) As 'Orders received',
			PR.price As 'X-Cart price',
			MAX(OD.price) As 'Maximum sale price',
			MAX(OD.price) - PR.price As 'Price delta',
			COALESCE(PriceBounce.fba_get_bb_price(P.productid),'No') As 'Parsed BuyBox price',
			cidev_get_amazon_price(P.productid) As `Our MFN price`,
			cidev_get_minimum_amazon_price(P.productid) As 'Our min FBA price',
			IF(PriceBounce.fba_get_bb_price(P.productid) = 0,COALESCE(1 / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, $Amazon_FBA_Report_Tau),'No'), COALESCE(0.1*(PriceBounce.fba_get_bb_price(P.productid) - xcart_k.cidev_get_minimum_amazon_price(P.productid)) / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, $Amazon_FBA_Report_Tau),'No')) As 'Composite index (E)'
/*			COALESCE((PriceBounce.fba_get_bb_price(P.productid) - cidev_get_minimum_amazon_price(P.productid)) / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, $Amazon_FBA_Report_Tau),'No') As 'Composite index (E)'*/
From xcart_products P
		left join xcart_order_details OD ON OD.productid = P.productid
		inner join xcart_orders O ON O.orderid = OD.orderid and O.amazon_fulfillment_channel != 'AFN' and O.amazon_fulfillment_channel != 'MFN'
		inner join xcart_order_groups OG ON OG.orderid = O.orderid and OG.manufacturerid = P.manufacturerid and OG.cb_status = 'P'
		left join xcart_manufacturers M ON M.manufacturerid = P.manufacturerid
		left join xcart_pricing PR ON PR.productid = P.productid and PR.quantity = 1 
where P.forsale = 'Y' and O.date > UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL -$Amazon_FBA_Report_depth_months MONTH)) and M.d_enable_feed = 'Y' AND P.amazon_enabled != 'Y'
Group By P.productid
Order By IF(PriceBounce.fba_get_bb_price(P.productid) = 0,COALESCE(1 / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, $Amazon_FBA_Report_Tau),0),
	    COALESCE(0.1*(PriceBounce.fba_get_bb_price(P.productid) - xcart_k.cidev_get_minimum_amazon_price(P.productid)) / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, $Amazon_FBA_Report_Tau),0)) desc
/*COALESCE((PriceBounce.fba_get_bb_price(P.productid) - cidev_get_minimum_amazon_price(P.productid)) / P.cost_to_us + PriceBounce.fba_Get_Xi(P.productid, $Amazon_FBA_Report_Tau),0) desc, (SUM(OD.amount)*COUNT(distinct O.orderid)*IF(MAX(OD.price) - PR.price<=0,1,ABS(MAX(OD.price) - PR.price)*10)) desc*/
");


	if (!empty($report3)){
		foreach ($report3 as $k => $v){
			if ($v["Composite index (E)"] != "No"){
				$report3[$k]["Composite index (E)"] = price_format($v["Composite index (E)"]);
			}
		}
	}

        if ($download == "3" && !empty($report3)){
                header("Content-Type: application/force-download");
                header("Content-Disposition: attachment; filename=\"report3.csv\"");

                $header_arr = array();

                foreach ($report3 as $k => $v){
                  if (!empty($v) && is_array($v)){

                    $line_arr = array();
                    foreach($v as $kk => $vv){
                        if ($k == "0"){
                                $header_arr[] = $kk;
                        } else {
                                $line_arr[] = $vv;
                        }
                    }

                    if ($k == "0"){
                            $header_line = implode("\t", $header_arr);
                            print($header_line."\r\n");
                    }
                    else {
                            $line = implode("\t", $line_arr);
                            unset($line_arr);

                            print($line."\r\n");
                    }
                  }
                }
                die();
        }

        $smarty->assign("report3", $report3);

//func_print_r($report3);
}


if (!empty($search_data["amazon_fba_restocking_report"])){
	$smarty->assign("search_data", $search_data["amazon_fba_restocking_report"]);
}

$smarty->assign("mode", $mode);
$smarty->assign("main", "amazon_fba_restocking_report");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);

?>
