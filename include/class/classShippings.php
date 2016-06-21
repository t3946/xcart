<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";
require_once $xcart_dir."/include/class/classProducts.php";
require_once $xcart_dir."/include/class/classShipping.php";

class classShippings extends classShipping
{

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "shipping";
        $this->sPrimaryKeyFiled = "shippingid";
        parent::__construct($iId);
    }

    public function getShippingInfo($iShippingId) {
        return func_query("SELECT * FROM ".$this->sql_tbl['shipping']." WHERE shippingid = $iShippingId");
    }

    public function getShippingWeight($iProductId, $iShippingId, $iAmount = 1, $aProduct = array(), $aShipping = array(), $bUseShippingParametrs = true) {
        if (empty($aProduct)) {
            $classProducts = new classProducts();
            $aProduct = $classProducts->getProductInfo($iProductId);
            unset ($classProducts);
        }
        if (empty($aShipping)) {
            $aShipping = $this->getShippingInfo($iShippingId);
        }

        if (empty($aProduct["weight"]) || floatval($aProduct["weight"]) == 0) {
            $aProduct["weight"] = "0.1";
        }

        $real_weight = $aProduct["weight"] * $iAmount;

        if (!empty($aProduct["shipping_weight"]) && floatval($aProduct["shipping_weight"]) > 0 && $bUseShippingParametrs)
            $real_weight = $aProduct["shipping_weight"] * $iAmount;

        if (($aProduct["shipping_dim_x"]||$aProduct["shipping_dim_y"]||$aProduct["shipping_dim_z"]) && $bUseShippingParametrs)
            $Volume = $aProduct["shipping_dim_x"] * $aProduct["shipping_dim_y"] * $aProduct["shipping_dim_z"] * $iAmount; else
            $Volume = $aProduct["dim_x"] * $aProduct["dim_y"] * $aProduct["dim_z"] * $iAmount;

        if ($Volume > $aShipping["vol_threshold"] && !empty($aShipping["dim_factor"])) {
            $weight = max($real_weight, ($Volume / $aShipping["dim_factor"]));
        } else {
            $weight = $real_weight;
        }

        return $weight;
    }

    public function getProductsShippingWeight($iShippingId, $aProducts = array(), $aShipping = array()) {
        $weight = 0;
        if (!empty($aProducts)) {
            foreach ($aProducts as $aProduct) {
                $weight += $this->getShippingWeight($aProduct['productid'], $iShippingId, $aProduct['amount'], $aProduct, $aShipping);
            }
        }
        return $weight;
    }

}