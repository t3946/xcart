<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/icon.css">
<link rel="stylesheet" href="{$SkinDir}/js/semantic/components/checkbox.min.css">
<script src="{$SkinDir}/js/semantic/components/checkbox.min.js" type="text/javascript"></script>

<table cellpadding="3" cellspacing="1" width="100%" id="amazon_verification">

    <tr class="TableHead">
        <th></th>
        <th style="width: 120px">SKU</th>
        <th style="width: 250px; overflow: hidden; display: inline-block; white-space: nowrap;">Product name</th>
        <th>Cost to us</th>
        <th style="width: 140px; overflow: hidden; white-space: nowrap;">Asin</th>
        <th style="width: 80px;">Amazon FBA restricted</th>
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
                <td align="{if $asin_edit}right{else}center{/if}">
                    <a target="_blank" href="{$aVerifiactionResult.AmazonLink}">{$aVerifiactionResult.pasin}</a>
                    {if $asin_edit}<button data-asin="{$aVerifiactionResult.pasin}" class="ui button"><i class="edit icon"></i></button>{/if}
                </td>
                <td align="center">
                    <div class="ui toggle checkbox" style="width:50px;">
                        <input type="checkbox" {if $aVerifiactionResult.Product->isAmazonFBARestricted()}checked="checked"{/if}><label></label>
                    </div>
                </td>
            </tr>
        {/foreach}
    {/if}
</table>
{literal}
<script type="text/javascript">
    $('#amazon_verification').find('.ui.toggle.checkbox > input').change(function (){
        var iProductId = $(this).closest('tr').data('product-id');
        $.post('ajax_admin.php', {
                    product_id: iProductId,
                    status: $(this).attr('checked'),
                    ajax_action: 'product_amazon_fba_restricted_change'
                },
                function (data) {
                    if (data.result) {

                    }
                },
                'json');
    })
</script>
{/literal}