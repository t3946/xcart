	<div>
            <p class="survey_form_title">Products in Stock</p>

            <!-- 01 block -->

            <p>Please confirm that all of these items are in stock:</p>
            <table class="survey">
                <tr>
                    <th>Item #</th>
                    <th class="survey_form_right">Quantity required</th>
                    <th class="survey_form_center">Stock status</th>
                    <th class="survey_form_center">Quantity in stock</th>
                    <th class="survey_form_center">FBA qty</th>
                    <th class="survey_form_center" nowrap="nowrap">ETA date<br />(mm/dd/yyyy)</th>
                    <th class="survey_form_center">Offer<br />backorder</th>
                </tr>

{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
                <tr>
                    <td nowrap="nowrap">{$item.mpn}</td>
                    <td class="survey_form_right" id="items_amount_{$item.productid}">{$item.amount}</td>
                    <td class="survey_form_right">
                        <select name="stock_status[{$item.productid}]" id="stock_status"
                        onchange="javasript:{literal} 
                        if (this.value == 'all_in_stock'){ 
                                $('#div_items_stock_{/literal}{$item.productid}{literal}').hide();
                                $('#div_eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}').hide();
                                $('#div_offer_backorder_{/literal}{$item.productid}{literal}').hide();
                        }else if (this.value == 'some_in_stock'){
                                $('#div_items_stock_{/literal}{$item.productid}{literal}').show();
                                $('#div_eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}').show();
                                $('#div_offer_backorder_{/literal}{$item.productid}{literal}').show();
                        }else if (this.value == 'out_of_stock'){
                                $('#div_items_stock_{/literal}{$item.productid}{literal}').hide();
                                $('#div_eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}').show();
                                $('#div_offer_backorder_{/literal}{$item.productid}{literal}').show();
                        }else if (this.value == 'discontinued'){
                                $('#div_items_stock_{/literal}{$item.productid}{literal}').hide();
                                $('#div_eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}').hide();
                                $('#div_offer_backorder_{/literal}{$item.productid}{literal}').hide();
                        }
                        {/literal}">
                                <option value="all_in_stock" 
{if $item.stock_request_status eq "all_in_stock"}selected="selected"
{elseif $item.stock_request_status eq "" && $admin_area_uses eq "Y" && $item.forsale eq "Y" && $item.back eq "0" && $item.eta_date_mm_dd_yyyy eq ""}selected="selected"{/if}>
					{if $item.amount eq "1"}In stock{else}All items are in stock{/if}
				</option>
				{if $item.amount gt 1}
                                <option value="some_in_stock" 
{if $item.stock_request_status eq "some_in_stock"}selected="selected"
{elseif $item.stock_request_status eq "" && $admin_area_uses eq "Y" && $item.forsale eq "Y" && $item.back gt "0" && $item.back ne $item.amount}selected="selected"{/if}>
					Some items are in stock
				</option>
				{/if}
                                <option value="out_of_stock" 
{if $item.stock_request_status eq "out_of_stock"}selected="selected"
{elseif $item.stock_request_status eq "" &&  $admin_area_uses eq "Y" && $item.forsale eq "Y" && $item.back eq $item.amount}selected="selected"{/if}>
					Out of stock
				</option>
                                <option value="discontinued" 
{if $item.stock_request_status eq "discontinued"}selected="selected"
{elseif $item.stock_request_status eq "" && $admin_area_uses eq "Y" && $item.forsale eq "N" && $item.back gt "0"}selected="selected"{/if}>
					Discontinued
				</option>
                        </select>
                    </td>
                    <td class="survey_form_center">

                      <div id="div_items_stock_{$item.productid}" 
			{if $admin_area_uses eq "Y"}
				{assign var="tmp_items_stock" value=""}
				{if $item.forsale eq "Y" && $item.back gt "0" && $item.back ne $item.amount}
					{math assign="tmp_items_stock" equation="y-x" y=$item.amount x=$item.back}
				{else}
					style="display: none;"
				{/if}
			{else}				
				style="display: none;"
			{/if}
		      >
                        <input type="text" id="items_stock_{$item.productid}" name="items_stock[{$item.productid}]" value="{if $admin_area_uses eq "Y"}{$tmp_items_stock}{/if}" size="4" style="width: 98%;">
                      </div>

                    </td>

		    <td align="center">
			{$item.amazon_fba_avail}
		    </td>

                    <td>
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}").datepicker();
  });
{/literal}
-->
</script>

                      <div id="div_eta_date_mm_dd_yyyy_{$item.productid}" 
			{if $admin_area_uses eq "Y"}
				{if ($item.forsale eq "Y" && $item.back eq $item.amount) || ($item.forsale eq "Y" && $item.back gt "0" && $item.back ne $item.amount)}
				{else}
					style="display: none;"
				{/if}
			{else}
				style="display: none;"
			{/if}
		       >
                        <input id="eta_date_mm_dd_yyyy_{$item.productid}" type="text" size="9" style="width: 98%;" name="eta_date_mm_dd_yyyy[{$item.productid}]" value="{if $admin_area_uses eq "Y"}{$item.eta_date_mm_dd_yyyy}{/if}" />
                      </div>


{*
                        <button class="all_in_stock" id="all_in_stock_{$item.productid}" type="button" onclick="javascript:{literal} var q=$('#items_amount_{/literal}{$item.productid}{literal}').html(); $('#items_stock_{/literal}{$item.productid}{literal}').val(q);{/literal}"></button>
*}
                    </td>


		    <td align="center">
                      <div id="div_offer_backorder_{$item.productid}" 
                        {if $admin_area_uses eq "Y"}
                                {if $item.forsale eq "Y" && $item.back gt "0"}
                                {else}
                                        style="display: none; text-align: center;"
                                {/if}
                        {else}                          
                                style="display: none; text-align: center;"
                        {/if}
                      >
                        <input type="checkbox" id="offer_backorder_{$item.productid}" name="offer_backorder[{$item.productid}]" {if $item.offer_backorder eq "Y"}checked="checked"{/if} value="Y" />
                      </div>
		    </td>
                </tr>
{/if}
{/foreach}
{/if}

            </table>
        </div>

