<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classProducts.php";
require_once $xcart_dir . "/include/class/classLogs.php";

class classExternalVerificationBatch extends classData
{
    const LINK_SEARCH_BY_ASIN = 'https://www.amazon.com/dp/%s/';
    const LINK_SEARCH_BY_UPC = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';
    const LINK_SEARCH_BY_NAME = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';
    const LINK_TO_BATCH_VERIFY = 'verification.php?batch=%s';
    const BATCH_STATUS_CHANGED_LOG_MESSAGE = 'Batch ID <b>%s</b> status has been changed: %s -> %s';

    protected $aProductsInBatchCompleted = [];
    protected $aProductsInBatchMatched = [];
    protected $aProductsInBatchNotSure = [];
    protected $aProductsInBatchNotMatched = [];

    public static $aProductStatuses = ['processed'=>['match','not_match','not_sure','not_found'], 'open'=>'open'];

    /**
     * @var classProduct
     */
    private $oVerifiedProduct = null;

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['batch_id'];
        $this->sPrimaryTable = 'external_verification_batches';
        parent::__construct($aParams);
    }

    public function getBatchId()
    {
        return $this->getField('batch_id');
    }

    public function getBatchStatus()
    {
        return $this->getField('batch_status');
    }

    public function getVerifiedProductId()
    {
        if (!empty($this->oVerifiedProduct))
            return $this->oVerifiedProduct->getProductId();
        return false;
    }

    public function getProductsInBatchCompleted()
    {
        if (empty($this->aProductsInBatchCompleted)) {
            $aProducts = $this->oSQL->init()->addSelect('productid')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getBatchId())->
            addCondition('action IN ("'.implode('","',self::$aProductStatuses['processed']).'")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new classProduct($aProduct['productid']);
                    $this->aProductsInBatchCompleted[] = $oProduct;
                }
            }
        }
        return $this->aProductsInBatchCompleted;
    }

    public function getProductsInBatchCompletedCount()
    {
        if (empty($this->aProductsInBatchCompleted)) {
            $this->getProductsInBatchCompleted();
        }
        return count($this->aProductsInBatchCompleted);
    }

    public function getProductsInBatchMatchedCount()
    {
        if (empty($this->aProductsInBatchMatched)) {
            $this->getProductsInBatchMatched();
        }
        return count($this->aProductsInBatchMatched);
    }

    public function getProductsInBatchMatched()
    {
        if (empty($this->aProductsInBatchMatched)) {
            $aProducts = $this->oSQL->init()->addSelect('productid')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getBatchId())->
            addCondition('action IN ("match")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new classProduct($aProduct['productid']);
                    $this->aProductsInBatchMatched[] = $oProduct;
                }
            }
        }
        return $this->aProductsInBatchMatched;
    }

    public function getProductsInBatchNotSureCount()
    {
        if (empty($this->aProductsInBatchNotSure)) {
            $this->getProductsInBatchNotSure();
        }
        return count($this->aProductsInBatchNotSure);
    }

    public function getProductsInBatchNotSure()
    {
        if (empty($this->aProductsInBatchNotSure)) {
            $aProducts = $this->oSQL->init()->addSelect('productid')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getBatchId())->
            addCondition('action IN ("not_sure")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new classProduct($aProduct['productid']);
                    $this->aProductsInBatchNotSure[] = $oProduct;
                }
            }
        }
        return $this->aProductsInBatchNotSure;
    }

    public function getProductsInBatchNotMatchedCount()
    {
        if (empty($this->aProductsInBatchNotMatched)) {
            $this->getProductsInBatchNotMatched();
        }
        return count($this->aProductsInBatchNotMatched);
    }

    public function getProductsInBatchNotMatched()
    {
        if (empty($this->aProductsInBatchNotMatched)) {
            $aProducts = $this->oSQL->init()->addSelect('productid')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getBatchId())->
            addCondition('action IN ("not_match")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new classProduct($aProduct['productid']);
                    $this->aProductsInBatchNotMatched[] = $oProduct;
                }
            }
        }
        return $this->aProductsInBatchNotMatched;
    }

    public function getProductsInBatchOpened()
    {
        global $login;
        $aProductsInBatchOpen = null;
        $aProducts = $this->oSQL->init()->addSelect('productid')->addFromTable('external_verification_products', 'xp')->addCondition("login='$login'")->addCondition('batch_id='.$this->getBatchId())->
        addCondition('action IN ("open")')->addCondition('NOT EXISTS
         (SELECT 1 FROM ' . self::$sql_tbl['external_verification_products'] . ' as xp2 WHERE login = "' .$login . '" AND action IN ("'.implode('","',self::$aProductStatuses['processed']).'") AND xp2.productid = xp.productid)')->Execute()->getQueryResult();
        if (!empty($aProducts)) {
            foreach ($aProducts as $aProduct) {
                $oProduct = new classProduct($aProduct['productid']);
                $aProductsInBatchOpen = $oProduct;
            }
        }
        return $aProductsInBatchOpen;
    }

    public function getNextProductToVerify()
    {
        global $login;
        $aProductsNextInBatch = null;
        $aOpenedProducts = $this->getProductsInBatchOpened();
        if (empty($aOpenedProducts)) {
            $aNextProducts = $this->oSQL->init()->addSelect('P.productid')->addSelect('VP.login')->
            addSelect("(SELECT count(1)
                              FROM " . self::$sql_tbl['external_verification_products'] . " VP2
                              INNER JOIN xcart_external_verification_products_queue Q2 ON Q2.productid = VP2.productid AND Q2.status = 'In progress' AND Q2.cross_verify_count = 1
                              WHERE VP2.login = VP.login AND VP2.batch_id = VP.batch_id)", 'batch_processed')->addFromTable('products', 'P')->
            addInnerJoin('external_verification_products_queue', 'Q', "Q.productid = P.productid AND Q.status = 'In progress' AND Q.cross_verify_count <= 1")->
            addInnerJoin('external_verification_products', 'VP', "VP.productid = P.productid AND VP.login != '$login'")->addCondition("P.forsale = 'Y'")->
            addGroupBy('P.productid')->addOrderBy('batch_processed DESC')->setLimit('1')->Execute()->getQueryResult();
            if (!empty($aNextProducts)) {
                foreach ($aNextProducts as $aNextProduct) {
                    $oProduct = new classProduct($aNextProduct['productid']);
                    $aProductsNextInBatch = $oProduct;
                    $this->updateField('last_cross_verify_login', $aNextProduct['login']);
                }
            } else {
                $aNextProducts = $this->oSQL->init()->addSelect('p.productid')->addSelect('cross_verify_count', 'batch_processed')->addFromTable('products', 'p')->
                addInnerJoin('external_verification_products_queue', 'q', "q.productid = p.productid AND q.status = 'In progress' AND q.cross_verify_count <= 1")->
                addCondition("p.forsale = 'Y'")->addCondition('NOT EXISTS (SELECT 1 FROM ' . self::$sql_tbl['external_verification_products'] . ' vp WHERE vp.productid = p.productid AND login = \'' . $login . '\' AND action IN (\'open\'))')->addGroupBy('p.productid')->addOrderBy('cross_verify_count DESC')->setLimit('1')->Execute()->getQueryResult();

                if (!empty($aNextProducts)) {
                    foreach ($aNextProducts as $aNextProduct) {
                        $oProduct = new classProduct($aNextProduct['productid']);
                        $aProductsNextInBatch = $oProduct;
                    }
                }
            }
        } else {
            $aProductsNextInBatch = $aOpenedProducts;
        }
        if (is_object($aProductsNextInBatch) && $aProductsNextInBatch->getProductId() > 0)
            $this->oVerifiedProduct = $aProductsNextInBatch;
        return $this;
    }

    public function openProductToVerify()
    {
        global $login;
        if (!empty($this->oVerifiedProduct)) {
            $aInsertArray = ['productid' => $this->oVerifiedProduct->getProductId(), 'login' => $login, 'batch_id' => $this->getBatchId(), 'action' => self::$aProductStatuses['open'], 'value' => time()];
                func_array2insert('external_verification_products', $aInsertArray, true, false);
        }

    }

    public function getOriginalLinksJson()
    {
        if (empty($this->oVerifiedProduct)) {
            $this->getNextProductToVerify()->openProductToVerify();
        }
        $aLinkArray = [];
        if (!empty($this->oVerifiedProduct)) {
            $aLinkArray[] = [$this->oVerifiedProduct->getProductFrontURL('https://') . '?keep_https=yes', $this->oVerifiedProduct->getProductName()];
        }
        return json_encode($aLinkArray, JSON_PRETTY_PRINT);
    }

    public function getSearchLinksJson()
    {
        global $xcart_https_host;
        if (empty($this->oVerifiedProduct)) {
            $this->getNextProductToVerify()->openProductToVerify();
        }
        $aLinkArray = [];

        if (!empty($this->oVerifiedProduct)) {
            $sASIN = $this->oVerifiedProduct->getAmazonASIN();
            $sUPC = $this->oVerifiedProduct->getUPC();

            if (!empty($sASIN)) $aLinkArray[0] = ['https://' . $xcart_https_host . DIR_VERIFICATOR . '/view.php?' . sprintf(self::LINK_SEARCH_BY_ASIN, $sASIN), 'Open product by ASIN: ' . $sASIN];
            if (!empty($sUPC)) $aLinkArray[1] = ['https://' . $xcart_https_host . DIR_VERIFICATOR . '/view.php?' . sprintf(self::LINK_SEARCH_BY_UPC, $sUPC), 'Search product by UPC: ' . $sUPC];
            $aLinkArray[2] = ['https://' . $xcart_https_host . DIR_VERIFICATOR . '/view.php?' . sprintf(self::LINK_SEARCH_BY_NAME, html_entity_decode($this->oVerifiedProduct->getProductName())), 'Search product by Product Name: ' . $this->oVerifiedProduct->getProductName()];

        }
        return json_encode($aLinkArray, JSON_PRETTY_PRINT);
    }

    public function getProductVerificationTime($iProductId)
    {
        $diffInSec = false;
        $aProductOpen = $this->oSQL->init()->addSelect('*')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getBatchId())->
        addCondition('action = "open"')->addCondition('productid = ' . $iProductId)->Execute()->getQueryResult();
        if (!empty($aProductOpen)) {
            $aProductClose = $this->oSQL->init()->addSelect('*')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getBatchId())->
            addCondition('action IN ("'.implode('","',self::$aProductStatuses['processed']).'")')->addCondition('productid = ' . $iProductId)->Execute()->getQueryResult();
            if (!empty($aProductClose)) {
                $diffInSec = intval($aProductClose[0]['value']) - intval($aProductOpen[0]['value']);
            }
        }
        return $diffInSec;
    }

    public function setVerificationStatus($sNewStatus)
    {
        classLogs::_log('amazon_product_verification', $this->getBatchId(), classLogs::LOG_TYPE_XCART, sprintf(self::BATCH_STATUS_CHANGED_LOG_MESSAGE,$this->getBatchLogin().'_'.$this->getBatchNumber().'_'.$this->getBatchAmount(), $this->getBatchStatus(),$sNewStatus));
        $this->updateField('batch_status', $sNewStatus);

    }

    public function updateVerificationStatus($aParams)
    {
        global $login;
        $aResult = [];

        $aInserArray = ['productid' => $aParams['product_id'], 'login' => $login, 'batch_id' => $this->getBatchId(), 'action' => $aParams['status'], 'value' => time()];
        func_array2insert('external_verification_products', $aInserArray, true, false);

        if (!empty($aParams['note'])) {
            $aInserArray = ['productid' => $aParams['product_id'], 'login' => $login, 'batch_id' => $this->getBatchId(), 'action' => 'comments_if_not', 'value' => $aParams['note']];
            func_array2insert('external_verification_products', $aInserArray, true, false);
        }

        if (!empty($aParams['asin'])) {
            $aInserArray = ['productid' => $aParams['product_id'], 'login' => $login, 'batch_id' => $this->getBatchId(), 'action' => 'asin_on_amazon', 'value' => $aParams['asin']];
            func_array2insert('external_verification_products', $aInserArray, true, false);
        }

        $sql = "UPDATE " . self::$sql_tbl['external_verification_products_queue'] . " SET cross_verify_count = cross_verify_count+1 WHERE productid = " . $aParams['product_id'];
        db_query($sql);

        $aCompleted = $this->getProductsInBatchCompleted();

        if (!empty($aCompleted)) {
            $iCount = count($aCompleted);
            $diffInSec = $this->getProductVerificationTime($aParams['product_id']);
            if ($diffInSec) {
                $this->updateField('batch_product_speed', (floatval($this->getField('batch_product_speed')) * ($iCount - 1) + $diffInSec) / ($iCount));
            }
            if ($iCount >= $this->getField('batch_amount')) {
                $this->updateField('batch_status', 'Completed');
                $aResult['batch_completed'] = true;
            }
        }
        return $aResult;
    }

    public function getBatchLogin()
    {
        return $this->getField('login');
    }

    public function checkAccess()
    {
        global $login;
        return ($this->getBatchLogin() == $login);
    }

    public function getCurrentBatches()
    {
        global $login;
        $aB = [];
        $aBatches = $this->oSQL->init()->addSelect('*')->addFromTable('external_verification_batches')->
        addCondition("login='$login'")->addCondition("batch_status = 'In progress'")->Execute()->getQueryResult();
        if (!empty($aBatches)) {
            foreach ($aBatches as $aBatch) {
                $oBatch = new classExternalVerificationBatch();
                $oBatch->fillPrimaryTableValues($aBatch);
                $aB[] = $oBatch;
            }
        }
        return $aB;
    }

    public function getPreviousBatches()
    {
        global $login;
        $aB = [];
        $aBatches = $this->oSQL->init()->addSelect('*')->addFromTable('external_verification_batches')->
        addCondition("login='$login'")->addCondition("batch_status != 'In progress'")->Execute()->getQueryResult();
        if (!empty($aBatches)) {
            foreach ($aBatches as $aBatch) {
                $oBatch = new classExternalVerificationBatch();
                $oBatch->fillPrimaryTableValues($aBatch);
                $aB[] = $oBatch;
            }
        }
        return $aB;
    }

    public function getBatchNumber()
    {
        return $this->getField('batch_number');
    }

    public function getBatchAmount()
    {
        return $this->getField('batch_amount');
    }

    public function getStartDate()
    {
        $oData = new DateTime();
        $oData->setTimestamp(strtotime($this->getField('batch_start')));
        return $oData;
    }

    public function getBatchVerifyLink()
    {
        return sprintf(self::LINK_TO_BATCH_VERIFY, $this->getBatchId());
    }

    public function getAverageVerifySpeed()
    {
        return round(floatval($this->getField('batch_product_speed')));
    }
}