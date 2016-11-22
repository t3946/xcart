<?php
//session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

//var_dump(\Xcart\Product::model()->find(\Xcart\SQLBuilder::getInstance()->addCondition("productcode='CTI-RF-TRI-0158'"))->getProductsAvailOnAmazonParentWithChild(1));



$prod =  \Xcart\Product::model()->find(\Xcart\SQLBuilder::getInstance()->addCondition("productcode='CTI-TRI-0158'"))->getFields();
global $xcart_states_US;
$xcart_states_US = func_query("SELECT stateid, state, code, country_code, base_state_zipcode FROM $sql_tbl[states] WHERE base_state_zipcode!='' AND country_code='US'");
foreach ($xcart_states_US as $k => $v){
    $xcart_states_US[$k]["city"] = func_query_first_cell("SELECT city FROM $sql_tbl[geo_litecity_location] WHERE country='US' AND postalCode='$v[base_state_zipcode]'");
}

var_dump(func_get_amazon_shippings_for_all_states($prod));