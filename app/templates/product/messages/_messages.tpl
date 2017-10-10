{*@TODO: Нахуй так жить =(*}

{set $class = ($fill! && $fill) ? 'fill' : '' }

{*{include "product/messages/_p_label.tpl" cls=$class ~ " lead-time" text=$model->lead_time_message}*}
{*{include "product/messages/_p_label.tpl" cls=$class ~ " multiply-quantity" text="Order in multiples of {$model->min_amount} items"}*}
{*{include "product/messages/_p_label.tpl" cls=$class ~ " last-items" text="Order at least {$model->avail} items"}*}

{if !$model->isOutOfStock()}
    {if $model->lead_time_message|trim}
        {include "product/messages/_p_label.tpl" cls=$class ~ " lead-time" text=$model->lead_time_message}
    {/if}
    
    {if $model->mult_order_quantity == 'Y' && $model->min_amount > 1}
        {include "product/messages/_p_label.tpl" cls=$class ~ " multiply-quantity" text="Order in multiples of {$model->min_amount} items"}
    {/if}
    
    {if $model->min_amount >= $model->avail}
        {include "product/messages/_p_label.tpl" cls=$class ~ " last-items" text="Order at least {$model->avail} items"}
    {/if}

{else}
    {if $fill! && $fill}
        {if $model->eta_date_mm_dd_yyyy && $model->eta_date_mm_dd_yyyy > time()}
            {include "product/messages/_p_label.tpl" cls=$class ~ " out-of-stock" text="Expected availability: {$model->eta_date_mm_dd_yyyy|date_format:"%d %b %Y"}"}
        {else}
            {include "product/messages/_p_label.tpl" cls=$class ~ " out-of-stock" text="Out of stock"}
        {/if}
    {else}
        {include "product/messages/_p_label.tpl" cls=$class ~ " out-of-stock" text="Out of stock"}

        {if $model->eta_date_mm_dd_yyyy && $model->eta_date_mm_dd_yyyy > time()}
            <div class="eta-date">
                Eta date: {$model->eta_date_mm_dd_yyyy|date_format:"%d %b %Y"}
            </div>
        {/if}
    {/if}
{/if}