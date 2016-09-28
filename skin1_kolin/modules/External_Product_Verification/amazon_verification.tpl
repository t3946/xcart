{include file="main/multirow.tpl"}
<B>Test batch options</B>
<hr/>
<table cellpadding="3" cellspacing="1" width="100%" id="amazon_verification">

    <tr class="TableHead">
        <th style="background-color: white;" width="3%"> </th>
        <th width="1">Pos.</th>
        <th width="10">SKU</th>
        <th>Product name</th>
        <th width="200">Correct answer</th>
        <th width="150">Asin</th>
        <th>Del</th>
    </tr>
    {if $aProductsQueue}
        {foreach from=$aProductsQueue item=oProductsQueue}
            {assign var=oProduct value=$oProductsQueue->getProductEntity()}
            {cycle assign=classVar name=$type values=", class='TableSubHead'"}
            <tr {$classVar}>
                <td>&nbsp;</td>
                <td><input size="5" name="position[{$oProduct->getProductId()}]" value="{$oProductsQueue->getPosition()}" type="text"/></td>
                <td>
                    <a target="_blank" href="{$oProduct->getProductModifyURL()}">{$oProduct->getSKU()}</a>
                </td>
                <td>
                    <a target="_blank" href="{$oProduct->getProductFrontURL()}">{$oProduct->getProductName()}</a>
                </td>
                <td>
                    <input name="answer[{$oProduct->getProductId()}]" {if $oProductsQueue->getStatus() == 'etalon_not_found'} checked {/if} value="etalon_not_found" type="radio"/>
                    <label>Product not found</label> <br>
                    <input name="answer[{$oProduct->getProductId()}]" {if $oProductsQueue->getStatus() == 'etalon_match'} checked {/if} value="etalon_match" type="radio"/>
                    <label>Match</label>
                    <input name="answer[{$oProduct->getProductId()}]" {if $oProductsQueue->getStatus() == 'etalon_not_match'} checked {/if} value="etalon_not_match" type="radio"/>
                    <label>Does NOT match</label>
                </td>
                <td style="vertical-align: bottom;">
                    {if $oProductsQueue->getAsin()}
                        {foreach from=$oProductsQueue->getAsin() item=sAsin name=asinloop}
                           <input style="width:84%; margin-bottom:3px; margin-right: 3px;" name="answerasin[{$oProduct->getProductId()}][]" value="{$sAsin}" type="text"/>{if !$smarty.foreach.asinloop.first}<a class="delete_multiple_asin" href="#"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_delete|escape}"/></a>{else}<a class="add_multiple_asin" href="#"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>{/if}
                        {/foreach}
                    {else}
                        <input style="width:84%; margin-bottom:3px; margin-right: 3px;" name="answerasin[{$oProduct->getProductId()}][]" value="" type="text"/><a class="add_multiple_asin" href="#"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
                    {/if}
                </td>
                <td><input type="checkbox" name="etalon_delete[{$oProduct->getProductId()}]"/></td>
            </tr>
        {/foreach}
        <tr>
            <td colspan="7"><hr/></td>
        </tr>
    {/if}
    <tr id="add_test_sku_row">
        <td id="add_test_sku_box_0" width="3%"> </td>
        <td id="add_test_sku_box_1">
            <input size="5" name="test_position[0]"/>
        </td>
        <td id="add_test_sku_box_2">
            <input name="test_sku[0]"/>
        </td>
        <td id="add_test_sku_box_3">

        </td>
        <td id="add_test_sku_box_4">
            <input name="correct_answer[0]" value="etalon_not_found" type="radio"/>
            <label>Product not found</label> <br>
            <input name="correct_answer[0]" value="etalon_match" type="radio"/>
            <label>Match</label>
            <input name="correct_answer[0]" value="etalon_not_match" type="radio"/>
            <label>Does NOT match</label>
        </td>
        <td style="vertical-align: bottom;" id="add_test_sku_box_5">
            <input style="width:84%; margin-bottom:3px; margin-right: 3px;" name="test_asin[0][]" value="" type="text"/><a class="add_multiple_asin" href="#"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
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
