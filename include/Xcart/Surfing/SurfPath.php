<?php

namespace Surfing;

use Session\Session;
use Xcart\Customer;
use Xcart\Data;
use Xcart\App\Request\HttpRequest;

class SurfPath extends Data
{
    private $goals_arr = [
        "A" => "goal_addtocart",
        "K" => "goal_checkout",
        "S" => "goal_search",
        "O" => "goal_order",
    ];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'cidev_surf_path';
        parent::__construct($aParams);
    }

    public function logSurfPath($resource_type, $resource_id = "0")
    {
        global $login, $detect_isMobile_was_created, $current_storefront, $clean_url_data, $cidev_filters_tree_sorted;  //TODO remove globals;

        $oSession = (new Session());
        $oSurfMeta = \Surfing\SurfMeta::objects()->filter(['sessid' => $oSession->getId()])->get();
        if (!$oSurfMeta) {
            $sReferalUrl = '';
            $oHttpRequest = new HttpRequest();
            $sUri = $oHttpRequest->resolveRequestUri();
            if (strpos($sUri, "origin") !== false) {
                $sReferalUrl = str_replace("/dispatcher.php?request_uri=", "", $sUri);
                $sReferalUrl = $_SERVER["HTTP_HOST"] . $sReferalUrl;
                Customer::objects()->filter(['login' => $login])->get()->updateField('referer', addslashes($sReferalUrl));
            }

            $sReferer = $oHttpRequest->getReferrer();
            if (!empty($sReferer) && empty($sReferalUrl)) {
                $referal_url_arr1 = explode("//", $sReferer);
                $referal_url_arr2 = explode("/", $referal_url_arr1[1]);
                $sReferalUrl      = $referal_url_arr1[0] . "//" . $referal_url_arr2[0];
            }
            $cidev_surf_meta_arr = [
                "sessid"         => $oSession->getId(),
                "date"           => time(),
                "referal_url"    => addslashes($sReferalUrl),
                "is_mobile"      => ($detect_isMobile_was_created ? "Y" : "N"),
                "goal_order"     => 'N',
                "goal_checkout"  => 'N',
                "goal_addtocart" => 'N',
                "goal_search"    => 'N',
                "points_visited" => '1',
                "last_update"    => time(),
                "storefrontid"   => $current_storefront,
            ];
            if (in_array($resource_type, ["A", "K", "S", "O"])) {
                $cidev_surf_meta_arr[$this->goals_arr[$resource_type]] = "Y";
            }

            $cidev_surf_path_arr["meta_id"] = SurfMeta::create($cidev_surf_meta_arr)->_insert();

            if (in_array($resource_type, ["P", "C", "B", "T"])) {
                $resource_id = $clean_url_data["resource_id"];
            }
            $cidev_surf_path_arr["resource_id"]   = $resource_id;
            $cidev_surf_path_arr["resource_type"] = $resource_type;
            $cidev_surf_path_arr["timestamp"] = time();

            if (in_array($resource_type, ["C", "B", "S"]) && !empty($cidev_filters_tree_sorted) && is_array($cidev_filters_tree_sorted)) {
                $selected_fv_id_arr = [];
                foreach ($cidev_filters_tree_sorted as $v) {
                    if (!empty($v["filter_values"]) && is_array($v["filter_values"])) {
                        foreach ($v["filter_values"] as $tree_filter_values) {
                            if ($tree_filter_values["selected"] == "Y") {
                                $selected_fv_id_arr[] = $tree_filter_values["fv_id"];
                            }
                        }
                    }
                }
                if (!empty($selected_fv_id_arr)) {
                    $cidev_surf_path_arr["additional_data"] = implode(",", $selected_fv_id_arr);
                }
            }
            self::create($cidev_surf_path_arr)->_insert();
        }
    }
}