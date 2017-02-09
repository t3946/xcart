<?php

namespace Xcart\Surfing;

use Xcart\App\Request\XcartSession;
use Xcart\Data;
use Xcart\App\Request\HttpRequest;

class SurfPath extends Data
{
    const GOAL_TYPE_ADD_TO_CART = 'A';
    const GOAL_TYPE_CHECKOUT = 'K';
    const GOAL_TYPE_SEARCH = 'S';
    const GOAL_TYPE_ORDER = 'O';
    const GOAL_TYPE_REFERER = 'R';
    const GOAL_TYPE_PRODUCT = 'P';
    const GOAL_TYPE_BRAND = 'B';
    const GOAL_TYPE_CATEGORY = 'C';
    const GOAL_TYPE_STATIC_PAGE = 'T';
    const GOAL_TYPE_HOME_PAGE = 'H';
    const GOAL_TYPE_TECHNICAL_SEARCH = 'L';

    private $goals_arr = [
        self::GOAL_TYPE_ADD_TO_CART => "goal_addtocart",
        self::GOAL_TYPE_CHECKOUT => "goal_checkout",
        self::GOAL_TYPE_SEARCH => "goal_search",
        self::GOAL_TYPE_ORDER => "goal_order",
    ];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'cidev_surf_path';
        parent::__construct($aParams);
    }

    public function logSurfPath()
    {
        global $detect_isMobile_was_created, $current_storefront, $clean_url_data, $cidev_filters_tree_sorted, $xcart_http_host;  //TODO remove globals;
        $sReferalUrl = '';
        $aGoalArray = [];
        $oSession = new XcartSession();
        if (defined("IS_ROBOT") || !$oSession->getId()){
            return false;
        }

        $oHttpRequest = new HttpRequest();
        $oSurfMeta = SurfMeta::objects()->filter(['id' => $oSession->getMetaId()])->get();
        $aReferalUrl = parse_url($oHttpRequest->getReferrer());
        if ($aReferalUrl['host'] != $xcart_http_host) {
            $sPath = ltrim($aReferalUrl['path'], '/');
            $sReferalUrl = $aReferalUrl['host'] . (empty($sPath) ? '' : '/'. $sPath) . (empty($aReferalUrl['query']) ? '' : "?{$aReferalUrl['query']}");
        }
        if (in_array($this->resource_type, [self::GOAL_TYPE_ADD_TO_CART, self::GOAL_TYPE_CHECKOUT, self::GOAL_TYPE_SEARCH, self::GOAL_TYPE_ORDER])) {
            $aGoalArray[$this->goals_arr[$this->resource_type]] = "Y";
        }

        $oSurfMeta->points_visited++;
        $oSurfMeta->fill(array_merge($oSurfMeta->getFields(), $aGoalArray));

        if (in_array($this->resource_type, [self::GOAL_TYPE_PRODUCT, self::GOAL_TYPE_CATEGORY, self::GOAL_TYPE_BRAND, self::GOAL_TYPE_STATIC_PAGE])) {
            $this->resource_id = $clean_url_data["resource_id"];
        }
        $this->meta_id = $oSurfMeta->id;
        $this->timestamp = time();
        $this->position = $oSurfMeta->points_visited;

        if (in_array($this->resource_type, [self::GOAL_TYPE_CATEGORY, self::GOAL_TYPE_BRAND, self::GOAL_TYPE_SEARCH]) &&
            !empty($cidev_filters_tree_sorted) &&
            is_array($cidev_filters_tree_sorted)
        ) {
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
                $this->additional_data = implode(",", $selected_fv_id_arr);
            }
        }
        $this->_insert();

        if (!empty($sReferalUrl)) {
            $oSurfMeta->points_visited++;
            $oSurfMeta->referal_url = addslashes($sReferalUrl);
            $oReferer = Referer::objects()->filter(['referer' => $sReferalUrl])->get();
            if (!$oReferer) {
                $oReferer = Referer::create(['referer' => addslashes($sReferalUrl)]);
                $oReferer->referer_id = $oReferer->_insert();
            }
            $oReferer->updateField('visits', $oReferer->visits + 1);
            self::create([
                'meta_id' => $oSurfMeta->id,
                'resource_id' => $oReferer->referer_id,
                'resource_type' => self::GOAL_TYPE_REFERER,
                'timestamp' => time(),
                'position' => ($oSurfMeta->points_visited)
            ])->_insert();
        }

        $oSurfMeta->_update();
        return true;
    }
}