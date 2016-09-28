<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classGMCQualityIssues extends classData
{
    public function __construct($aGMCQualityIssues = null)
    {
        $this->sPrimaryTable = 'cidev_gmc_quality_issues';
        $this->aPrimaryKeys = ['productid', 'issue_id'];

        parent::__construct($aGMCQualityIssues);
    }

    public function getProductId()
    {
        return $this->getField('productid');
    }
}