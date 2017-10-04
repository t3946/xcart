<?php
/**
 * @var \Xcart\Product $oProduct
 */
global $REQUEST_METHOD, $smarty, $config, $productid, $section_name;

use Modules\Core\Helpers\GeoipHelper;
use Modules\Shipping\Helpers\ShippingHelper;

#
## ALWAYS USE IT if you do not require auth.php
###
define('AREA_TYPE', 'C'); // if add this, then xid is used.

define('x_session_save_to_db__do_not_use', 'Y');

require "./top.inc.php";
require "./init.php"; #uses xid.X

$current_area="C";
$aResult = [];


if ($REQUEST_METHOD == 'POST') {

    list($products, $sGoogleAnaliticsParam) = Xcart\Helpers\SliderData::getSliderData($section_name, $productid);

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
            if ($oProduct->isGroupRoot()) {
                $smarty->assign('product', $oProduct);
            }


            $aResult['items'][] =
                [
                    'productid' => $oProduct->productid,
                    'clean_url' => $oProduct->getRelativeURL(),
                    'price' => $oProduct->getFrontendPrice(),
                    'category' => $oProduct->getMainCategory()->category,
                    'brand' => $oBrand->brand,
                    'product' => $oProduct->getTitle(),
                    'thumb' => $oProduct->isGroupRoot() ? $smarty->fetch('group_thumbnail.tpl') : $smarty->fetch('product_thumbnail.tpl'),
                    'N_key' => $k + 1,
                    'ga_param' => $sGoogleAnaliticsParam,
                    'title' => $oProduct->getTitle(),
                    'is_group' => $oProduct->isGroupRoot()
                ];

		}
	}
    echo json_encode($aResult);
}
if ($REQUEST_METHOD == 'GET' && $section_name == 'shipping') {
    if ($_GET['product_id']) {
        $qty = intval($_GET['qty']);

        $state_model = GeoipHelper::getGeoipLocation(Xcart\App\Main\Xcart::app()->request->getUserIP())->state_model;

        $shipping_rate = ShippingHelper::getStateMinShipping($_GET['product_id'], $qty, $state_model);

        $smarty->assign('shipping_rate', $shipping_rate);
        $smarty->assign('shipping_state', $state_model);
        $smarty->assign('qty', $qty);

        echo $smarty->fetch('customer/main/product_shipping.tpl');
    }
}
