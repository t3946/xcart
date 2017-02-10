<?php

namespace Xcart\Surfing;

use Xcart\Data;
use Xcart\App\Request\XcartSession;

class SurfMeta extends Data
{
    private static $_instance = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'cidev_surf_meta';
        parent::__construct($aParams);
    }

    static public function getInstance()
    {
        global $detect_isMobile_was_created, $current_storefront;
        if (is_null(self::$_instance)) {
            $oSession = new XcartSession();
            if ($oSession->getId()) {
                self::$_instance = self::objects()->filter(["sessid" => $oSession->getId()])->get();
                if (is_null(self::$_instance)) {
                    self::$_instance = self::create(
                        ["sessid" => $oSession->getId(),
                            "date" => time(),
                            "is_mobile" => ($detect_isMobile_was_created ? "Y" : "N"),
                            "goal_order" => 'N',
                            "goal_checkout" => 'N',
                            "goal_addtocart" => 'N',
                            "goal_search" => 'N',
                            "points_visited" => '0',
                            "last_update" => time(),
                            "storefrontid" => $current_storefront,
                        ]);
                    self::$_instance->id = self::$_instance->_insert();
                }
            }
        }
        return self::$_instance;
    }
}