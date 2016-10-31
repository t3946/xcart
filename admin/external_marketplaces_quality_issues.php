<?php
global $xcart_dir, $config, $REQUEST_METHOD, $location, $first_page, $current_storefront;

require "./auth.php";
require $xcart_dir . "/include/security.php";

$objects_per_page = intval($config['Appearance']['products_per_page_admin']);

$smarty->assign("storefrontid", $current_storefront);

if (!empty($issue) && is_numeric($issue) || !empty($search)) {

    $location[] = ['Quality Issues Processing Rules', "external_marketplaces_quality_issues.php"];

    $aFilterParams = ['fixed' => 'N'];
    $aIssueParam = null;
    if (!empty($issue)) {
        $aIssueParam = ['issue_id' => $issue];
        $oIssue = new Xcart\External_MarketPlace\IssuesProcessingRules($aIssueParam);
        $smarty->assign("navigation_script", "external_marketplaces_quality_issues.php?issue=$issue");
        $location[] = [$oIssue->getIssueName(), ""];
    }
    if (!empty($search)) {
        $aFilterParams ['search'] = $search;
        $oIssue = new Xcart\External_MarketPlace\IssuesProcessingRules($aIssueParam);
        $location[] = ['Search results', ""];
    }

    $oIssue->setStoreFront($current_storefront);

    $smarty->assign("oIssueProcessingRule", $oIssue);
    $totalcount = $oIssue->getProductImpactedCount($aFilterParams);
    if ($totalcount > 0) {

        $total_nav_pages = ceil($totalcount / $objects_per_page) + 1;
        include $xcart_dir . "/include/navigation.php";

        $aImpactedProducts = $oIssue->getProductImpacted($first_page, $objects_per_page, $aFilterParams);
        $smarty->assign("aImpactedProducts", $aImpactedProducts);

    }
    $smarty->assign("main", "external_marketplaces_quality_issues_view");
} else {
    $smarty->assign("main", "external_marketplaces_quality_issues");

    $aIssueList = Xcart\External_MarketPlace\IssuesProcessingRules::getIssuesList($current_storefront);
    if (!empty($aIssueList))
        usort ($aIssueList,['Xcart\External_MarketPlace\IssuesProcessingRules','sortByIssueProductsCount']);
    $smarty->assign('aProcessingRules', $aIssueList);
    $smarty->assign('statuses', ['exclude', 'manual','skip']);
    $location[] = ['Quality Issues Processing Rules', ""];
}

$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);