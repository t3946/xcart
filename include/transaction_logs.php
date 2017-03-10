<?php
global $smarty;

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

$sql = <<<SQL
SELECT $sql_tbl[transaction_logs].*, 
$sql_tbl[payment_methods].payment_method, 
$sql_tbl[payment_methods].transaction_id_link, 
$sql_tbl[payment_methods].transaction_link_anchor, 
$sql_tbl[customers].firstname, 
$sql_tbl[customers].usertype 
FROM $sql_tbl[transaction_logs] 
LEFT JOIN $sql_tbl[payment_methods] ON $sql_tbl[payment_methods].paymentid=$sql_tbl[transaction_logs].paymentid 
LEFT JOIN $sql_tbl[customers] ON $sql_tbl[customers].login=$sql_tbl[transaction_logs].login 
WHERE $sql_tbl[transaction_logs].orderid='$orderid' 
ORDER BY $sql_tbl[transaction_logs].date DESC
SQL;
$transaction_logs = \Xcart\Connection::getInstance()->executeQuery($sql)->fetchAll();

if (!empty($transaction_logs)){
	foreach ($transaction_logs as $k_transaction_log => $v_transaction_log){

	    if (!empty($v_transaction_log["transaction_log"])){
		$unserialized_transaction_log = unserialize($v_transaction_log["transaction_log"]);
		if (is_array($unserialized_transaction_log)){
		    $transaction_logs[$k_transaction_log]["unserialized_transaction_log"] = $unserialized_transaction_log;

		    if (!empty($unserialized_transaction_log["details"][0]["issue"])){
			$transaction_logs[$k_transaction_log]["issue"] = $unserialized_transaction_log["details"][0]["issue"];
		    }
		}
	    }
	}
}

$smarty->assign("transaction_logs", $transaction_logs);
