<?php
namespace Xcart\External_Product_Verification;
use Xcart\Data;
use Xcart\Product;
use Xcart\SQLBuilder;
use Xcart\Logs;
use Xcart\Customer;

class ExternalVerificationBatch extends Data
{
    const LINK_SEARCH_BY_ASIN = 'https://www.amazon.com/dp/%s/';
    const LINK_SEARCH_BY_UPC = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';
    const LINK_SEARCH_BY_NAME = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';
    const LINK_TO_BATCH_VERIFY = 'verification.php?batch=%s&split_screen=1';
    const LINK_TO_BATCH_VIEW = 'operators_batch_view.php?batch=%s';
    const BATCH_STATUS_CHANGED_LOG_MESSAGE = 'Batch ID <b>%s</b> status has been changed: %s -> %s';

    protected $aProductsInBatchCompleted = [];
    protected $aProductsInBatchMatched = [];
    protected $aProductsInBatchNotSure = [];
    protected $aProductsInBatchNotMatched = [];

    private $iProductAnswerRight = 0;
    private $iProductAnswerWrong = 0;

    public static $aProductStatuses = ['processed' => ['match', 'not_match', 'not_sure', 'not_found'], 'open' => 'open'];

    /**
     * @var Product
     */
    private $oVerifiedProduct = null;

    private $oCustomer = null;

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

