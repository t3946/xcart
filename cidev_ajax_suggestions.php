<?php
/**
 * @var \Xcart\Product $oProduct
 */
global $REQUEST_METHOD, $smarty, $config;
#
## ALWAYS USE IT if you do not require auth.php
###
define('AREA_TYPE', 'C'); // if add this, then xid is used.

define('x_session_save_to_db__do_not_use', 'Y');

require "./top.inc.php";
require "./init.php"; #uses xid.X

$current_area="C";

list($products, $sGoogleAnaliticsParam) = Xcart\Helpers\SliderData::getSliderData($section_name, $productid);

if ($REQUEST_METHOD == 'POST') {
	if (!empty($products)){
        $aResult = [];
        foreach ($products as $k => $oProduct){
            $oThumbImage = null;
            $aThumbImages = \Modules\Product\Models\ImageTModel::objects()->filter(['id' => $oProduct->productid])->all();
            if (!empty($aThumbImages)) {
                $oThumbImage = reset($aThumbImages);
            }
            $oBrand = \Xcart\Brand::objects()->get(['brandid' => $oProduct->brandid]);
            $oSplash = \Xcart\Images\Splash::objects()->filter(['id' => (int) $oProduct->splash_id])->get();
            $smarty->assign('splash', $oSplash);
            $smarty->assign('config', $config);
            $smarty->assign('tmbn_url', $oThumbImage->getURL());
            $smarty->assign('product', $oProduct->product);

            $aResult['items'][] = [
                'productid' => $oProduct->productid,
                'clean_url' => $oProduct->getURL(),
                'price' => $oProduct->getPrice(),
                'category' => $oProduct->getMainCategory()->category,
                'brand' => $oBrand->brand,
                'product' => $oProduct->product,
                'thumb' => $smarty->fetch('product_thumbnail.tpl'),
                'N_key' => $k + 1,
                'ga_param' => $sGoogleAnaliticsParam,
                'title' => $oProduct->product];

		}
		echo json_encode($aResult);
	}
}
