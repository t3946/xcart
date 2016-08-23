<?php
global $login, $xcart_dir;

require "./auth.php";
require_once $xcart_dir . "/include/class/classCustomer.php";

if (empty($login))
    func_header_location("error_message.php?antibot_error");

if (!empty($active_modules["Simple_Mode"]))
    func_header_location($current_location . DIR_ADMIN . "/home.php");

$location[] = array("Verificator home", "");

$oCustomer = new classCustomer(['login' => $login]);
$smarty->assign('oCustomer', $oCustomer);

if (!empty($login))
    $smarty->assign("location", $location);

$smarty->assign("main", "home");

@include $xcart_dir . "/modules/gold_display.php";
func_display("verificator/home.tpl", $smarty);

