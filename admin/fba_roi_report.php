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
	$sql = <<<SQL
INSERT INTO xcart_fba_roi_accounting
(edate, credit, debit, account, comments, orderid, productid)
Select DATE(FROM_UNIXTIME(O.date)), __f1__, __f2__, :type, CONCAT('Sale order ', O.order_prefix, O.orderid,' SKU: ',P.productcode), O.orderid, OD.productid
From xcart_orders O
            left join xcart_k.xcart_order_groups OG ON OG.orderid = O.orderid
			left join xcart_order_details OD ON OD.orderid = O.orderid
			inner join xcart_products P ON P.productid = OD.productid AND P.manufacturerid = OG.manufacturerid
			left join xcart_fba_roi_accounting A ON A.orderid= O.orderid and A.productid = OD.productid and A.account = :type
where O.amazon_fulfillment_channel = 'AFN' and O.cb_status = 'P' and A.id is NULL
SQL;

    $sql1 = str_replace('__f1__', '0',$sql);
    $sql1 = str_replace('__f2__', 'OD.amount * OD.price',$sql1);
    $statement = \Xcart\Connection::getInstance()->prepare($sql1);
    $statement->bindValue('type', 'cash');
    $statement->execute();

    $sql2 = str_replace('__f1__', 'OD.amount*P.cost_to_us',$sql);
    $sql2 = str_replace('__f2__', '0',$sql2);
    $statement = \Xcart\Connection::getInstance()->prepare($sql2);
    $statement->bindValue('type', 'inventory');
    $statement->execute();

    $sql3 = str_replace('__f1__', 'OD.amount * P.cost_to_us * 1.3',$sql);
    $sql3 = str_replace('__f2__', 'OD.amount*P.cost_to_us',$sql3);
    $statement = \Xcart\Connection::getInstance()->prepare($sql3);
    $statement->bindValue('type', 'equity');
    $statement->execute();

$select = array();
$sql = <<<SQL
Select :type,
			IF(SUM(A.debit)-SUM(A.credit)>0,SUM(A.debit)-SUM(A.credit),0) As Debit,
			IF(SUM(A.debit)-SUM(A.credit)<0,ABS(SUM(A.debit)-SUM(A.credit)),0) As Credit
From xcart_fba_roi_accounting A
Where A.account = :type
SQL;    

$statement = \Xcart\Connection::getInstance()->prepare($sql);
$statement->bindValue('type', 'notes payable');
$select[] = $statement->fetch();

$statement->bindValue('type', 'cash');
$select[] = $statement->fetch();

$statement->bindValue('type', 'inventory');
$select[] = $statement->fetch();

$statement->bindValue('type', 'fba_expense');
$select[] = $statement->fetch();


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
