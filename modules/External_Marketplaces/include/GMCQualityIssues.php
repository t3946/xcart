<?php
namespace Xcart\External_MarketPlace;
use Xcart\Data;
use Xcart\Product;

class GMCQualityIssues extends Data
{
    private $oProduct = null;
    private $oIssue = null;

    public function __construct($aGMCQualityIssues = null)
    {
        $this->sPrimaryTable = 'cidev_gmc_quality_issues';
        $this->aPrimaryKeys = ['productid', 'issue_id'];

        parent::__construct($aGMCQualityIssues);
    }

    public function getIssueEntity()
    {
        if (is_null($this->oIssue)) {
            $this->oIssue = new IssuesProcessingRules(['issue_id'=>$this->getIssueId()]);
        }
        return $this->oIssue;
    }

    public function getProductId()
    {
        return $this->getField('productid');
    }

    public function getIssueId()
    {
        return $this->getField('issue_id');
    }

    public function getProductEntity()
    {
        if (is_null($this->oProduct)) {
            $this->oProduct = new Product(['productid'=>$this->getProductId()]);
        }
        return $this->oProduct;
    }

    public function getIssueDataHuman()
    {
        $sResult = null;
        $oIssue = json_decode($this->getField('issue_data'));
        if ($oIssue) {
            $class_vars = get_object_vars($oIssue);

            foreach ($class_vars as $name => $value) {
                if ($value) {
                    $sResult .= "<b>$name</b>: $value<br/>";
                }
            }
        }
        return $sResult;
    }

    public function getIssueDestination()
    {
        return json_decode($this->getField('issue_destination'));
    }

    public function getIssueDate(){
        return new \DateTime($this->getField('issue_date'));
    }
}