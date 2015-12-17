<br />
{capture name=dialog}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
<a href="{$catalogs.admin}/product_reports.php" class="VertMenuItems">{$lng.lbl_product_reports}</a><br />
{/if}

{*
{if !($membership_code eq "ADMIN_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/order_reports.php" class="VertMenuItems">{$lng.lbl_order_reports}</a><br />
{/if}
*}

{if $membership_code eq ""}
<a href="{$catalogs.admin}/amazon_fba_restocking_report.php" class="VertMenuItems">Amazon FBA restocking report</a><br />

<a href="{$catalogs.admin}/fba_roi_report.php" class="VertMenuItems">FBA ROI report (year based)</a><br />

<a href="{$catalogs.admin}/amazon_settlement_report_analyzer.php" class="VertMenuItems">Amazon Settlement Reports Analyzer</a><br />
{/if}

{/capture}
{include file="dialog.tpl" title="Reports" content=$smarty.capture.dialog extra='width="100%"'}
