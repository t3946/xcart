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
<table cellpadding="3" cellspacing="1" width="100%" id="amazon_verification">

    <tr class="TableHead">
        <th></th>
        <th>SKU</th>
        <th style="width: 250px; overflow: hidden; display: inline-block; white-space: nowrap;">Product name</th>
        <th style="width: 120px; overflow: hidden; white-space: nowrap;">Asin</th>
        <th>Del</th>
    </tr>
    {if $aVerifiactionResults}
    {foreach from=$aVerifiactionResults item=aVerifiactionResult}
        <tr {cycle values=", class='TableSubHead'"}>
            <td><input name="productids[{$aVerifiactionResult.Product->getProductId()}]" type="checkbox"/>
                <input type="hidden" name="productasin[{$aVerifiactionResult.Product->getProductId()}]" value="{$aVerifiactionResult.pasin}" />
            </td>
            <td>
                <a target="_blank" href="{$aVerifiactionResult.Product->getProductModifyURL()}">{$aVerifiactionResult.Product->getSKU()}</a>
            </td>
            <td>
                <a target="_blank" href="{$aVerifiactionResult.Product->getProductFrontURL()}">{$aVerifiactionResult.Product->getProductName()}</a>
            </td>
            <td>
                <a target="_blank" href="{$aVerifiactionResult.AsinLink}">{$aVerifiactionResult.pasin}</a>
            </td>
        </tr>
    {/foreach}
{/if}
</table>
    <p>
        <input type="submit" value="Submit to listing loader" />
    </p>
    {include file="customer/main/per_page_editor.tpl" per_page=$per_page per_page_text='Products per page'}
    {include file="customer/main/navigation.tpl"}
</form>
{/capture}

{include file="dialog.tpl" title='Creating Product Listings on Amazon' content=$smarty.capture.amazon_products_listing extra='width="100%"'}