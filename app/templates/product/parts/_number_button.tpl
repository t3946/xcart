<a class="button number-button grey-border">
    <span class="number"
          data-max="{$model->avail}"
          data-min="{$model->min_amount}"
          data-number="5"
          {*step="{if $model->mult_order_quantity == 'Y'}{$model->min_amount}{else}1{/if}"*}
          id="quantity-{$model->productid}">{$model->min_amount}</span>
</a>