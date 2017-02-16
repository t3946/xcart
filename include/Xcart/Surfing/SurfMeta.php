<?php

namespace Xcart\Surfing;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Request\XcartSession;

class SurfMeta extends AutoMetaModel
{
    private static $_instance = null;

    public static function tableName()
    {
        return 'xcart_cidev_surf_meta';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className()
            ]
        ];
    }

    static public function getInstance()
    {
        global $detect_isMobile_was_created, $current_storefront;

        if (is_null(self::$_instance)) {
            $oSession = new XcartSession();

            if ($oSession->getId()) {
                self::$_instance = self::objects()->filter(["sessid" => $oSession->getId()])->get();

                if (is_null(self::$_instance)) {
                    self::$_instance =  new self(
                        [
                            "sessid"         => $oSession->getId(),
                            "date"           => time(),
                            "is_mobile"      => ($detect_isMobile_was_created ? "Y" : "N"),
                            "goal_order"     => 'N',
                            "goal_checkout"  => 'N',
                            "goal_addtocart" => 'N',
                            "goal_search"    => 'N',
                            "points_visited" => '0',
                            "last_update"    => time(),
                            "storefrontid"   => $current_storefront,
                        ]
                    );
                    self::$_instance->save();
                }
            }
        }

        return self::$_instance;
    }
}