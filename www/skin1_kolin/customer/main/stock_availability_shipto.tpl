        <div>
            <p class="survey_form_title">Shipping Cost</p>

            <table>
                <tr><td nowrap="nowrap">Please provide us with a shipping quote for the above products to this destination:</td></tr>
            </table>

            <table class="survey">
                <tr>
                    <td>{$order.s_city}, {$order.s_state} &nbsp;{$order.s_zipcode}</td>
                    <td>
                        <span class="survey_form_blue">$</span> <input type="text" name="actual_shipping_net" size="10" {if $admin_area_uses eq "Y"}value="{if $order.shipping_groups.$m.stock_request_shipping_cost eq "0.00"}{else}{$order.shipping_groups.$m.stock_request_shipping_cost}{/if}"{/if} />
                    </td>
                </tr>
            </table>
        </div>
