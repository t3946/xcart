<?php
/**
 * @var \Xcart\Product $oProduct
 */
global $REQUEST_METHOD, $smarty, $config, $productid;
#
## ALWAYS USE IT if you do not require auth.php
###
define('AREA_TYPE', 'C'); // if add this, then xid is used.

define('x_session_save_to_db__do_not_use', 'Y');

require "./top.inc.php";
require "./init.php"; #uses xid.X

$current_area="C";
$aResult = [];

list($products, $sGoogleAnaliticsParam) = Xcart\Helpers\SliderData::getSliderData($section_name, $productid);

if ($REQUEST_METHOD == 'POST') {
	if (!empty($products)){

        foreach ($products as $k => $oProduct){
            $oThumb = $oProduct->getThumbnail();
            $oBrand = \Xcart\Brand::objects()->get(['brandid' => $oProduct->brandid]);
            $smarty->assign('splash', $oProduct->getSplash());
            $smarty->assign('config', $config);
            $smarty->assign('tmbn_url',null);
            if ($oThumb) {
                $smarty->assign('tmbn_url', $oThumb->getURL());
            }
            $smarty->assign('product', $oProduct->product);

            $aResult['items'][] = [
                'productid' => $oProduct->productid,
                'clean_url' => $oProduct->getRelativeURL(),
                'price' => $oProduct->getPrice(),
                'category' => $oProduct->getMainCategory()->category,
                'brand' => $oBrand->brand,
                'product' => $oProduct->product,
                'thumb' => $smarty->fetch('product_thumbnail.tpl'),
                'N_key' => $k + 1,
                'ga_param' => $sGoogleAnaliticsParam,
                'title' => $oProduct->product];

		}
	}
    echo json_encode($aResult);
}
