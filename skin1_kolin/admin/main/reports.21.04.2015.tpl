<br />
{capture name=dialog}

{if !($membership_code eq "ADMIN_CUSTOMER_SERVICE")}
<a href="{$catalogs.admin}/product_reports.php" class="VertMenuItems">{$lng.lbl_product_reports}</a><br />
{/if}

{if !($membership_code eq "ADMIN_PRODUCT_MANAGER")}
<a href="{$catalogs.admin}/order_reports.php" class="VertMenuItems">{$lng.lbl_order_reports}</a><br />
{/if}

<a href="{$catalogs.admin}/amazon_fba_restocking_report.php" class="VertMenuItems">Amazon FBA restocking report</a><br />

{/capture}
{include file="dialog.tpl" title="Reports" content=$smarty.capture.dialog extra='width="100%"'}
