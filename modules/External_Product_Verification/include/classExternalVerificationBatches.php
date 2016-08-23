<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classProducts.php";

class classExternalVerificationBatch extends classData
{
    const LINK_SEARCH_BY_ASIN = 'https://www.amazon.com/dp/%s/';
    const LINK_SEARCH_BY_UPC = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';
    const LINK_SEARCH_BY_NAME = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';

    protected $aProductsInBatchComplited = [];

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['batch_id'];
        $this->sPrimaryTable = 'external_verification_batches';
        parent::__construct($aParams);
    }

    public function getProductsInBatchComplited()
    {
        if (empty($this->aProductsInBatchComplited)) {
            $aProducts = $this->oSQL->init()->addSelect('productid')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getField('batch_id'))->
            addCondition('action IN ("match","not_match","not_sure")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new classProduct($aProduct['productid']);
                    $this->aProductsInBatchComplited[] = $oProduct;
                }
            }
        }
        return $this->aProductsInBatchComplited;
    }

    public function getProductsInBatchOpened()
    {
        $aProductsInBatchOpen = null;
        $aProducts = $this->oSQL->init()->addSelect('productid')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getField('batch_id'))->
        addCondition('action IN ("open")')->Execute()->getQueryResult();
        if (!empty($aProducts)) {
            foreach ($aProducts as $aProduct) {
                $oProduct = new classProduct($aProduct['productid']);
                $aProductsInBatchOpen = $oProduct;
            }
        }
        return $aProductsInBatchOpen;
    }

    public function getNextProductInBatch()
    {
        global $login;
        $aProductsNextInBatch = null;
        $aOpenedProducts = $this->getProductsInBatchOpened();
        if (empty($aOpenedProducts)) {
            $aNextProducts = $this->oSQL->init()->addSelect('p.productid')->
                addSelect("(SELECT count(1)
                              FROM ".self::$sql_tbl['external_verification_products']." VP2
                              INNER JOIN xcart_external_verification_products_queue Q2 ON Q2.productid = VP2.productid AND Q2.status = 'In progress' AND Q2.cross_verify_count = 1
                              WHERE VP2.login = VP.login AND VP2.batch_id = VP.batch_id)", 'batch_processed')->addFromTable('products', 'p')->
                addInnerJoin('external_verification_products_queue', 'Q', "ON Q.productid = P.productid AND Q.status = 'In progress' AND Q.cross_verify_count <= 1")->
                addInnerJoin('external_verification_products', 'VP', "VP.productid = P.productid AND VP.login != '$login'")->addCondition("P.forsale = 'Y'")->
                addGroupBy('P.productid')->addOrderBy('batch_processed DESC')->setLimit('1');
            if (!empty($aNextProducts)) {
                foreach ($aNextProducts as $aNextProduct){
                    $oProduct = new classProduct($aNextProduct['productid']);
                    $aProductsNextInBatch = $oProduct;
                }
            }
        } else {
            $aProductsNextInBatch = $aOpenedProducts;
        }
        return $aProductsNextInBatch;
    }

    public function getSearchLinksJson()
    {
        global $xcart_https_host;
        $oProduct = new classProduct(553685);
        $aLinkArray = [];
        $aLinkArray[] = ['https://' . $xcart_https_host . DIR_VERIFICATOR . '/view.php?' . sprintf(self::LINK_SEARCH_BY_ASIN, 'B001A41DHY'), 'Open product by ASIN: ' . 'B001A41DHY'];
        $aLinkArray[] = ['https://' . $xcart_https_host . DIR_VERIFICATOR . '/view.php?' . sprintf(self::LINK_SEARCH_BY_UPC, '795721107878'), 'Search product by UPC: ' . '795721107878'];
        $aLinkArray[] = ['https://' . $xcart_https_host . DIR_VERIFICATOR . '/view.php?' . sprintf(self::LINK_SEARCH_BY_NAME, 'Hubbard Scientific Raised Relief Map 950 Colorado State Map'), 'Search product by Product Name: ' . 'Hubbard Scientific Raised Relief Map 950 Colorado State Map'];
        $aLinkArray[] = [$oProduct->getProductFrontURL() . '?keep_https=yes', '1'];
        return json_encode($aLinkArray, JSON_PRETTY_PRINT);
    }
}