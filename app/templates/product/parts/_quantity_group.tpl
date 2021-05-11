{set $min = $model->min_amount}
{set $quantity = $quantity ? $quantity : $min}
{set $group_class = $group_class ? " $group_class" : ''}
{set $btn_class = $btn_class ? " $btn_class" : ''}

<div class="quantity-group{$group_class}">
    <span class="quantity-group-btn quantity-group-btn_dec {if $quantity > $min}quantity-group-btn_active{/if}{$btn_class}">
        <svg class="icon quantity-group-icon"><use xlink:href="/static/frontend/images/icons/sprite.svg#switcher-minus"></use></svg>
    </span>
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
            inputmode="numeric"
    />
    <span class="quantity-group-btn quantity-group-btn_inc {if $quantity <= $model->avail}quantity-group-btn_active{/if}{$btn_class}">
        <svg class="icon quantity-group-icon"><use xlink:href="/static/frontend/images/icons/sprite.svg#switcher-plus"></use></svg>
    </span>
</div>