<?php

namespace Xcart\External_Product_Verification;

use Xcart\Data;
use Xcart\SQLBuilder;

class ExternalVerificationFeeds extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['feed_id'];
        $this->sPrimaryTable = 'external_verification_feeds';
        parent::__construct($aParams);
    }

    /**
     * @param string $sStatus
     * @return ExternalVerificationProductsQueue[]
     */
    public function getVerificationProductsByStatus($sStatus = '')
    {
        $aResult = [];
        if ($this->getField('feed_id')) {
            $oSQL = SQLBuilder::getInstance()->addCondition("feed_id = {$this->getField('feed_id')}");
            if (!empty($sStatus)){
                $oSQL->addCondition("amz_listing_status = '$sStatus'");
            }
            $aResult = ExternalVerificationProductsQueue::model()->findAll($oSQL);
        }
        return $aResult;
    }
}