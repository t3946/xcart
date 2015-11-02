<?php
	$data = "";
	$query = $_SERVER['QUERY_STRING'];
	
	$options = explode("=",$query);
	if ( $options[1] == '1' )
		$data = "this packages are: php, nginx, mysql, smartgit (with GitHUB support)";
	else if ( $options[1] == '2' )
		$data = "the other packages used is: openssl, mysql workbench, heidiSQL, slack and more";
		
	echo json_encode(array("data"=>$data));
?>
