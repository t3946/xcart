<?php
namespace Xcart;

/**
 * @deprecated deprecated class
 */
class ProductVerificationStatus extends CloneData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "product_verification_statuses";
        $this->sPrimaryKeyFiled = "statusid";

        parent::__construct($iId);
    }
}