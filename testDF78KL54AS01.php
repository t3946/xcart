<?php
session_start();

ini_set('memory_limit', '512M');
set_time_limit(0);

require "./top.inc.php";
require "./init.php";


echo Xcart\Product::model(['productid'=>76912])->getAmazonFBAAvailExcludedProcessing();