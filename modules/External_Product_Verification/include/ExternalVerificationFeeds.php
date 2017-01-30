<?php

namespace Xcart\External_Product_Verification;

use Xcart\Data;

class ExternalVerificationFeeds extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['feed_id'];
        $this->sPrimaryTable = 'external_verification_feeds';
        parent::__construct($aParams);
    }
}