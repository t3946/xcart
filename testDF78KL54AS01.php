<?php
//session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";


var_dump(\Xcart\Connection::getInstance()->getConnection());

//$oOrder = \Xcart\Order::model(['orderid' => 72813]);

//$oOrder->submitOrderEntry();


/*
$aShippings = \Xcart\Shipping::model()->getShippingProcessor(\Xcart\Customer::model(['login' => 'anonymous-92700']), (new \Xcart\Manufacturer(12)));

$a = reset($aShippings);
foreach ($a as $oShippingProcessor) {
    $oShippingProcessor->setProducts([\Xcart\Product::model(['productid' => 20501])]);
    $m = $oShippingProcessor->getShippingRates();
    var_dump($m);
}
*/