<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classCloneData.php";

class classProductVerificationStatus extends classCloneData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "product_verification_statuses";
        $this->sPrimaryKeyFiled = "statusid";

        parent::__construct($iId);
    }
}