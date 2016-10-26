<?php
namespace External_MarketPlace;
use Xcart\Data;

class IssuesProcessingRules extends Data
{
    private $aProductsIssues = null;
    private $iProductCount = null;
    private $iStoreFrontId = null;

    public function __construct($aIssuesProcessingRules = null)
    {
        $this->sPrimaryTable = 'cidev_issues_processing_rules';
        $this->aPrimaryKeys = ['issue_id'];

        parent::__construct($aIssuesProcessingRules);
    }

    public static function getIssuesList($iStorefrontId = null)
    {
        $aResults = null;
        $oSQL = new SQLBuilder();
        $oSQL->addSelect('*')->addFromTable('cidev_issues_processing_rules');
        $aIssues = $oSQL->Execute()->getQueryResult();
        if (!empty($aIssues)) {
            foreach ($aIssues as $aIssue) {
                $oIssue = new External_MarketPlace\IssuesProcessingRules();
                $oIssue->fill($aIssue);
                if (!is_null($iStorefrontId))
                    $oIssue->setStoreFront($iStorefrontId);
                if ($oIssue->getProductImpactedCount() > 0)
                    $aResults[] = $oIssue;
            }
        }
        return $aResults;
    }

    public static function getIssueByGoogleIssueId($sGoogleId)
    {
        $oResult = null;
        if (!empty($sGoogleId)) {
            $oSQL = new Xcart\SQLBuilder();
            $aIssues = $oSQL->addSelect('*')->addFromTable('cidev_issues_processing_rules')->addCondition("issue_gmc_id = '$sGoogleId'")->Execute()->getQueryResult();
            if (!empty($aIssues)) {
                foreach ($aIssues as $aIssue) {
                    $oResult = new Xcart\IssuesProcessingRules();
                    $oResult->fill($aIssue);
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
        $sIssueName = $this->getField('issue_name');
        if (empty($sIssueName)) {
            return $this->getIssueGMCId();
        }
        return stripslashes($sIssueName);
    }

    public function updateIssueName($sIssueName)
    {
        $this->updateField('issue_name', addslashes($sIssueName));
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

    public function getProductImpactedCount($aParams = ['fixed' => 'N'])
    {
        if (is_null($this->iProductCount)) {
            $iIssueId = $this->getIssueId();
            if ($iIssueId) $aParams['issue_id'] = $this->getIssueId();

            $oSQL = classSQLBuilder::getInstance();
            $oSQL->addSelect('count(1)', 'cnt')->addFromTable('cidev_gmc_quality_issues', 'xc')->
            addInnerJoin('products', 'xp', "xp.productid = xc.productid AND xp.forsale='Y'");
            if (!empty($aParams['search'])) {
                $sSearch = addslashes($aParams['search']);
                $oSQL->addCondition("(xp.productcode LIKE '%" . $sSearch . "%' OR xp.product LIKE '%" . $sSearch . "%')");
                unset($aParams['search']);
            }
            if (!is_null($this->getStoreFront())) {
                $oSQL->addInnerJoin('products_sf', 'psf', 'psf.productid = xc.productid AND psf.sfid=' . $this->getStoreFront());
            }
            $aCount = $oSQL->addFilter($aParams)->Execute()->getQueryResult();

            $aC = reset($aCount);
            $this->iProductCount = $aC['cnt'];
        }
        return $this->iProductCount;
    }

    public function getProductImpacted($first_page = 0, $objects_per_page = 50, $aParams)
    {
        if (is_null($this->aProductsIssues)) {
            $iIssueId = $this->getIssueId();
            if ($iIssueId) $aParams['issue_id'] = $this->getIssueId();
            $oSQL = classSQLBuilder::getInstance();
            $oSQL->addSelect('xc.*')->addFromTable('cidev_gmc_quality_issues', 'xc')->
            addInnerJoin('products', 'xp', "xp.productid = xc.productid AND xp.forsale='Y'");
            if (!empty($aParams['search'])) {
                $sSearch = addslashes($aParams['search']);
                $oSQL->addCondition("(xp.productcode LIKE '%" . $sSearch . "%' OR xp.product LIKE '%" . $sSearch . "%')");
                unset($aParams['search']);
            }
            if (!is_null($this->getStoreFront())) {
                $oSQL->addInnerJoin('products_sf', 'psf', 'psf.productid = xc.productid AND psf.sfid=' . $this->getStoreFront());
            }

            $aProductImpacted = $oSQL->addFilter($aParams)->setLimit("$first_page, $objects_per_page")->Execute()->getQueryResult();

            if ($aProductImpacted) {
                foreach ($aProductImpacted as $aProducts) {
                    $oIssue = new Xcart\GMCQualityIssues();
                    $oIssue->fill($aProducts);
                    $this->aProductsIssues[] = $oIssue;
                }
            }
        }

        return $this->aProductsIssues;
    }

    public static function sortByIssueProductsCount($a, $b)
    {
        return $a->getProductImpactedCount() < $b->getProductImpactedCount();
    }

    public function setStoreFront($iStoreFrontId)
    {
        $this->iStoreFrontId = $iStoreFrontId;
    }

    public function getStoreFront()
    {
        return $this->iStoreFrontId;
    }
}