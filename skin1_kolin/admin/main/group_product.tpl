{capture name=dialog}
    {if $oProduct->isGroupRoot()}
        <p><b>This product is group parent and has child products</b></p>
    {elseif $oProduct->isGroupChild()}
        <p><b>This product has group parent</b></p>
    {else}
        <p><b>This product has no any parent group products</b></p>
    {/if}
    {if $oProduct->isGroupRoot() || $oProduct->isGroupChild()}
        <table width="100%" class="group_product">
            <tr class="TableHead">
                <td>SKU</td>
                <td>Product</td>
                <td align="center">Forsale</td>
                {if $oProduct->isGroupRoot()}
                    <td>R avail</td>
                    <td>Cost to us</td>
                    <td>Price</td>
                {/if}
                <td></td>
            </tr>
            {if $oProduct->isGroupRoot()}
                {foreach from=$oProduct->childs item=child}
                    <tr data-product-id="{$child->productid}">
                        <td><a href="{$child->getAdminUrl()}" target="_blank">{$child->productcode}</a></td>
                        <td><a href="{$child->getUrl()}" target="_blank">{$child->product}</a></td>
                        <td align="center">{$child->forsale}</td>
                        <td align="center">{$child->r_avail}</td>
                        <td align="center">{$child->cost_to_us}</td>
                        <td align="center">{$child->getPrice()}</td>
                        <td align="center"><a href="#" class="remove_group"><img src="/skin1_kolin/images/minus.gif" alt="Remove" /></a></td>
                    </tr>
                {/foreach}
            {elseif $oProduct->isGroupChild()}
                {assign var="parent" value=$oProduct->parent}
                <tr data-product-id="{$oProduct->productid}">
                    <td><a href="{$parent->getAdminUrl()}" target="_blank">{$parent->productcode}</a></td>
                    <td><a href="{$parent->getUrl()}" target="_blank">{$parent->product}</a></td>
                    <td align="center">{$parent->forsale}</td>
                    <td align="center"><a href="#" class="remove_group"><img src="/skin1_kolin/images/minus.gif" alt="Remove" /></a></td>
                </tr>
            {/if}
        </table>
    {/if}
    {if $oProduct->isGroupRoot() || (!$oProduct->isGroupRoot() && !$oProduct->isGroupChild())}
    <br>
    <div class="new_group" data-product-id="{$oProduct->productid}">
        <input class="add_new_group" autocomplete="off" type="text" name="add_new_group" placeholder="Enter product SKU"/>
        <input class="add" type="button" value="Add"/>
    </div>
    {/if}
{/capture}

{include file="dialog.tpl" title='Group product' content=$smarty.capture.dialog extra='width="100%"'}

{literal}
    <script type="text/javascript">
        $('.group_product .remove_group').click(function(e){
            e.preventDefault();
            var tr = $(this).closest('tr');
            var product_id = tr.data('product-id');
            tr.css('opacity', 0.4);
            $.post(
                '{/literal}{$url_group_remove}{literal}',
                {
                    product_id: product_id
                },
                function(data) {
                    tr.fadeOut().remove();
                }
            );

        });
        $('.new_group .add').click(function(){
           var parent = $(this).closest('.new_group');
           var product_id = parent.data('product-id');
           parent.css('opacity', 0.4);
           $.post(
                '{/literal}{$url_group_add}{literal}',
                {
                    product_id: product_id,
                    add_sku: parent.find('.add_new_group').val()
                },
                function(data) {
                    parent.css('opacity', 1);
                    if (data.error) {
                        alert(data.error);
                    } else {
                        location.reload();
                    }
                }
           );
        })
    </script>
{/literal}