<div class="title-selected">Selected options:</div>
{foreach $options as $productOptionId => $variantId}

    {set $modelOptionVariant = $.getProductOptionVariantModel}
    {set $optionItem = $modelOptionVariant->findItem($productOptionId, $variantId)}
    {set $name = $optionItem->product_option->option->title}
    {set $type = $optionItem->product_option->option->type}
    {set $value = $optionItem->variant->name}

    {if $type == 'color'}
        {set $color = $optionItem->variant->value}
        <div class="value">
            <span class="name">{$name}</span>: <span class="value__value">
                <span class="value__value__color" style="background-color: {$color};"></span>
                {$value}
                </span>
        </div>
    {else}
        <div class="value">
            <span class="name">{$name}</span>: <span class="value__value">{$value}</span>
        </div>
    {/if}
{/foreach}
