<?php
@set_time_limit(0);

require "./auth.php";
require $xcart_dir."/include/security.php";

x_session_register("search_data");

$location[] = array("FBA ROI report (year based)", "");

if ($REQUEST_METHOD=="POST") {

    if ($mode == "search"){

//	$search_data["fba_roi_report"]["Amazon_FBA_Restocking_period_days"] = $Amazon_FBA_Restocking_period_days;

	x_session_save("search_data");

        func_header_location("fba_roi_report.php?mode=search");
    }
}

if ($mode == "search"){


#*insert debit cash
	db_query("INSERT INTO xcart_fba_roi_accounting
(edate,credit,debit,account,comments)
Select DATE(FROM_UNIXTIME(O.date)), 0, OD.amount*OD.price, 'cash', CONCAT('Sale order ', O.order_prefix, O.orderid)
From xcart_orders O
			left join xcart_order_details OD ON OD.orderid = O.orderid
			left join xcart_products P ON P.productid = OD.productid
			left join xcart_fba_roi_accounting A ON A.comments like CONCAT('%',O.order_prefix,O.orderid,'%') and A.account = 'cash'
where O.amazon_fulfillment_channel = 'AFN' and O.cb_status = 'P' and A.comments is NULL");

#insert credit inventory
        db_query("INSERT INTO xcart_fba_roi_accounting
(edate,credit,debit,account,comments)
Select DATE(FROM_UNIXTIME(O.date)), OD.amount*P.cost_to_us, 0, 'inventory', CONCAT('Sale order ', O.order_prefix, O.orderid)
From xcart_orders O
			left join xcart_order_details OD ON OD.orderid = O.orderid
			left join xcart_products P ON P.productid = OD.productid
			left join xcart_fba_roi_accounting A ON A.comments like CONCAT('%',O.order_prefix,O.orderid,'%') and A.account = 'inventory'
where O.amazon_fulfillment_channel = 'AFN' and O.cb_status = 'P' and A.comments is NULL");

#insert debit/credit equity
        db_query("INSERT INTO xcart_fba_roi_accounting
(edate,credit,debit,account,comments)
Select DATE(FROM_UNIXTIME(O.date)), OD.amount*P.cost_to_us*1.3, OD.amount*P.cost_to_us, 'equity', CONCAT('Sale order ', O.order_prefix, O.orderid)
From xcart_orders O
			left join xcart_order_details OD ON OD.orderid = O.orderid
			left join xcart_products P ON P.productid = OD.productid
			left join xcart_fba_roi_accounting A ON A.comments like CONCAT('%',O.order_prefix,O.orderid,'%') and A.account = 'equity'
where O.amazon_fulfillment_channel = 'AFN' and O.cb_status = 'P' and A.comments is NULL");


$select = array();
$select[] = func_query_first("
Select 
			'notes payable',
			IF(SUM(A.debit)-SUM(A.credit)>0,SUM(A.debit)-SUM(A.credit),0) As Debit,
			IF(SUM(A.debit)-SUM(A.credit)<0,ABS(SUM(A.debit)-SUM(A.credit)),0) As Credit
From xcart_fba_roi_accounting A
Where A.account = 'notes payable';
");

$select[] = func_query_first("
Select 
			'cash',
			IF(SUM(A.debit)-SUM(A.credit)>0,SUM(A.debit)-SUM(A.credit),0) As Debit,
			IF(SUM(A.debit)-SUM(A.credit)<0,ABS(SUM(A.debit)-SUM(A.credit)),0) As Credit
From xcart_fba_roi_accounting A
Where A.account = 'cash';
");

$select[] = func_query_first("
Select 
			'inventory',
			IF(SUM(A.debit)-SUM(A.credit)>0,SUM(A.debit)-SUM(A.credit),0) As Debit,
			IF(SUM(A.debit)-SUM(A.credit)<0,ABS(SUM(A.debit)-SUM(A.credit)),0) As Credit
From xcart_fba_roi_accounting A
Where A.account = 'inventory';
");

$select[] = func_query_first("
Select 
			'fba_expense',
			IF(SUM(A.debit)-SUM(A.credit)>0,SUM(A.debit)-SUM(A.credit),0) As Debit,
			IF(SUM(A.debit)-SUM(A.credit)<0,ABS(SUM(A.debit)-SUM(A.credit)),0) As Credit
From xcart_fba_roi_accounting A
Where A.account = 'fba_expense';
");

$select[] = func_query_first("
Select 
			'equity',
			SUM(A.debit) As Debit,
			SUM(A.credit) As Credit
From xcart_fba_roi_accounting A
Where A.account = 'equity';
");

$select[] = func_query_first("
Select 'Time_period_in_days', Round((UNIX_TIMESTAMP(DATE(NOW())) - F.report_date) / 86400,0) As 'Time_period_in_days'
From xcart_k.xcart_cidev_amazon_fba_products F
Order By F.report_date
Limit 1;
");

//func_print_r($select);


        $smarty->assign("select", $select);

//func_print_r($report3);
}


if (!empty($search_data["fba_roi_report"])){
	$smarty->assign("search_data", $search_data["fba_roi_report"]);
}

$smarty->assign("mode", $mode);
$smarty->assign("main", "fba_roi_report");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);

?>
