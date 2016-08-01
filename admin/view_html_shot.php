<?php

require "./auth.php";
require $xcart_dir."/include/security.php";
include_once $xcart_dir."/include/class/classProducts.php";
include_once $xcart_dir."/include/class/classOrderDetail.php";

x_load("category");
x_load('product');

$oProduct = new classProduct(331743);
$productid = $oProduct->getProductId();

$oOrder = new classOrderDetail(62790);


$smarty->assign("oProduct", $oProduct);

$smarty->assign("product", $oProduct->getProductTableValues());
$product_info = $oProduct->getProductTableValues();

//echo func_product_price($product_info);
//$oProduct->setField('taxed_price');
$oProduct->setField('price',func_product_price($product_info));



include $xcart_dir."/modules/Wholesale_Trading/product.php";

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/main/product_detail.tpl",$smarty);
