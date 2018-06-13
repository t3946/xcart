<a class="button number-button grey-border">
    <span class="number"
          data-max="{$model->avail}"
          data-min="{$model->min_amount}"
          data-number="5"
          data-step="{if $model->mult_order_quantity == 'Y'}{$model->min_amount}{else}1{/if}"
          id="quantity-{$model->productid}">{$model->min_amount}</span>
</a>
{*<a class="button number-button grey-border">*}
    {*<span class="number"*}
          {*data-max="15"*}
          {*data-min="2"*}
          {*data-number="5"*}
          {*data-step="1"*}
          {*id="quantity-{$model->productid}">2</span>*}
{*</a>*}