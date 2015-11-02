{config_load file="$skin_config"}
Info Request Survey

Products in Stock
Please confirm that all of these items are in stock:
{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
Item # {$item.productcode}
Quantity required: {$item.amount}
In stock: {$items_stock[$item.productid]}
{/if}
{/foreach}
{/if}

Shipping Cost
Please provide us with a shipping quote for the above products to this destination:
{$order.s_city}, {$order.s_state}
{$order.s_zipcode}
${$actual_shipping_net}

'Cost to Us'
Please let us know the cost to us (the merchant) for this order.
{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
Item # {$item.productcode}
Quantity required: {$item.amount}
Cost to us: {$cost_to_us[$item.productid]}
{/if}
{/foreach}
{/if}

