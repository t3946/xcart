<div class="table__prices--row price-row {if $hidden}hidden af-anim{/if}" data-quantity="{$quantity}">
    <div class="column price">

        <div class="value">
            {$site_currency->symbol_prefix}{$site_currency} <span class="price">{$price|number_format:2}</span>{if $discount}<span class="discount {if $discount}show{/if}">{$discount}%</span>{/if}
        </div>
    </div>

    <div class="column quantity">
        <div class="value">
            {$quantity_line}
        </div>
    </div>
</div>