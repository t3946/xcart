{config_load file="$skin_config"}
<div class="survey_form">

    <p class="survey_form_header">Info Request Survey</p>

        <div>
            <p class="survey_form_title">Products in Stock</p>

            <!-- 01 block -->

            <p>Please confirm that all of these items are in stock:</p>
            <table class="survey">
                <tr>
                    <th>Item #</th>
                    <th class="survey_form_right">Quantity required</th>
                    <th class="survey_form_center">In stock</th>
                </tr>

{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
                <tr>
                    <td nowrap="nowrap">{$item.productcode}</td>
                    <td class="survey_form_right" align="right">{$item.amount}</td>
                    <td class="survey_form_center" align="center">{$items_stock[$item.productid]}</td>
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
                    <td><span class="survey_form_blue">$</span>{$actual_shipping_net}</td>
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
                    <td class="survey_form_right" align="right">{$item.amount}</td>
                    <td class="survey_form_center" align="center">{$cost_to_us[$item.productid]}</td>
                </tr>
{/if}
{/foreach}
{/if}

            </table>
        </div>
</div>

