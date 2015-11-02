<?php
require "./auth.php";

if (empty($manufacturerid)){
	die("Empty manufacturer");
}

$where = "";

if (!empty($forsale) && ($forsale == "Y" || $forsale == "N")){
	$where = "AND forsale='$forsale'";
}

$products = db_query("SELECT productcode FROM $sql_tbl[products] WHERE manufacturerid='$manufacturerid' $where ORDER BY productcode");

while($product = db_fetch_array($products)) {
	print($product["productcode"]."\r\n<br />");
}

?>
