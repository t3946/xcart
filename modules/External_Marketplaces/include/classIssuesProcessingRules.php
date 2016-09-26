<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";
require_once $xcart_dir . "/include/class/classSQLBuilder.php";

class classIssuesProcessingRules extends classData
{
    public function __construct($aIssuesProcessingRules = null)
    {
        $this->sPrimaryTable = 'cidev_issues_processing_rules';
        $this->aPrimaryKeys = ['issue_id'];

        parent::__construct($aIssuesProcessingRules);
    }

    public static function getIssuesList()
    {
        $aResults = null;
        $oSQL = new classSQLBuilder();
        $aIssues = $oSQL->addSelect('issue_id')->addFromTable('cidev_issues_processing_rules')->Execute()->getQueryResult();
        if (!empty($aIssues)) {
            foreach ($aIssues as $aIssue) {
                $aResults[] = new classIssuesProcessingRules(['issue_id' => $aIssue['issue_id']]);
            }
        }
        return $aResults;
    }

    public static function getIssueByGoogleIssueId($sGoogleId)
    {
        $oResult = null;
        if (!empty($sGoogleId)) {
            $oSQL = new classSQLBuilder();
            $aIssues = $oSQL->addSelect('*')->addFromTable('cidev_issues_processing_rules')->addCondition("issue_gmc_id = '$sGoogleId'")->Execute()->getQueryResult();
            if (!empty($aIssues)) {
                foreach ($aIssues as $aIssue) {
                    $oResult = new classIssuesProcessingRules();
                    $oResult->fillPrimaryTableValues($aIssue);
                }
            }
        }
        return $oResult;
    }

    public function setIssueGMCId($GMCIssue)
    {
        $this->setField('issue_gmc_id', $GMCIssue);
    }

    public function getIssueGMCId()
    {
        return $this->getField('issue_gmc_id');
    }

    public function getIssueName()
    {
        return stripslashes($this->getField('issue_name'));
    }

    public function updateIssueName($sIssueName)
    {
        $this->updateField('issue_name',addslashes($sIssueName));
        return $this;
    }

    public function getIssueProcessing()
    {
        return $this->getField('issue_processing');
    }

    public function setIssueId($sIssue)
    {
        $this->setField('issue_id', $sIssue);
    }

    public function getIssueId()
    {
        return $this->getField('issue_id');
    }

    public function getIssueDate()
    {
        $oDate = new DateTime();
        $oDate->setTimestamp(strtotime($this->getField('issue_date')));
        return $oDate;
    }

    public function getProductImpactedCount()
    {
        $aCount = $this->oSQL->addSelect('count(1)', 'cnt')->addFromTable('cidev_gmc_quality_issues', 'xc')->addInnerJoin('products', 'xp', "xp.productid = xc.productid AND xp.forsale='Y'")->
        addCondition('xc.issue_id = '.$this->getIssueId())->Execute()->getQueryResult();
        $aC = reset($aCount);
        return $aC['cnt'];
    }
}