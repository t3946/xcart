<div class="table__prices--row price-row product-quantity-row__wholesale product-quantity_wholesale-row {if $hidden}hidden af-anim{/if}" data-quantity="{$quantity}">
    <div class="col-4 text-center">
        <div class="value">
            {$price|site_currency}{if $discount}<span class="discount-percent {if $discount}show{/if}">{$discount}%</span>{/if}
        </div>
    </div>

    <div class="col-4 text-center quantity">
        <div class="value">
            {$quantity_line}
        </div>
    </div>
</div>