    /**
     * @return ExternalVerificationProducts[]
     */
    public function getProductsInBatchCompleted()
    {
        if (empty($this->aProductsInBatchCompleted)) {
            $oSQL = SQLBuilder::getInstance();
            $aProducts = $oSQL->addSelect('*')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getBatchId())->
            addCondition('action IN ("' . implode('","', self::$aProductStatuses['processed']) . '")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $this->aProductsInBatchCompleted[] = ExternalVerificationProducts::model()->fill($aProduct);
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
            $oSQL = SQLBuilder::getInstance();
            $aProducts = $oSQL->addSelect('productid')->addFromTable('external_verification_products')->addCondition('batch_id=' . $this->getBatchId())->
            addCondition('action IN ("match")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new Product(['productid' => $aProduct['productid']]);
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
            $oSQL = SQLBuilder::getInstance();
            $aProducts = $oSQL->addSelect('productid')->
                                addFromTable('external_verification_products')->
                                addCondition('batch_id=' . $this->getBatchId())->
                                addCondition('action IN ("not_sure")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new Product(['productid' => $aProduct['productid']]);
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
            $oSQL = SQLBuilder::getInstance();
            $aProducts = $oSQL->addSelect('productid')->
                                addFromTable('external_verification_products')->
                                addCondition('batch_id=' . $this->getBatchId())->
                                addCondition('action IN ("not_match")')->Execute()->getQueryResult();
            if (!empty($aProducts)) {
                foreach ($aProducts as $aProduct) {
                    $oProduct = new Product(['productid' => $aProduct['productid']]);
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
        $oSQL = SQLBuilder::getInstance();
        $aProducts = $oSQL->addSelect('productid')->
                            addFromTable('external_verification_products', 'xp')->
                            addCondition("login='$login'")->
                            addCondition('batch_id=' . $this->getBatchId())->
                            addCondition('action IN ("open")')->
                            addCondition('NOT EXISTS
                             (SELECT 1 FROM ' . self::$sql_tbl['external_verification_products'] . ' as xp2 WHERE login = "' . $login . '" AND action IN ("' . implode('","', self::$aProductStatuses['processed']) . '") AND xp2.productid = xp.productid)')->Execute()->getQueryResult();
        if (!empty($aProducts)) {
            foreach ($aProducts as $aProduct) {
                $oProduct = new Product(['productid' => $aProduct['productid']]);
                $aProductsInBatchOpen = $oProduct;
            }
        }
        return $aProductsInBatchOpen;
    }

    public function getNextProduct($isTest = false, $checkOpen = true)
    {
        global $login;
        $oProduct = null;
        $sStatuses = " AND q.status = 'In progress' AND q.cross_verify_count <= 1 ";
        $slogin = "";
        if ($isTest) {
            $sStatuses = " AND q.status IN ('etalon_match','etalon_not_match', 'etalon_not_found') ";
            $slogin = " AND login = '$login' ";
        }
        $oSQL = SQLBuilder::getInstance();
        $oSQL -> addSelect('p.productid')->
                 addSelect('cross_verify_count', 'batch_processed')->
                 addFromTable('products', 'p')->
                 addInnerJoin('external_verification_products_queue', 'q', "q.productid = p.productid $sStatuses")->addCondition("p.forsale = 'Y'");
        if ($checkOpen)
            $oSQL->addCondition("NOT EXISTS (SELECT 1 FROM " . self::$sql_tbl['external_verification_products'] . " vp WHERE vp.productid = p.productid" . $slogin . " AND action IN ('open'))");
        else
            $oSQL->addCondition("NOT EXISTS (SELECT 1 FROM " . self::$sql_tbl['external_verification_products'] . " vp WHERE vp.productid = p.productid" . $slogin . " AND action IN ('" . implode("','", self::$aProductStatuses['processed']) . "'))");
        $aNextProducts = $oSQL->addGroupBy('p.productid')->addOrderBy('position ASC, cross_verify_count DESC')->setLimit('1')->Execute()->getQueryResult();
        if (!empty($aNextProducts)) {
            foreach ($aNextProducts as $aNextProduct) {
                $oProduct = new Product(['productid' => $aNextProduct['productid']]);
            }
        }
        return $oProduct;
    }

    public function getNextProductToVerify()
    {
        global $login;
        $aProductsNextInBatch = null;

        $aOpenedProducts = $this->getProductsInBatchOpened();
        if (empty($aOpenedProducts)) {
            if (!$this->isTest()) {
                $oSQL = SQLBuilder::getInstance();
                $aNextProducts = $oSQL->addSelect('Q.productid')->
                                        addSelect('VP.login')->
                                        addSelect("count(login)", 'p_count')->addFromTable('external_verification_products_queue', 'Q')->
                                        addInnerJoin('products', 'P', 'P.productid = Q.productid')->
                                        addInnerJoin('external_verification_products', 'VP', "VP.action = 'open' AND VP.productid = P.productid AND VP.login != '$login'")->addCondition("P.forsale = 'Y'")->
                                        addCondition("Q.status = 'In progress'")->addCondition("Q.cross_verify_count <= 1")->
                                        addCondition("NOT EXISTS (SELECT 1 FROM xcart_external_verification_products VP3 WHERE VP3.productid = P.productid AND action ='open' AND login = '$login')")->
                                        addGroupBy('Q.productid')->
                                        addHaving('p_count <= 1')->
                                        setLimit('1')->Execute()->getQueryResult();
                if (!empty($aNextProducts)) {
                    foreach ($aNextProducts as $aNextProduct) {
                        $oProduct = new Product(['productid' => $aNextProduct['productid']]);
                        $aProductsNextInBatch = $oProduct;
                        $this->updateField('last_cross_verify_login', $aNextProduct['login']);
                    }
                } else {
                    $aProductsNextInBatch = $this->getNextProduct($this->isTest());
                }
            } else { //Test batch
                $aProductsNextInBatch = $this->getNextProduct($this->isTest());
                if (empty($aProductsNextInBatch))
                    $aProductsNextInBatch = $this->getNextProduct();
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
            $aLinkArray[2] = ['https://' . $xcart_https_host . DIR_VERIFICATOR . '/view.php?' . sprintf(self::LINK_SEARCH_BY_NAME, urlencode(html_entity_decode($this->oVerifiedProduct->getProductName()))), 'Search product by Product Name: ' . $this->oVerifiedProduct->getProductName()];

        }
        return json_encode($aLinkArray, JSON_PRETTY_PRINT);
    }

    public function getProductVerificationTime($iProductId)
    {
        $diffInSec = false;
        $oSQL = SQLBuilder::getInstance();
        $aProductOpen = $oSQL->addSelect('*')->
                               addFromTable('external_verification_products')->
                               addCondition('batch_id=' . $this->getBatchId())->
                               addCondition('action = "open"')->addCondition('productid = ' . $iProductId)->Execute()->getQueryResult();
        if (!empty($aProductOpen)) {
            $oSQL = SQLBuilder::getInstance();
            $aProductClose = $oSQL->addSelect('*')->
                                    addFromTable('external_verification_products')->
                                    addCondition('batch_id=' . $this->getBatchId())->
                                    addCondition('action IN ("' . implode('","', self::$aProductStatuses['processed']) . '")')->
                                    addCondition('productid = ' . $iProductId)->Execute()->getQueryResult();
            if (!empty($aProductClose)) {
                $diffInSec = intval($aProductClose[0]['value']) - intval($aProductOpen[0]['value']);
            }
        }
        return $diffInSec;
    }

    public function setVerificationStatus($sNewStatus)
    {
        Logs::_log('amazon_product_verification', $this->getBatchId(), Logs::LOG_TYPE_XCART, sprintf(self::BATCH_STATUS_CHANGED_LOG_MESSAGE, $this->getBatchLogin() . '_' . $this->getBatchNumber() . '_' . $this->getBatchAmount(), $this->getBatchStatus(), $sNewStatus));
        $this->updateField('batch_status', $sNewStatus);

    }

    public function updateVerificationStatus($aParams)
    {
        global $login;
        $aResult = [];
        /** @var ExternalVerificationProducts $oExternaVerificationProduct */
        $oExternaVerificationProduct = ExternalVerificationProducts::model();
        $sNewConlusionStatus = $aParams['status'];
        if ($aParams['status'] == 'submit') {
            if (!empty($aParams['aConclusion'])) {
                $sNewConlusionStatus = 'not_match';
                if ($aParams['aConclusion']['product_image'] == 'same' &&
                    $aParams['aConclusion']['product_names'] == 'not_contradict' &&
                    $aParams['aConclusion']['product_description'] == 'not_contradict' &&
                    intval($aParams['aConclusion']['qty_on_amazon']) <= intval($aParams['aConclusion']['qty_on_our_website'])
                ) {
                    $sNewConlusionStatus = 'match';
                }
                foreach ($aParams['aConclusion'] as $keyConclusion => $valueConclusion) {
                    $oExternaVerificationProduct->fill(['productid' => $aParams['product_id'], 'login' => $login, 'batch_id' => $this->getBatchId(), 'action' => $keyConclusion, 'value' => $valueConclusion])->_insert(true);
                }
            }
        }

        $oExternaVerificationProduct->fill(['productid' => $aParams['product_id'], 'login' => $login, 'batch_id' => $this->getBatchId(), 'action' => $sNewConlusionStatus, 'value' => time()])->_insert(true);

        if (!empty($aParams['note'])) {
            $oExternaVerificationProduct->fill(['productid' => $aParams['product_id'], 'login' => $login, 'batch_id' => $this->getBatchId(), 'action' => 'comments_if_not', 'value' => $aParams['note']])->_insert(true);
        }

        if (!empty($aParams['asin'])) {
            $oExternaVerificationProduct->fill(['productid' => $aParams['product_id'], 'login' => $login, 'batch_id' => $this->getBatchId(), 'action' => 'asin_on_amazon', 'value' => $aParams['asin']])->_insert(true);
        }

        /** @var ExternalVerificationProductsQueue $oProductQueue */
        $oProductQueue = ExternalVerificationProductsQueue::model(['productid' => $aParams['product_id']]);
        $oProductQueue->updateField('cross_verify_count', $oProductQueue->getCrossVerifyCount() + 1);

        $iCount = $this->getProductsInBatchCompletedCount();
        if ($iCount) {
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

    /**
     * @return ExternalVerificationBatch[]
     */
    public function getCurrentBatches()
    {
        global $login;
        $aB = [];
        $oSQL = SQLBuilder::getInstance();
        $aBatches = $oSQL->addSelect('*')->
                           addFromTable('external_verification_batches')->
                           addCondition("login='$login'")->
                           addCondition("batch_status = 'In progress'")->Execute()->getQueryResult();
        if (!empty($aBatches)) {
            foreach ($aBatches as $aBatch) {
                $oBatch = new ExternalVerificationBatch();
                $oBatch->fill($aBatch);
                $aB[] = $oBatch;
            }
        }
        return $aB;
    }

    /**
     * @return ExternalVerificationBatch[]
     */
    public function getPreviousBatches()
    {
        global $login;
        $aB = [];
        $oSQL = SQLBuilder::getInstance();
        $aBatches = $oSQL->addSelect('*')->
                           addFromTable('external_verification_batches')->
                           addCondition("login='$login'")->
                           addCondition("batch_status != 'In progress'")->Execute()->getQueryResult();
        if (!empty($aBatches)) {
            foreach ($aBatches as $aBatch) {
                $oBatch = new ExternalVerificationBatch();
                $oBatch->fill($aBatch);
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
        $oData = new \DateTime();
        $oData->setTimestamp(strtotime($this->getField('batch_start')));
        return $oData;
    }

    public function getBatchVerifyLink()
    {
        return sprintf(self::LINK_TO_BATCH_VERIFY, $this->getBatchId());
    }

    public function getBatchViewLink()
    {
        return sprintf(self::LINK_TO_BATCH_VIEW, $this->getBatchId());
    }

    public function getAverageVerifySpeed()
    {
        return round(floatval($this->getField('batch_product_speed')));
    }

    public function isTest()
    {
        return ($this->getField('is_test') == 'Y');
    }

    public static function checkAnswerCorrect(ExternalVerificationProducts $oCompletedProduct, ExternalVerificationProductsQueue $oProductQueue)
    {
        $iResult = 0;
        switch ($oProductQueue->getStatus()) {
            case 'etalon_match':
                switch ($oCompletedProduct->getAction()) {
                    case 'match' :
                        if (in_array($oCompletedProduct->getAsin(), $oProductQueue->getAsin())) {
                            $iResult = 1;
                        } else $iResult = -1;
                        break;
                    case 'not_match' :
                        $iResult = -1;
                        break;
                    case 'not_sure' :
                        $iResult = -1;
                        break;
                    case 'not_found' :
                        $iResult = -1;
                        break;
                }
                break;
            case 'etalon_not_match':
                switch ($oCompletedProduct->getAction()) {
                    case 'match' :
                        $iResult = -1;
                        break;
                    case 'not_match' :
                        $iResult = 1;
                        break;
                    case 'not_sure' :
                        $iResult = -1;
                        break;
                    case 'not_found' :
                        $iResult = 1;
                        break;
                }
                break;
            case 'etalon_not_found':
                switch ($oCompletedProduct->getAction()) {
                    case 'match' :
                        $iResult = -1;
                        break;
                    case 'not_match' :
                        $iResult = 1;
                        break;
                    case 'not_sure' :
                        $iResult = -1;
                        break;
                    case 'not_found' :
                        $iResult = 1;
                        break;
                }
                break;
        }
        return $iResult;
    }

    public function countTestResults()
    {
        $this->iProductAnswerRight = $this->iProductAnswerWrong = 0;
        $aCompletedProducts = $this->getProductsInBatchCompleted();
        if (!empty($aCompletedProducts)) {
            foreach ($aCompletedProducts as $oCompletedProduct) {
                $oProductQueue = new ExternalVerificationProductsQueue(['productid' => $oCompletedProduct->getProductId()]);
                $iAnswerResult = self::checkAnswerCorrect($oCompletedProduct, $oProductQueue);
                if ($iAnswerResult > 0) {
                    $this->iProductAnswerRight++;
                } elseif ($iAnswerResult < 0) {
                    $this->iProductAnswerWrong++;
                }
            }
        }
        return $this;
    }

    public function getRightAnswersCount()
    {
        return $this->iProductAnswerRight;
    }

    public function getWrongAnswersCount()
    {
        return $this->iProductAnswerWrong;
    }

    public function isTestFailed()
    {
        return ($this->getField('test_failed') == 'Y');
    }

    public function getCustomer()
    {
        if (is_null($this->oCustomer)) {
            $this->oCustomer = new Customer(['login' => $this->getBatchLogin()]);
        }
        return $this->oCustomer;
    }

    public function isAccountSuspended()
    {
        return $this->getCustomer()->isAmazonAccountSuspended();
    }

    public function checkBatchTestProductsComplete()
    {
        if ($this->isTest()) {
            $aProductsNextInBatch = $this->getNextProduct($this->isTest(), false);
            if (empty($aProductsNextInBatch)) {
                $this->countTestResults();
                global $config;
                if ($this->getWrongAnswersCount() > intval($config['Amazon_Verification']['amazon_verification_maximum_mistakes'])) {
                    $this->updateField('test_failed', 'Y');
                }
            }
        }
    }
}