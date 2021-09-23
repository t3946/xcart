<?php

define("IS_MULTILANGUAGE", true);
define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = array("descr", "disclaimer_text");

require "./auth.php";
require $xcart_dir."/include/security.php";

if(empty($active_modules['Brands']))
	func_header_location ("error_message.php?access_denied&id=25");
else
	include $xcart_dir."/modules/Brands/brands.php";

$smarty->assign("single_mode", $single_mode);

$smarty->assign("main","brands");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
