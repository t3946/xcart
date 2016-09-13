{include file="main/multirow.tpl"}
<B>Test batch options</B>
<hr/>
<table cellpadding="3" cellspacing="1" width="100%" id="amazon_verification">

    <tr class="TableHead">
        <th style="background-color: white;" width="3%"> </th>
        <th width="10">SKU</th>
        <th>Product name</th>
        <th width="200">Correct answer</th>
        <th>Del</th>
    </tr>
    {if $aProductsQueue}
        {foreach from=$aProductsQueue item=oProductsQueue}
            {assign var=oProduct value=$oProductsQueue->getProductEntity()}
            <tr>
                <td>&nbsp;</td>
                <td>
                    <a target="_blank" href="{$oProduct->getProductModifyURL()}">{$oProduct->getSKU()}</a>
                </td>
                <td>
                    <a target="_blank" href="{$oProduct->getProductFrontURL()}">{$oProduct->getProductName()}</a>
                </td>
                <td>
                    <input name="answer[{$oProduct->getProductId()}]" {if $oProductsQueue->getStatus() == 'etalon_match'} checked {/if} value="etalon_match" type="radio"/>
                    <label>Match</label>
                    <input name="answer[{$oProduct->getProductId()}]" {if $oProductsQueue->getStatus() == 'etalon_not_match'} checked {/if} value="etalon_not_match" type="radio"/>
                    <label>Does not matched</label>
                </td>
                <td><input type="checkbox" name="etalon_delete[{$oProduct->getProductId()}]"/></td>
            </tr>
        {/foreach}
    {/if}
    <tr id="add_test_sku_row">
        <td id="add_test_sku_box_0" width="3%"> </td>
        <td id="add_test_sku_box_1">
            <input name="test_sku[0]"/>
        </td>
        <td id="add_test_sku_box_2">

        </td>
        <td id="add_test_sku_box_3">
            <input name="correct_answer[0]" value="etalon_match" type="radio"/>
            <label>Match</label>
            <input name="correct_answer[0]" value="etalon_not_match" type="radio"/>
            <label>Does not matched</label>
        </td>
        <td>
            {include file="buttons/multirow_add.tpl" mark="add_test_sku"}
        </td>
    </tr>
</table>
<br>
<br>