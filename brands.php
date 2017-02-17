<?php
#
# brands.php, random
#

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



if($active_modules["Brands"])
    include $xcart_dir."/modules/Brands/customer_brands_list.php";
else
	func_header_location("home.php");

if ($config["Appearance"]["Enable_surf_stats"] == "Y"){
    Modules\User\Helpers\SurfingHelper::logSurfPath(['resource_type' => Modules\User\Models\SurfPath::GOAL_TYPE_BRAND]);
}

# Assign the current location line
$smarty->assign("location", $location);

func_display("customer/home.tpl",$smarty);
