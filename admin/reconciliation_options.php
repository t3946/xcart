<?php
if ($REQUEST_METHOD == 'POST'){

	if ($mode == 'Update_Reconciliation'){
        	if (!empty($Reconciliations) && is_array($Reconciliations)){
                	foreach ($Reconciliations as $k => $v){
                        	db_query("UPDATE $sql_tbl[reconciliation_search_keyphrases] SET search_keyphrase='$v[search_keyphrase]', code='$v[code]', expense_description='$v[expense_description]' WHERE id='$k'");
	                }
        	}
	} elseif ($mode == 'Reconciliation_add'){
		db_query("INSERT INTO $sql_tbl[reconciliation_search_keyphrases] (search_keyphrase) VALUES ('')");
        } elseif ($mode == 'Reconciliation_delete' && !empty($Reconciliation_delete)){
		db_query("DELETE FROM $sql_tbl[reconciliation_search_keyphrases]  WHERE id='$Reconciliation_delete'");
	}

	$top_message["content"] = 'Done.';
        $top_message["type"] = "I";

	func_header_location("configuration.php?option=Reconciliation");
}

$Reconciliations = func_query("SELECT * FROM $sql_tbl[reconciliation_search_keyphrases] ORDER BY code ASC");
$smarty->assign("Reconciliations", $Reconciliations);
?>
