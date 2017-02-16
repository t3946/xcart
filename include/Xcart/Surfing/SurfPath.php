<?php

namespace Xcart\Surfing;

use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

class SurfPath extends AutoMetaModel
{
    const GOAL_TYPE_ADD_TO_CART      = 'A';
    const GOAL_TYPE_CHECKOUT         = 'K';
    const GOAL_TYPE_SEARCH           = 'S';
    const GOAL_TYPE_ORDER            = 'O';
    const GOAL_TYPE_REFERER          = 'R';
    const GOAL_TYPE_PRODUCT          = 'P';
    const GOAL_TYPE_BRAND            = 'B';
    const GOAL_TYPE_CATEGORY         = 'C';
    const GOAL_TYPE_STATIC_PAGE      = 'T';
    const GOAL_TYPE_HOME_PAGE        = 'H';
    const GOAL_TYPE_TECHNICAL_SEARCH = 'L';

    private $goals_arr
        = [
            self::GOAL_TYPE_ADD_TO_CART => "goal_addtocart",
            self::GOAL_TYPE_CHECKOUT    => "goal_checkout",
            self::GOAL_TYPE_SEARCH      => "goal_search",
            self::GOAL_TYPE_ORDER       => "goal_order",
        ];

    public static function tableName()
    {
        return 'xcart_cidev_surf_path';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
        ];
    }

    public static function logSurfPath(array $params = [])
    {
        global $clean_url_data, $cidev_filters_tree_sorted, $xcart_http_host;  //TODO remove globals;

        $model = new self($params);

        $sReferalUrl = null;
        $aGoalArray = [];

        if (defined("IS_ROBOT") || !Xcart::app()->request->session->getId()) {
            return false;
        }

        $oSurfMeta = SurfMeta::getInstance();

        if ($oSurfMeta->id) {
            $aReferalUrl = parse_url(Xcart::app()->request->getReferrer());
            $aUri = Xcart::app()->request->getQueryArray();

            if ($aReferalUrl['host'] != $xcart_http_host) {
                $sPath = ltrim($aReferalUrl['path'], '/');
                $sReferalUrl = $aReferalUrl['host'] . (empty($sPath) ? '' : '/' . $sPath) . (empty($aReferalUrl['query']) ? '' : "?{$aReferalUrl['query']}");
                $aUri = Xcart::app()->request->getQueryArray();

                if (!empty($aUri['origin']) && !empty($aReferalUrl['host'])) {
                    $sReferalUrl .= (empty($aReferalUrl['query']) ? '?' : '&') . http_build_query(['origin' => $aUri['origin']]);
                }
            }

            if (in_array($model->resource_type, [self::GOAL_TYPE_ADD_TO_CART, self::GOAL_TYPE_CHECKOUT, self::GOAL_TYPE_SEARCH, self::GOAL_TYPE_ORDER])) {
                $aGoalArray[$model->goals_arr[$model->resource_type]] = "Y";
            }

            $oSurfMeta->points_visited++;
            $oSurfMeta->setAttributes(array_merge($oSurfMeta->getAttributes(), $aGoalArray));

            if (in_array($model->resource_type, [self::GOAL_TYPE_PRODUCT, self::GOAL_TYPE_CATEGORY, self::GOAL_TYPE_BRAND, self::GOAL_TYPE_STATIC_PAGE])) {
                $model->resource_id = $clean_url_data["resource_id"];
            }

            $model->meta_id = $oSurfMeta->id;
            $model->timestamp = time();
            $model->position = $oSurfMeta->points_visited;

            if ($model->resource_type == self::GOAL_TYPE_SEARCH) {
                $REQUEST_URI_arr = explode("/", $aUri["request_uri"]);
                $model->additional_data = $REQUEST_URI_arr[2];
            }
            if (in_array($model->resource_type, [self::GOAL_TYPE_CATEGORY, self::GOAL_TYPE_BRAND, self::GOAL_TYPE_SEARCH])
                && !empty($cidev_filters_tree_sorted)
                && is_array($cidev_filters_tree_sorted)
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
                    if (!empty($model->additional_data)) {
                        $model->additional_data .= ',';
                    }
                    $model->additional_data .= implode(",", $selected_fv_id_arr);
                }
            }
            if ($model->meta_id) {
                if ($model->isValid()) {
                    $model->save();
                }
            }

            if (!is_null($sReferalUrl)) {
                $oSurfMeta->points_visited++;
                $oSurfMeta->referal_url = addslashes($sReferalUrl);
                /** @var AutoMetaModel $oReferer */
                list($oReferer, $created) = Referer::objects()->getOrCreate(['referer' => (string)substr($sReferalUrl, 0, 767)]);
                $oReferer->visits++;
                $oReferer->save();

                if ($oSurfMeta->id) {
                    (new self([
                            'meta_id'         => $oSurfMeta->id,
                            'resource_id'     => $oReferer->referer_id,
                            'resource_type'   => self::GOAL_TYPE_REFERER,
                            'timestamp'       => time(),
                            'position'        => $oSurfMeta->points_visited,
                            'additional_data' => Xcart::app()->request->getUserAgent(),
                        ]
                    ))->save();
                }
            }

            $oSurfMeta->save();

            return true;
        }

        return false;
    }
}