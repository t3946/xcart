<?php
namespace Xcart;

class Product extends Data
{
    const ADMIN_PRODUCT_MODIFY_URL = '/admin/product_modify.php?productid=%d&sf=%d';

    const PRODUCT_STATUS_NOT_VERIFY = 0;
    const PRODUCT_STATUS_PROBLEM_NOT_FIXED = 1;
    const PRODUCT_STATUS_PROBLEM_FIXED = 2;
    const PRODUCT_STATUS_VERIFY = 3;

    const RETAIL_TRUST_SKU_PREFIX = 'RT*';


    private $oManufacturer;
    private $oStoreFront;
    private $aProductVerificationHistoryLast = [];

    private $aImagesD = [];
    private $aImagesP = [];
    private $aImagesT = [];

    private $aPricing = [];

    private $iAmazonQuantity = null;
    private $fAmazonPrice = null;

    /**
     * @var ProductQuestion[]
     */
    private $aProductQuestions = null;

    private $iAmazonFbaAvail = null;

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = 'products';
        $this->aPrimaryKeys = ['productid'];

        parent::__construct($iId);
    }

    public function getManfacturerClass($iManufacurerId = null)
    {
        if (!is_null($iManufacurerId))
            return new Manufacturer($iManufacurerId);
        else {
            if (is_null($this->oManufacturer)) {
                $this->oManufacturer = new Manufacturer($this->aPrimaryTableValue['manufacturerid']);
            }
            return $this->oManufacturer;
        }
    }

    public function getManufacturerId()
    {
        return $this->getField('manufacturerid');
    }

    public function getMPN()
    {
        $sMPN = '';
        if (strpos($this->getSKU(), $this->getManfacturerClass()->getField('code')) == 0)
            $sMPN = preg_replace("/^(" . $this->getManfacturerClass()->getField('code') . "-)/i", "", $this->getSKU());
        return $sMPN;
    }

    public function getSKU()
    {
        return $this->getField('productcode');
    }


    public function getStoreFront()
    {
        if (is_null($this->oStoreFront)) {
            $this->oStoreFront = StoreFront::getStoreFrontByProductId($this->getProductId());
        }
        return $this->oStoreFront;
    }

    public function setProductManufacturer($aManufacturerInfo)
    {
        if (!empty($aManufacturerInfo) && is_array($aManufacturerInfo)) {
            $this->oManufacturer = new Manufacturer($aManufacturerInfo);
        }
        return $this;
    }

    public function getProductModifyURL()
    {
        return sprintf(self::ADMIN_PRODUCT_MODIFY_URL, $this->getProductId(), $this->getStoreFront()->getField('storefrontid'));
    }

    public function getProductFrontURL($http = 'http://')
    {
        return $http . $this->getStoreFront()->getDomain() . '/' . func_clean_url_get('P', $this->getProductId(), false);
    }

    public function getHTMLShot($iOrderID)
    {
        return HTMLShot::model()->find(SQLBuilder::getInstance()->addCondition('product_id = ' . $this->getProductId())->addCondition('order_id = ' . $iOrderID));
    }

    public function createHTMLShot($iOrderID)
    {
        $aManufacturerProductVerifySettings = $this->getManfacturerClass()->getFields(['products_always_verify', 'days_before_verify']);
        if ($aManufacturerProductVerifySettings['products_always_verify'] == 'Y') {
            $this->changeVerificationStatus(self::PRODUCT_STATUS_VERIFY, '', true, [$iOrderID]);
        } elseif (intval($aManufacturerProductVerifySettings['days_before_verify']) > 0 && $this->getProductLastVerifyDate()) {
            $currentDate = new \DateTime("now");
            $iDaysInterval = $currentDate->diff($this->getProductLastVerifyDate())->days;
            if ($iDaysInterval <= $aManufacturerProductVerifySettings['days_before_verify']) {
                $this->changeVerificationStatus(self::PRODUCT_STATUS_VERIFY, '', true, [$iOrderID]);
            }
        } else {
            $this->changeVerificationStatus(self::PRODUCT_STATUS_NOT_VERIFY, '', true, [$iOrderID]);
            HTMLShot::model()->createHTMLShot($this, $iOrderID);
        }
    }

    public function getProductURLOnDistributorWebSite()
    {
        $sWebsiteProduct = $this->getManfacturerClass()->getField('d_website_search_for_sku_url');
        if (empty($sWebsiteProduct))
            $sWebsiteProduct = $this->getManfacturerClass()->getField('url');
        return str_replace(['{{mpn}}', '{{supplier_internal_id}}'], [$this->getMPN(), $this->getField('supplier_internal_id')], $sWebsiteProduct);
    }

    public function getProductLastVerifyDate()
    {
        $iDate = (int)$this->getField('last_verify_date');
        if (!empty($iDate)) {
            $oDatetime = new \DateTime();
            $oDatetime->setTimestamp($iDate);
            return $oDatetime;
        }
        return false;
    }

    public static function getProductVerificationStatuses()
    {
        return func_query("SELECT * FROM " . self::$sql_tbl['product_verification_statuses'] . " ORDER BY orderby ASC");
    }

    public function getProductVerificationHistoryLastNote()
    {
        $sResult = '';
        $this->aProductVerificationHistoryLast = func_query_first("SELECT * FROM " . self::$sql_tbl['product_verification_history'] . " WHERE productid = " . $this->getProductId() . " ORDER BY timestamp DESC");
        if (!empty($this->aProductVerificationHistoryLast)) {
            $sResult = stripslashes($this->aProductVerificationHistoryLast['verification_note']);
        }
        return $sResult;
    }

    public function changeVerificationStatus($iStatusId, $sNote = '', $add2History = true, $aOrders)
    {
        global $login;
        $bResult['result'] = false;
        if ($this->getField('verification_statusid') != $iStatusId) {
            $aUpdateParams = ['verification_statusid' => $iStatusId];
            $oDatetime = new \DateTime();
            if ($iStatusId == self::PRODUCT_STATUS_VERIFY) {
                $aUpdateParams['last_verify_date'] = $oDatetime->getTimestamp();
            }
            $res = func_array2update($this->sPrimaryTable, $aUpdateParams, 'productid = ' . $this->getProductId());

            if ($res) {
                if ($add2History) {
                    $aInsertArray = ['productid' => $this->getProductId(),
                        'verification_note' => addslashes($sNote),
                        'timestamp' => $oDatetime->getTimestamp(),
                        'username' => $login,
                        'oldstatusid' => $this->getField('verification_statusid'),
                        'newstatusid' => $iStatusId];
                    func_array2insert('product_verification_history', $aInsertArray);
                }
                $bResult['result'] = true;

                $this->setField('last_verify_date', $aUpdateParams['last_verify_date']);

                if (!empty($aOrders) && ($iStatusId == self::PRODUCT_STATUS_PROBLEM_NOT_FIXED || $iStatusId == self::PRODUCT_STATUS_PROBLEM_FIXED)) {
                    foreach ($aOrders as $iOrderId) {
                        $oVerificationStatusNew = new ProductVerificationStatus($iStatusId);
                        $oVerificationStatusOld = new ProductVerificationStatus($this->getField('verification_statusid'));
                        $sLogMessage = "<b>" . $this->getField('productcode') . "</b> product verification status: " . $oVerificationStatusOld->getField('name') . " -> " . $oVerificationStatusNew->getField('name') . "\n";
                        if (!empty($sNote)) $sLogMessage .= 'Problem/fix description: ' . $sNote;
                        func_log_order($iOrderId, 'X', nl2br($sLogMessage));
                    }
                }
                $this->setField('verification_statusid', $aUpdateParams['verification_statusid']);

            } else $bResult['error'] = 'Status not updated';
        } else {
            $bResult['error'] = 'Status not changed. New status = Old status';
        }
        return $bResult;

    }

    public function getProductId()
    {
        return $this->getField('productid');
    }

    private function fetchImages($type)
    {
        $sImagesVar = "aImages" . $type;
        if (empty($this->$sImagesVar)) {
            $aImages = func_query("SELECT * FROM " . self::$sql_tbl['images_' . $type] . " WHERE id = " . $this->getProductId() . " ORDER BY orderby ASC");
            if (!empty($aImages))
                foreach ($aImages as $aImage) {
                    $oProductImage = new ProductImage($type);
                    $oProductImage->fill($aImage);
                    $var = &$this->$sImagesVar;
                    $var[] = $oProductImage;
                }
        }
    }

    /**
     * @param $type
     * @return ProductImage[]
     */
    public function getImages($type)
    {
        $sImagesVar = "aImages" . $type;
        $this->fetchImages($type);
        return $this->$sImagesVar;
    }

    private function fetchPricing()
    {
        if (empty($this->aPricing)) {
            $aPricing = func_query("SELECT * FROM " . self::$sql_tbl['pricing'] . " WHERE productid = " . $this->getProductId() . " ORDER BY quantity ASC");
            if (!empty($aPricing))
                foreach ($aPricing as $aPrice) {
                    $oProductPricing = new Pricing();
                    $oProductPricing->fill($aPrice);
                    $this->aPricing[] = $oProductPricing;
                }
        }
    }

    public function getPricing()
    {
        $this->fetchPricing();
        return $this->aPricing;
    }

    public function getProductTableValues()
    {
        return $this->aPrimaryTableValue;
    }

    public function isParent()
    {
        return ($this->getField('clone_parent_productid') == 0);
    }

    public function isForSale()
    {
        return ($this->getField('forsale') == 'Y' ? true : false);
    }

    public function isProductOutOfStock()
    {
        $result = true;
        if (intval($this->getField('r_avail')) <= 0)
            $result = false;
        $iEtaDate = $this->getField('eta_date_mm_dd_yyyy');
        if ($result && !empty($iEtaDate)) {
            $current_time = time();
            if ($current_time < $iEtaDate) {
                $result = false;
            }
        }
        if ($result && $this->getProductCostToUs() > $this->getPrice())
            $result = false;

        if ($result && floatval($this->getField("shipping_freight")) == 0 && strpos($this->getField("productcode"), "ART-") === false)
            $result = false;

        return $result;
    }

    public function getMapPrice()
    {
        return floatval($this->getField('new_map_price'));
    }

    public function getProductCostToUs()
    {
        return floatval($this->getField('cost_to_us'));
    }

    public function getPrice($forQuantity = 1)
    {
        $fPrice = 0;
        if (!empty($this->aPricing)) {
            foreach ($this->aPricing as $oPrice) {
                if ($forQuantity >= floatval($oPrice->getQuantity())) {
                    $fPrice = floatval($oPrice->getPrice());
                    break;
                }

            }
        }
        $fMapPrice = $this->getMapPrice();
        if ($fPrice < $fMapPrice) $fPrice = $fMapPrice;

        return $fPrice;
    }

    public function getFrontendPrice($forQuantity = 1)
    {
        $fPrice = $this->getPrice($forQuantity);

        if ($this->isSupplierFeedsEnabled() && !$this->isProductOutOfStock()) {
            $fPrice = func_decreased_price($this->getProductCostToUs(), $fPrice, $this->getMapPrice());
        }

        return $fPrice;
    }

    public function  isSupplierFeedsEnabled()
    {
        $result = false;
        $sEnabled = func_query_first_cell("SELECT enabled FROM " . self::$sql_tbl['supplier_feeds'] . " WHERE manufacturerid=" . $this->getField('manufacturerid') . " AND feed_type = 'I' AND enabled='Y' AND (multiple_feed_destinations!='Y' OR (multiple_feed_destinations='Y' AND feed_file_name='" . $this->getField("controlled_by_feed") . "'))");
        if ($sEnabled == 'Y') $result = true;
        return $result;
    }

    public function getPreviewImageURL()
    {
        $sUrl = null;
        $this->getImages('P');
        if (!empty($this->aImagesP)) {
            $oImage = reset($this->aImagesP);
            $sUrl = $oImage->getURL();
        }
        return $sUrl;
    }

    public function getDetailedImages()
    {
        return $this->getImages('D');
    }

    public function getAmazonASIN()
    {
        $sASIN = func_query_first_cell("SELECT asin FROM " . self::$sql_tbl['products_amz_fields'] . " WHERE productid=" . $this->getProductId());
        return $sASIN;
    }

    public function getAmazonFBAAvail()
    {
        if (is_null($this->iAmazonFbaAvail)) {
            $this->iAmazonFbaAvail = intval(func_query_first_cell("SELECT cidev_get_amazon_FBA_cloned_stock(" . $this->getProductId() . ") as amazon_fba_avail FROM dual"));
        }
        return $this->iAmazonFbaAvail;
    }

    public function getAmazonFBAAvailReal()
    {
        return intval($this->getField('amazon_fba_avail'));
    }

    public function getAmazonFBAAvailExcludedProcessing()
    {
        $aResult = SQLBuilder::getInstance()->addSelect('COALESCE(SUM(OD.amount- OD.back),0)', 'AvailOnFBA')->
        addFromTable('order_groups', 'OG')->
        addInnerJoin('orders', 'O', 'O.orderid = OG.orderid', 'LEFT JOIN')->
        addInnerJoin('order_details', 'OD', 'OD.orderid = O.orderid', 'LEFT JOIN')->
        addInnerJoin('products', 'P', 'P.productid = ' . $this->getProductId() . ' AND OD.productid = P.productid')->
        addCondition("OG.cb_status IN ('IO','P','H','3','Q','N','O','AP')")->
        addCondition("OG.dc_status IN ('B','M','T','K','DP','E','G')")->
        addCondition('FROM_UNIXTIME(O.date) > DATE_ADD(NOW(),INTERVAL -4 WEEK)')->
        query_first()->getQueryResult();
        return intval($this->getAmazonFBAAvail() * 0.8) - intval($aResult['AvailOnFBA']);
    }

    public function isProductFBAAvail()
    {
        return ($this->getAmazonFBAAvail() > 0);
    }

    public function getUPC()
    {
        return $this->getField('upc');
    }

    public function getProductName()
    {
        return $this->getField('product');
    }

    public function isRetailTrustEnabled()
    {
        return ($this->getField('retail_trust_enabled') == 'Y') ? true : false;
    }

    /**
     * @param $sSKU
     * @return Product
     */
    public static function getProductBySKU($sSKU)
    {
        return Product::model()->find(SQLBuilder::getInstance()->addCondition("productcode = '$sSKU'"));
    }

    /**
     * @return Product[]
     */
    public function getChildProducts()
    {
        $aResult = [];
        if ($this->getProductId())
            $aResult = Product::model()->findAll(SQLBuilder::getInstance()->addCondition('clone_parent_productid = ' . $this->getProductId()));
        return $aResult;
    }

    /**
     * @return Product|null
     */
    public function getParentProduct()
    {
        $oParentProduct = null;
        if ($this->getField('clone_parent_productid')) {
            $oParentProduct = Product::model(['productid' => $this->getField('clone_parent_productid')]);
        }
        return $oParentProduct;
    }

    public function getProductsAvailOnAmazonParentWithChild($iQty)
    {
        $aProductAmazonArray = [];
        $iShipNeed = $iQty;
        if ($iQty > 0 && $this->getAmazonFBAAvail() >= $iQty) {
            if ($this->getAmazonFBAAvailReal() > 0) {
                if ($this->getAmazonFBAAvailReal() >= $iQty) {
                    $aProductAmazonArray[] = ['oProduct' => $this, 'qty' => $iQty];
                    $iShipNeed -= $iQty;
                } else {
                    $aProductAmazonArray[] = ['oProduct' => $this, 'qty' => $this->getAmazonFBAAvailReal()];
                    $iShipNeed -= $this->getAmazonFBAAvailReal();
                }
            }
            if ($iShipNeed > 0) {
                if ($this->isParent()) { //parent
                    $aChildProducts = $this->getChildProducts();
                    if (!empty($aChildProducts)) {
                        foreach ($aChildProducts as $oChildProduct) {
                            if ($oChildProduct->getAmazonFBAAvailReal() > 0) {
                                if ($oChildProduct->getAmazonFBAAvailReal() >= $iShipNeed) {
                                    $aProductAmazonArray[] = ['oProduct' => $oChildProduct, 'qty' => $iShipNeed];
                                    $iShipNeed -= $iShipNeed;
                                } else {
                                    $aProductAmazonArray[] = ['oProduct' => $oChildProduct, 'qty' => $oChildProduct->getAmazonFBAAvailReal()];
                                    $iShipNeed -= $oChildProduct->getAmazonFBAAvailReal();
                                }
                            }
                            if ($iShipNeed <= 0) break;
                        }
                    }
                } else { //child
                    $oParentProduct = $this->getParentProduct();
                    if ($oParentProduct->getAmazonFBAAvailReal() > 0) {
                        if ($oParentProduct->getAmazonFBAAvailReal() >= $iShipNeed) {
                            $aProductAmazonArray[] = ['oProduct' => $oParentProduct, 'qty' => $iShipNeed];
                            $iShipNeed -= $iShipNeed;
                        } else {
                            $aProductAmazonArray[] = ['oProduct' => $oParentProduct, 'qty' => $oParentProduct->getAmazonFBAAvailReal()];
                            $iShipNeed -= $oParentProduct->getAmazonFBAAvailReal();
                        }
                    }
                    if ($iShipNeed > 0) {
                        $aChildProducts = $oParentProduct->getChildProducts();
                        if (!empty($aChildProducts)) {
                            foreach ($aChildProducts as $oChildProduct) {
                                if ($oChildProduct->getProductId() != $this->getProductId() && $oChildProduct->getAmazonFBAAvailReal() > 0) {
                                    if ($oChildProduct->getAmazonFBAAvailReal() >= $iShipNeed) {
                                        $aProductAmazonArray[] = ['oProduct' => $oChildProduct, 'qty' => $iShipNeed];
                                        $iShipNeed -= $iShipNeed;
                                    } else {
                                        $aProductAmazonArray[] = ['oProduct' => $oChildProduct, 'qty' => $oChildProduct->getAmazonFBAAvailReal()];
                                        $iShipNeed -= $oChildProduct->getAmazonFBAAvailReal();
                                    }
                                }
                                if ($iShipNeed <= 0) break;
                            }
                        }
                    }
                }
            }
        }
        return $aProductAmazonArray;
    }

    private static function UPC_calculate_check_digit($upc_code)
    {
        $sum = 0;
        $mult = 3;
        for ($i = (strlen($upc_code) - 2); $i >= 0; $i--) {
            $sum += $mult * $upc_code[$i];
            if ($mult == 3) {
                $mult = 1;
            } else {
                $mult = 3;
            }
        }
        if ($sum % 10 == 0) {
            $sum = ($sum % 10);
        } else {
            $sum = 10 - ($sum % 10);
        }
        return $sum;
    }

    private static function isISBN($sCode)
    {
        $bResult = false;
        if (in_array(strlen($sCode), [10, 13])) {
            if (in_array(substr($sCode, 0, 3), [978, 979])) {
                $bResult = true;
            }
        }
        return $bResult;
    }

    public static function calculateUPC($upc_code)
    {
        $upc_code = preg_replace("/[^0-9]/", "", $upc_code);
        switch (strlen($upc_code)) {
            case 8:
            case 14:
                $cd = self::UPC_calculate_check_digit($upc_code);
                if ($cd != $upc_code[strlen($upc_code) - 1]) {
                    return substr($upc_code, 0, -1) . $cd;
                } else {
                    return $upc_code;
                }
                break;
            case 11:
            case 12:
            case 13:
                $cd = self::UPC_calculate_check_digit($upc_code);
                if ($cd != $upc_code[strlen($upc_code) - 1]) {
                    if (!self::isISBN($upc_code) || (self::isISBN($upc_code) && strlen($upc_code) == 12)) {
                        $cd = self::UPC_calculate_check_digit($upc_code . "1");
                        return $upc_code . $cd;
                    } else {
                        return "";
                    }
                } else {
                    return $upc_code;
                }
                break;
        }
        return "";
    }

    /**
     * @return ProductQuestion[]
     */
    public function getProductQuestions()
    {
        if (is_null($this->aProductQuestions))
            $this->aProductQuestions = ProductQuestion::model()->findAll(SQLBuilder::getInstance()->addCondition('productid=' . $this->getProductId()));
        return $this->aProductQuestions;
    }

    public function getSKURetailTrust()
    {
        return self::RETAIL_TRUST_SKU_PREFIX.$this->getSKU();
    }

    public function getAmazonQuantity()
    {
        if (is_null($this->iAmazonQuantity)) {
            $aResult = SQLBuilder::getInstance()->
            addSelect('cidev_get_amazon_quantity(' . $this->getProductId() . ')', 'aquantity')->
            addFromTable('products')->
            addCondition('productid='.$this->getProductId())->
            query_first()->getQueryResult();
            $this->iAmazonQuantity = $aResult['aquantity'];
        }
        return $this->iAmazonQuantity;
    }

    public function getAmazonPrice()
    {
        if (is_null($this->fAmazonPrice)) {
            $aResult = SQLBuilder::getInstance()->
            addSelect('cidev_get_amazon_price(' . $this->getProductId() . ')', 'aprice')->
            addFromTable('products')->
            addCondition('productid='.$this->getProductId())->
            query_first()->getQueryResult();
            $this->fAmazonPrice = $aResult['aprice'];
        }
        return $this->fAmazonPrice;
    }
}