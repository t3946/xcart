<div class="survey_form">

    <p class="survey_form_header">Info Request Survey</p>

    <form action="stock_availability.php" method="POST">
    <input type="hidden"  name="m" value="{$m}" />
    <input type="hidden"  name="s" value="{$s}" />
    <input type="hidden"  name="o" value="{$o}" />
    <input type="hidden"  name="mode" value="sent" />

        <div>
            <p class="survey_form_title">Products in Stock</p>

            <!-- 01 block -->

            <p>Please confirm that all of these items are in stock:</p>
            <table class="survey">
                <tr>
                    <th>Item #</th>
                    <th class="survey_form_right">Quantity required</th>
                    <th class="survey_form_center">In stock</th>
                    <th></th>
                </tr>

{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
                <tr>
                    <td nowrap="nowrap">{$item.productcode}</td>
                    <td class="survey_form_right" id="items_amount_{$item.productid}">{$item.amount}</td>
                    <td class="survey_form_center">
                        <input type="text" id="items_stock_{$item.productid}" name="items_stock[{$item.productid}]" size="10">
                    </td>
                    <td>
                        <button class="all_in_stock" id="all_in_stock_{$item.productid}" type="button" onclick="javascript:{literal} var q=$('#items_amount_{/literal}{$item.productid}{literal}').html(); $('#items_stock_{/literal}{$item.productid}{literal}').val(q);{/literal}"></button>
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

            <p>Please provide us with a shipping quote for the above products to this destination:</p>
            <table class="survey">
                <tr>
                    <td>{$order.s_city}, {$order.s_state}</td>
                    <td>{$order.s_zipcode}</td>
                    <td>
                        <span class="survey_form_blue">$</span> <input type="text" name="actual_shipping_net" size="10">
                    </td>
                </tr>
            </table>
        </div>

        <!-- 03 block -->

        <div>
            <p class="survey_form_title">'Cost to Us'</p>

            <p>Please let us know the cost to us (the merchant) for this order.</p>
            <table class="survey">
                <tr>
                    <th>Item #</th>
                    <th class="survey_form_right">Quantity required</th>
                    <th class="survey_form_center">Cost to us</th>
                </tr>

{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
                <tr>
                    <td nowrap="nowrap">{$item.productcode}</td>
                    <td class="survey_form_right">{$item.amount}</td>
                    <td class="survey_form_center">
                        <input type="text" name="cost_to_us[{$item.productid}]" size="10">
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
