<div class="quantity-group">
    <span class="btn dec">-</span>
    <input type="number"
           name="quantity"
           min="{$model->min_amount}"
           max="{$model->avail}"
           data-min="{$model->min_amount}"
           step="{if $model->mult_order_quantity == 'Y'}{$model->min_amount}{else}1{/if}"
           value="{$model->min_amount}"
           id="quantity-{$model->productid}"
    />
    <span class="btn inc active">+</span>
</div>