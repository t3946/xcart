<br>
<br>
{capture name=amazon_products_listing}
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
            <td><input type="checkbox"/></td>
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
{/capture}

{include file="dialog.tpl" title='Creating Product Listings on Amazon' content=$smarty.capture.amazon_products_listing extra='width="100%"'}