<?php

namespace Xcart;


class Filter extends Data
{
    private $aFilterValues = null;
    /**
     * @var StoreFront
     */
    private $oStorefront = null;
    /**
     * @var Category
     */
    private $oCategory = null;
    /**
     * @var FilterValue[]
     */
    private $aFilterValuesSelected = null;

    private $aValueFound = null;

    private $fPriceMin = null;
    private $fPriceMax = null;


    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['f_id'];
        $this->sPrimaryTable = 'cidev_filters';
        parent::__construct($aParams);
    }

    public function getFilterId()
    {
        return $this->getField('f_id');
    }

    public function getFilterValues()
    {
        if (is_null($this->aFilterValues)) {
            $this->aFilterValues = FilterValue::model()->findAll(
                SQLBuilder::getInstance()->
                addCondition('f_id = ' . $this->getFilterId()));
        }
        return $this->aFilterValues;
    }

    public function setStoreFront($oStoreFront)
    {
        $this->oStorefront = $oStoreFront;
        return $this;
    }

    public function setCategory($oCategory)
    {
        $this->oCategory = $oCategory;
        return $this;
    }

    public function setFilterValuesSelected($aFilterValues)
    {
        $this->aFilterValuesSelected = $aFilterValues;
        return $this;
    }

    public function getFilterValuesSelected()
    {
        return $this->aFilterValuesSelected;
    }

    private function getFilterQueryCondition()
    {
        $aResult = [];
        if (!empty($this->aFilterValuesSelected)) {
            $aFVId = [];
            foreach ($this->aFilterValuesSelected as $oFilterValue) {
                $aFVId[] = $oFilterValue->getFilterValueId();
            }
            $sFilterSQL = null;
            if ($this->getFilterId()) {
                $sFilterSQL = <<<SQL
                INNER JOIN xcart_cidev_filter_products xc2 ON xc2.productid = p.productid
                INNER JOIN xcart_cidev_filter_values fv ON fv.fv_id = xc2.fv_id AND fv.f_id = {$this->getFilterId()}
                INNER JOIN xcart_cidev_filters f ON f.f_id = fv.f_id
SQL;
            }
            $aResult[] = $sFilterSQL . " WHERE xc1.fv_id IN (" . implode(',', $aFVId) . ")";
            $aResult[] = " having count(xc1 . productid) = " . count($this->aFilterValuesSelected);
        }
        return $aResult;
    }

    private function getPriceQueryCondition()
    {
        $sSQLprice = '';
        if (!empty($this->fPriceMin) && !empty($this->fPriceMax)) {
            $sSQLprice = " AND cidev_get_XCART_price(p.productid, 0, 'xcart', 0) between $this->fPriceMin and $this->fPriceMax ";
        }
        return $sSQLprice;
    }

    public function getFilteredProductsQuery()
    {
        list($sSQLfv, $sSQLfv2) = $this->getFilterQueryCondition();
        $sSQL = <<<SQL
SELECT p.*
FROM xcart_cidev_filter_values    xc
     INNER JOIN xcart_cidev_filter_products xc1 ON xc1.fv_id = xc.fv_id
     INNER JOIN xcart_products_categories pc ON xc1.productid = pc.productid
     INNER JOIN xcart_products p ON p.productid = xc1.productid AND forsale ='Y' {$this->getPriceQueryCondition()}
     INNER JOIN xcart_pc_options po ON po.storefrontid = {$this->oStorefront->getStoreFrontId()} AND ((po.disable_AC_products = 'N')
                                                            OR (    po.disable_AC_products = 'Y' AND p.pc_classify_status != 'AC'))
     INNER JOIN xcart_categories c ON pc.categoryid = c.categoryid AND c.categoryid_path like '{$this->oCategory->getPath()}%' AND c.storefrontid = {$this->oStorefront->getStoreFrontId()}                                                        
     {$sSQLfv}
     GROUP BY xc1.productid
     {$sSQLfv2} 

SQL;
    return $sSQL;
    }

    public function getMoreFilterValues()
    {
        if (is_null($this->aValueFound)) {

            $sSQL = <<<SQL
SELECT xc2.fv_id, fv_name, count(1) as cnt FROM ({$this->getFilteredProductsQuery()}) pq                                                         
     INNER JOIN xcart_cidev_filter_products xc2 ON xc2.productid = pq.productid
     INNER JOIN xcart_cidev_filter_values fv ON fv.fv_id = xc2.fv_id AND fv.f_id = {$this->getFilterId()}
     INNER JOIN xcart_cidev_filters f ON f.f_id = fv.f_id
     group by fv.fv_id
     order by fv_name
SQL;
            $aResults = SQLBuilder::getInstance()->setQuery($sSQL)->query()->getQueryResult();
            if (!empty($aResults)) {
                foreach ($aResults as $aResult) {
                    $iCount = $aResult['cnt'];
                    unset ($aResult['cnt']);
                    $this->aValueFound[] = FilterValue::model()->setFields($aResult)->setCount($iCount);
                }
            }
        }

        return $this->aValueFound;
    }

    public function getMoreBrands()
    {
        if (is_null($this->aValueFound)) {

            $sSQL = <<<SQL
SELECT xb.brandid, xb.brand, count(1) as cnt FROM (
     {$this->getFilteredProductsQuery()}) pq                                                          
     INNER JOIN xcart_brands xb ON pq.brandid = xb.brandid
     group by xb.brandid 
     order by xb.brand
SQL;
            $aResults = SQLBuilder::getInstance()->setQuery($sSQL)->query()->getQueryResult();
            if (!empty($aResults)) {
                foreach ($aResults as $aResult) {
                    $iCount = $aResult['cnt'];
                    unset ($aResult['cnt']);
                    $this->aValueFound[] = Brand::model()->setFields($aResult)->setCount($iCount);
                }
            }
        }
        return $this->aValueFound;
    }

    public function getFoundValuesCount()
    {
        $iCount = 0;
        if (!empty($this->aValueFound)) {
            $iCount += count($this->aValueFound);
        }
        return $iCount;
    }

    /**
     * @param $sPriceRange (0_200)
     */
    public function setPriceRange($sPriceRange)
    {
        list($this->fPriceMin, $this->fPriceMax) = explode('_', $sPriceRange);
    }
}