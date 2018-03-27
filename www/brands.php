<?php
#
# brands.php, random
#

use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;

require "./auth.php";

$brandid = abs(intval($brandid));

x_session_register("notify_email");
$smarty->assign("notify_email", $notify_email);

if (
    isset($brandid)
    && !empty($brandid)
    && $config['SEO']['clean_urls_enabled'] == 'Y'
    && !defined("DISPATCHED_REQUEST")
) {
    func_clean_url_permanent_redirect('M', $brandid);
}



if($active_modules["Brands"]) {
    if ($brandid && ($brand_model = \Modules\Brand\Models\BrandModel::objects()->get(['brandid' => $brandid]))) {
        if ($brand_model->parent_brand_id) {
            func_clean_url_permanent_redirect('M', $brand_model->parent_brand_id);
        }
    }
    include $xcart_dir . "/modules/Brands/customer_brands_list.php";
}
else
	func_header_location("home.php");

//if ($config["Appearance"]["Enable_surf_stats"] == "Y"){
//    SurfingHelper::logSurfPath([
//        'resource_type' => SurfPathModel::GOAL_TYPE_BRAND,
//        'resource_id' => $brandid,
//        'additional_data' => SurfingHelper::getSurfPathAdditionalData([
//            'resource_type' => SurfPathModel::GOAL_TYPE_BRAND,
//            'cidev_filters_tree_sorted' => $cidev_filters_tree_sorted
//        ])
//    ]);
//}

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
