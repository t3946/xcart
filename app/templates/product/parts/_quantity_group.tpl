{set $min = $model->min_amount}
{set $quantity = $quantity ? $quantity : $min}

<div class="quantity-group">
    <span class="quantity-group-btn quantity-group-btn_dec {if $quantity > $min}quantity-group-btn_active{/if}">–</span>
    <input
            class="quantity-group-input"
            type="number"
            name="quantity"
            min="{$min}"
            max="{$model->avail}"
            data-min="{$min}"
            step="{if $model->mult_order_quantity == 'Y'}{$min}{else}1{/if}"
            value="{$quantity}"
            id="quantity-{$model->productid}"
            autocomplete="off"
    />
    <span class="quantity-group-btn quantity-group-btn_inc {if $quantity <= $model->avail}quantity-group-btn_active{/if}">+</span>
</div>