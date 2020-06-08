<?php

use Xcart\App\Main\Xcart;
require_once '../app/include/vendors/autoload.php';

$config = include '../app/config/settings.php';

Xcart::init($config);

Xcart::app()->request->redirect('main:contact_us_form', [], 301);

require "./auth.php";

require $xcart_dir."/include/categories.php";

# START: random:18298_18304_18324 [2009 Jun 08 09:50] 
if ($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands.php";
else
# END: random:18298_18304_18324 [2009 Jun 08 09:50] 
if($active_modules["Manufacturers"])
    include $xcart_dir."/modules/Manufacturers/customer_manufacturers.php";

require $xcart_dir."/include/help.php";

#
##
###
if (!isset($rma_zipcode)){
	$rma_zipcode = '';
}
$smarty->assign("rma_zipcode", $rma_zipcode);

if (!isset($rma_orderid)){
        $rma_orderid = '';
}
$smarty->assign("rma_orderid", $rma_orderid);

if (!isset($rma_email)){
        $rma_email = '';
}
$smarty->assign("rma_email", $rma_email);
###
##
#

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
?>
