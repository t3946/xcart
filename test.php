<?php
session_start();


require "./top.inc.php";
require "./init.php";

global $xcart_dir;

require_once $xcart_dir."/include/class/classElasticSearch.php";


$classElastic = new classElasticSearch($config["ElasticSearch_options"],'www.artistsupplysource.com');
//$classElastic->setSource("*._id");
$classElastic->setType("product");
$classElastic->setMinScore(0.5);
//$classElastic->setProductId("376");


$classElastic->setSearchQuery($a);
$result = $classElastic->query();




