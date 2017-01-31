<br>
<br>
{capture name=amazon_products_listing}
<div style="float:right"><a style="line-height:28px;" target="_blank" href="az_monitor_upload_status.php">Monitor Upload Status</a></div>
{include file="customer/main/per_page_editor.tpl" per_page=$per_page per_page_text='Products per page'}
{include file="customer/main/navigation.tpl"}
<div style="clear: both">
{include file="main/check_all_row.tpl" form="createlistingsform" prefix="productids"}
</div>

<form action="az_create_listings.php" method="post" name="createlistingsform">
    {include file="admin/main/az_listing_product_table.tpl"}
    <p>
        <input type="submit" value="Submit to listing loader" />
    </p>
    {include file="customer/main/per_page_editor.tpl" per_page=$per_page per_page_text='Products per page'}
    {include file="customer/main/navigation.tpl"}
</form>
{/capture}

{include file="dialog.tpl" title='Creating Product Listings on Amazon' content=$smarty.capture.amazon_products_listing extra='width="100%"'}