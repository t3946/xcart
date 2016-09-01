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

    public static function getIssueByGoogleIssueId($sGoogleId)
    {
        $oResult = null;
        if (!empty($sGoogleId)) {
            $oSQL = new classSQLBuilder();
            $aIssues = $oSQL->addSelect('*')->addFromTable('cidev_issues_processing_rules')->addCondition("issue_gmc_id = '$sGoogleId'")->Execute()->getQueryResult();
            if (!empty($aIssues)) {
                foreach ($aIssues as $aIssue) {
                    $oResult = new classIssuesProcessingRules(['issue_id'=>$aIssue['issue_id']]);
                }
            }
        }
        return $oResult;
    }

    public function setIssueGMCId($GMCIssue)
    {
        $this->setField('issue_gmc_id', $GMCIssue);
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
}