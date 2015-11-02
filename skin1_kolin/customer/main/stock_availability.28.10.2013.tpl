<div class="survey_form">

    {if $admin_area_uses eq "Y"}
     <form action="order.php" method="post" name="7_mnfnotifyform_{$mnf_id}">
	<input type="hidden" name="orderid" value="{$order.orderid}" />
	<input type="hidden" name="mnf_id" value="{$mnf_id}" />
	<input type="hidden" name="mode" id="mode_info_request_survey" value="mode_info_request_survey" />
    {else}
     <script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
     <link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.admin.css" />

     <p class="survey_form_header">Information Request</p>
     <form action="stock_availability.php" method="POST">
	<input type="hidden"  name="m" value="{$m}" />
	<input type="hidden"  name="s" value="{$s}" />
	<input type="hidden"  name="o" value="{$o}" />
	<input type="hidden"  name="mode" value="sent" />
    {/if}
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
                    <th class="survey_form_center" nowrap="nowrap">ETA date<br />(mm/dd/yyyy)</th>
                </tr>

{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
                <tr>
                    <td nowrap="nowrap">{$item.mpn}</td>
                    <td class="survey_form_right" id="items_amount_{$item.productid}">{$item.amount}</td>
                    <td class="survey_form_center">
			<select name="stock_status[{$item.productid}]" id="stock_status"
			onchange="javasript:{literal} 
			if (this.value == 'all_in_stock'){ 
				$('#div_items_stock_{/literal}{$item.productid}{literal}').hide();
				$('#div_eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}').hide();
			}else if (this.value == 'some_in_stock'){
                                $('#div_items_stock_{/literal}{$item.productid}{literal}').show();
                                $('#div_eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}').show();
                        }else if (this.value == 'out_of_stock'){
                                $('#div_items_stock_{/literal}{$item.productid}{literal}').hide();
                                $('#div_eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}').show();
                        }else if (this.value == 'discontinued'){
                                $('#div_items_stock_{/literal}{$item.productid}{literal}').hide();
                                $('#div_eta_date_mm_dd_yyyy_{/literal}{$item.productid}{literal}').hide();
			}
			{/literal}">
				<option value="all_in_stock">All items are in stock</option>
				<option value="some_in_stock">Some items are in stock</option>
				<option value="out_of_stock">Out of stock</option>
				<option value="discontinued">Discontinued</option>
			</select>
		    </td>
                    <td class="survey_form_center">

		      <div id="div_items_stock_{$item.productid}" style="display: none;">
                        <input type="text" id="items_stock_{$item.productid}" name="items_stock[{$item.productid}]" size="4" style="width: 98%;">
		      </div>

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

                      <div id="div_eta_date_mm_dd_yyyy_{$item.productid}" style="display: none;">
			<input id="eta_date_mm_dd_yyyy_{$item.productid}" type="text" size="9" style="width: 98%;" name="eta_date_mm_dd_yyyy[{$item.productid}]" />
                      </div>


{*
                        <button class="all_in_stock" id="all_in_stock_{$item.productid}" type="button" onclick="javascript:{literal} var q=$('#items_amount_{/literal}{$item.productid}{literal}').html(); $('#items_stock_{/literal}{$item.productid}{literal}').val(q);{/literal}"></button>
*}
                    </td>
                </tr>
{/if}
{/foreach}
{/if}

            </table>
        </div>

        <!-- 02 block -->

        <div>
            <p class="survey_form_title">Shipping Cost</p>

            <table>
                <tr><td nowrap="nowrap">Please provide us with a shipping quote for the above products to this destination:</td></tr>
	    </table>

            <table class="survey">
                <tr>
                    <td>{$order.s_city}, {$order.s_state} &nbsp;{$order.s_zipcode}</td>
                    <td>
                        <span class="survey_form_blue">$</span> <input type="text" name="actual_shipping_net" size="10">
                    </td>
                </tr>
            </table>
        </div>

        <!-- 03 block -->

        <div>
            <p class="survey_form_title">'Cost to Us'</p>

            <p>Please let us know cost to us for the following items:</p>
            <table class="survey">
                <tr>
                    <th>Item #</th>
                    <th class="survey_form_right">Quantity<br />required</th>
                    <th class="survey_form_center">Cost to us<br />per item</th>
                </tr>

{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
                <tr>
                    <td nowrap="nowrap">{$item.mpn}</td>
                    <td class="survey_form_right">{$item.amount}</td>
                    <td class="survey_form_center">
                        <span class="survey_form_blue">$</span> <input type="text" name="cost_to_us[{$item.productid}]" size="10">
                    </td>
                    <td>
                    </td>
                </tr>
{/if}
{/foreach}
{/if}

            </table>
        </div>

        <!--submit button-->
        <div class="survey_button">
            <p>Thank you for your time!</p>
            <button id="submit" type="submit"></button>
        </div>
    </form>
</div>
