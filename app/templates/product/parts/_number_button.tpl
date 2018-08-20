<a class="button number-button str-down grey-border hover-blue">
    <span class="number"
          data-max="{$model->avail}"
          data-min="{$model->min_amount}"
          data-number="5"
          data-step="{if $model->mult_order_quantity == 'Y'}{$model->min_amount}{else}1{/if}"
          id="quantity-{$model->productid}">{$model->min_amount}</span>
</a>