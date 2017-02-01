<table cellpadding="3" cellspacing="1" width="100%" id="amazon_verification">

    <tr class="TableHead">
        <th></th>
        <th style="width: 120px">SKU</th>
        <th style="width: 250px; overflow: hidden; display: inline-block; white-space: nowrap;">Product name</th>
        <th>Cost to us</th>
        <th style="width: 140px; overflow: hidden; white-space: nowrap;">Asin</th>
        <th>Status</th>
    </tr>
    {if $aVerifiactionResults}
        {foreach from=$aVerifiactionResults item=aVerifiactionResult}
            <tr {cycle values=", class='TableSubHead'"} data-product-id="{$aVerifiactionResult.Product->getProductId()}">
                <td>{if !($readonly)}
                    <input name="productids[{$aVerifiactionResult.Product->getProductId()}]" type="checkbox"/>
                    <input type="hidden" name="productasin[{$aVerifiactionResult.Product->getProductId()}]" value="{$aVerifiactionResult.pasin}" />{/if}
                </td>
                <td>
                    <a target="_blank" href="{$aVerifiactionResult.Product->getAdminUrl()}">{$aVerifiactionResult.Product->getSKU()}</a>
                </td>
                <td>
                    <a target="_blank" href="{$aVerifiactionResult.Product->getAdminUrl()}">{$aVerifiactionResult.Product->getProductName()}</a>
                </td>
                <td align="center">
                    {include file="currency2.tpl" value=$aVerifiactionResult.Product->getProductCostToUs()}
                </td>
                <td align="right">
                    <a target="_blank" href="{$aVerifiactionResult.AmazonLink}">{$aVerifiactionResult.pasin}</a>
                    <button class="ui button"><i class="edit icon"></i></button>
                </td>
                <td align="center">
                    {$aVerifiactionResult.status}
                </td>
            </tr>
        {/foreach}
    {/if}
</table>