
{if $order_detail->product_options}
    <div>
        <b>Options:</b>
        {foreach $order_detail->product_options as $productOptionId => $variantId}

            {set $modelOptionVariant = $.getProductOptionVariantModel}
            {set $optionItem = $modelOptionVariant->findItem($productOptionId, $variantId)}
            {set $name = $optionItem->product_option->option->title}
            {set $value = $optionItem->variant->name}

            {$name}: {$value}
        {/foreach}
    </div>
{/if}