<table cellpadding="3" cellspacing="1" width="100%" id="amazon_verification">

    <tr class="TableHead">
        <th></th>
        <th>SKU</th>
        <th style="width: 250px; overflow: hidden; display: inline-block; white-space: nowrap;">Product name</th>
        <th style="width: 120px; overflow: hidden; white-space: nowrap;">Asin</th>
        <th>Status</th>
    </tr>
    {if $aVerifiactionResults}
        {foreach from=$aVerifiactionResults item=aVerifiactionResult}
            <tr {cycle values=", class='TableSubHead'"}>
                <td>{if !($readonly)}
                    <input name="productids[{$aVerifiactionResult.Product->getProductId()}]" type="checkbox"/>
                    <input type="hidden" name="productasin[{$aVerifiactionResult.Product->getProductId()}]" value="{$aVerifiactionResult.pasin}" />{/if}
                </td>
                <td>
                    <a target="_blank" href="{$aVerifiactionResult.Product->getProductModifyURL()}">{$aVerifiactionResult.Product->getSKU()}</a>
                </td>
                <td>
                    <a target="_blank" href="{$aVerifiactionResult.Product->getProductFrontURL()}">{$aVerifiactionResult.Product->getProductName()}</a>
                </td>
                <td align="center">
                    <a target="_blank" href="{$aVerifiactionResult.AsinLink}">{$aVerifiactionResult.pasin}</a>
                </td>
                <td align="center">{$aVerifiactionResult.status}</td>
            </tr>
        {/foreach}
    {/if}
</table>