<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";

$oFilter = \Xcart\Filter::model(['f_id' => 1]);
$oFilter->setStoreFront(\Xcart\StoreFront::model(['storefrontid'=>$current_storefront]))->
setCategory(\Xcart\Category::model(['categoryid'=>58293]))->setFilterValuesSelected([
    \Xcart\FilterValue::model(['fv_id'=>10165]),
    \Xcart\FilterValue::model(['fv_id'=>12690])
]);
$a = $oFilter->getMoreFilterValues();
var_dump($a);