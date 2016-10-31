{include file="main/multirow.tpl"}
<B>Test batch options</B>
<hr/>
<table cellpadding="3" cellspacing="1" width="100%" id="amazon_verification">

    <tr class="TableHead">
        <th style="background-color: white;" width="3%"> </th>
        <th width="1">Pos.</th>
        <th width="1">SKU</th>
        <th style="width: 250px; overflow: hidden; display: inline-block; white-space: nowrap;">Product name</th>
        <th style="width: 120px; overflow: hidden; white-space: nowrap;">Asin</th>
        <th>Image</th>
        <th>Name</th>
        <th>Description</th>
        <th>Pack qty</th>
        <th width="200">Conclusion</th>
        <th>Del</th>
    </tr>
    {if $aProductsQueue}
        {foreach from=$aProductsQueue item=oProductsQueue}
            {assign var=oProduct value=$oProductsQueue->getProductEntity()}
            {cycle assign=classVar name=$type values=", class='TableSubHead'"}
            <tr {$classVar}>
                <td>&nbsp;</td>
                <td><input size="2" name="position[{$oProduct->getProductId()}]" value="{$oProductsQueue->getPosition()}" type="text"/></td>
                <td>
                    <a target="_blank" href="{$oProduct->getProductModifyURL()}">{$oProduct->getSKU()}</a>
                </td>
                <td>
                    <a target="_blank" href="{$oProduct->getProductFrontURL()}">{$oProduct->getProductName()}</a>
                </td>
                <td>
                    {if $oProductsQueue->getAsin()}
                        {foreach from=$oProductsQueue->getAsin() item=sAsin name=asinloop}
                            <input size="10" style="margin-bottom:3px; margin-right: 3px;" name="answerasin[{$oProduct->getProductId()}][]" value="{$sAsin}" type="text"/>{if !$smarty.foreach.asinloop.first}<a class="delete_multiple_asin" href="#"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_delete|escape}"/></a>{else}<a class="add_multiple_asin" href="#"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>{/if}
                        {/foreach}
                    {else}
                        <input size="10" style="margin-bottom:3px; margin-right: 3px;" name="answerasin[{$oProduct->getProductId()}][]" value="" type="text"/><a class="add_multiple_asin" href="#"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
                    {/if}
                </td>
                <td nowrap="nowrap">
                    <input name="product_image[{$oProduct->getProductId()}]" {if $oProductsQueue->getProductImage() == 'different'} checked {/if} value="different" type="radio"/>
                    <label>Different</label> <br>
                    <input name="product_image[{$oProduct->getProductId()}]" {if $oProductsQueue->getProductImage() == 'same'} checked {/if} value="same" type="radio"/>
                    <label>Same</label>
                </td>
                <td nowrap="nowrap">
                    <input name="product_names[{$oProduct->getProductId()}]" {if $oProductsQueue->getProductName() == 'contradict'} checked {/if} value="contradict" type="radio"/>
                    <label>Contradict</label> <br>
                    <input name="product_names[{$oProduct->getProductId()}]" {if $oProductsQueue->getProductName() == 'not_contradict'} checked {/if} value="not_contradict" type="radio"/>
                    <label>Not</label>
                </td>
                <td nowrap="nowrap">
                    <input name="product_description[{$oProduct->getProductId()}]" {if $oProductsQueue->getProductDescription() == 'contradict'} checked {/if} value="contradict" type="radio"/>
                    <label>Contradict</label> <br>
                    <input name="product_description[{$oProduct->getProductId()}]" {if $oProductsQueue->getProductDescription() == 'not_contradict'} checked {/if} value="not_contradict" type="radio"/>
                    <label>Not</label>
                </td>
                <td nowrap="nowrap">
                    <label>Amazon</label>
                    <input size="2" name="pack_qty_amazon[{$oProduct->getProductId()}]" value="{$oProductsQueue->getPackQtyAmazon()}" type="text"/><br/>
                    <label>Website</label>
                    <input size="2" name="pack_qty_website[{$oProduct->getProductId()}]" value="{$oProductsQueue->getPackQtyWebsite()}" type="text"/>
                </td>
                <td nowrap="nowrap">
                    <input name="answer[{$oProduct->getProductId()}]" {if $oProductsQueue->getStatus() == 'etalon_not_found'} checked {/if} value="etalon_not_found" type="radio"/>
                    <label>Product not found</label> <br>
                    <input name="answer[{$oProduct->getProductId()}]" {if $oProductsQueue->getStatus() == 'etalon_match'} checked {/if} value="etalon_match" type="radio"/>
                    <label><b>Match</b></label>
                    <input name="answer[{$oProduct->getProductId()}]" {if $oProductsQueue->getStatus() == 'etalon_not_match'} checked {/if} value="etalon_not_match" type="radio"/>
                    <label><b>Does NOT match</b></label>
                </td>

                <td><input type="checkbox" name="etalon_delete[{$oProduct->getProductId()}]"/></td>
            </tr>
        {/foreach}
        <tr>
            <td colspan="11"><hr/></td>
        </tr>
    {/if}
    <tr id="add_test_sku_row">
        <td id="add_test_sku_box_0" width="3%"> </td>
        <td id="add_test_sku_box_1">
            <input size="2" name="test_position[0]"/>
        </td>
        <td id="add_test_sku_box_2">
            <input size="10" name="test_sku[0]"/>
        </td>
        <td id="add_test_sku_box_3">
        </td>
        <td id="add_test_sku_box_4">
            <input size="12" style="margin-bottom:3px; margin-right: 3px;" name="test_asin[0][]" value="" type="text"/><a class="add_multiple_asin" href="#"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
        </td>
        <td id="add_test_sku_box_5">
            <input name="product_image[0]" value="different" type="radio"/>
            <label>Different</label> <br>
            <input name="product_image[0]" value="same" type="radio"/>
            <label>Same</label>
        </td>
        <td id="add_test_sku_box_6">
            <input name="product_names[0]" value="contradict" type="radio"/>
            <label>Contradict</label> <br>
            <input name="product_names[0]" value="not_contradict" type="radio"/>
            <label>Not</label>
        </td>
        <td id="add_test_sku_box_7">
            <input name="product_description[0]" value="contradict" type="radio"/>
            <label>Contradict</label> <br>
            <input name="product_description[0]"value="not_contradict" type="radio"/>
            <label>Not</label>
        </td>
        <td id="add_test_sku_box_8">
            <label>Amazon</label>
            <input size="2" name="pack_qty_amazon[0]" value="1" type="text"/><br/>
            <label>Website</label>
            <input size="2" name="pack_qty_website[0]" value="1" type="text"/>
        </td>
        <td id="add_test_sku_box_9">
            <input name="correct_answer[0]" value="etalon_not_found" type="radio"/>
            <label>Product not found</label> <br>
            <input name="correct_answer[0]" value="etalon_match" type="radio"/>
            <label><b>Match</b></label>
            <input name="correct_answer[0]" value="etalon_not_match" type="radio"/>
            <label><b>Does NOT match</b></label>
        </td>
        <td style="vertical-align: bottom;" id="add_test_sku_box_10">

        </td>
        <td align="center">
            {include file="buttons/multirow_add.tpl" mark="add_test_sku"}
        </td>
    </tr>
</table>
<br>
<br>
<script>
    {literal}
        $('#amazon_verification').on('click','.add_multiple_asin', function() {
            var clonerow = $(this).prev().andSelf().clone();
            $(this).after(clonerow);
            return false;
        });
        $('#amazon_verification').on('click','.delete_multiple_asin', function() {
            $(this).prev().andSelf().remove();
            return false;
        });
    {/literal}
</script>
