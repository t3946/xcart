<?php

namespace Modules\Product\Helpers;


use Modules\Product\Models\ProductModel;

class SupplierFeedHelper
{
    /**
     * @param ProductModel $model
     * @return ProductModel
     */
    public static function getEtaDate($model)
    {
        $todayDate = strtotime(date("Y-m-d"));

        if (($model->eta_date_lock == "Y")
            && ($model->getOldAttribute('eta_date_mm_dd_yyyy') > $todayDate)
            && (($model->getOldAttribute('eta_date_mm_dd_yyyy') > $model->eta_date_mm_dd_yyyy) || empty($model->eta_date_mm_dd_yyyy))
        ) {
            $model->eta_date_mm_dd_yyyy = $model->getOldAttribute('eta_date_mm_dd_yyyy');
        } else {
            $model->eta_date_lock = "N";
        }

        return $model;
    }

    /**
     * @param ProductModel $model
     * @return ProductModel
     */
    public static function getWeightOptions($model)
    {
        if ($model->weight_lock == 'Y' || (!$model->weight && $model->getOldAttribute('weight'))) {
            $model->weight = $model->getOldAttribute('weight');
        }
        if ($model->shipping_weight_lock == 'Y' || (!$model->shipping_weight && $model->getOldAttribute('shipping_weight'))) {
            $model->shipping_weight = $model->getOldAttribute('shipping_weight');
        }
        if ($model->dim_lock == 'Y') {
            $model->dim_x = $model->getOldAttribute('dim_x');
            $model->dim_y = $model->getOldAttribute('dim_y');
            $model->dim_z = $model->getOldAttribute('dim_z');
        } else {
            $aDimFeed = [$model->dim_x, $model->dim_y, $model->dim_z];
            $aDimOld = [$model->getOldAttribute('dim_x'), $model->getOldAttribute('dim_y'), $model->getOldAttribute('dim_z')];
            rsort($aDimFeed);
            rsort($aDimOld);
            $model->dim_x = empty($aDimFeed[0]) ? $aDimOld[0] : $aDimFeed[0];
            $model->dim_y = empty($aDimFeed[1]) ? $aDimOld[1] : $aDimFeed[1];
            $model->dim_z = empty($aDimFeed[2]) ? $aDimOld[2] : $aDimFeed[2];
        }
        if ($model->shipping_dim_lock == 'Y') {
            $model->shipping_dim_x = $model->getOldAttribute('shipping_dim_x');
            $model->shipping_dim_y = $model->getOldAttribute('shipping_dim_y');
            $model->shipping_dim_z = $model->getOldAttribute('shipping_dim_z');
        } else {
            $aShipDimFeed = [$model->shipping_dim_x, $model->shipping_dim_y, $model->shipping_dim_z];
            $aShipDimOld = [$model->getOldAttribute('shipping_dim_x'), $model->getOldAttribute('shipping_dim_y'), $model->getOldAttribute('shipping_dim_z')];
            rsort($aShipDimFeed);
            rsort($aShipDimOld);
            $model->shipping_dim_x = empty($aShipDimFeed[0]) ? $aShipDimOld[0] : $aShipDimFeed[0];
            $model->shipping_dim_y = empty($aShipDimFeed[1]) ? $aShipDimOld[1] : $aShipDimFeed[1];
            $model->shipping_dim_z = empty($aShipDimFeed[2]) ? $aShipDimOld[2] : $aShipDimFeed[2];
        }

        return $model;
    }

    /**
     * @param ProductModel $model
     * @return array
     */
    public static function getUPC($model)
    {
        $newUPC = ProductHelper::calculateUPC($model->upc);
        $oldUPC = $model->getOldAttribute('upc');
        if ($oldUPC != $newUPC) {
            $model->upc = $newUPC;
        } else {
            $model->upc = $oldUPC;
        }

        return [$model, $oldUPC != $newUPC];
    }
}