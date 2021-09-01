<?php
require './auth.php';
if ($REQUEST_METHOD == 'POST') {
	db_query("INSERT INTO xcart_cidev_manufacturers_pass_view_log (manufacturerid,date,login) VALUES ('$manufacturerid', '".time()."', '$login')");
}
?>
