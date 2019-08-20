{set $class = ($fill! && $fill) ? 'fill' : '' }
{set $dx = $model->distributor}
{if !$model->isGroupRoot()}
    {if !$model->isOutOfStock()}
        {if $model->isFreeShipping()}
            {set $lbl}{t 'Free Shipping within contiguous U.S.'}{/set}
            {include "product/messages/_p_label.tpl" cls=$class ~~ "free-shipping" text=$lbl}
        {/if}
        {if $model->lead_time_message|trim}
            {include "product/messages/_p_label.tpl" cls=$class ~~ "lead-time" text=$model->lead_time_message}
        {elseif $dx->dx_leadtime}
            {if $dx->dx_leadtime === $dx->dx_leadtime_to || !$dx->dx_leadtime_to}
                {set $lbl}{t 'Lead time for this product is %count% business day' 'Lead time for this product is %count% business days' $dx->dx_leadtime}{/set}
                {include "product/messages/_p_label.tpl" cls=$class ~~ "lead-time" text=$lbl}
            {else}
                {set $lbl1}{t 'Lead time for this product is'}{/set}
                {set $lbl2}{t 'business days'}{/set}
                {include "product/messages/_p_label.tpl" cls=$class ~~ "lead-time" text=$lbl1 ~~ $dx->dx_leadtime~"-"~$dx->dx_leadtime_to ~~ $lbl2}
            {/if}
        {/if}
        {if $model->min_amount > 1}
            {if $model->mult_order_quantity == 'Y'}
                {set $lbl}{t 'Order in multiples of %count% item' 'Order in multiples of %count% items' $model->min_amount}{/set}
                {include "product/messages/_p_label.tpl" cls=$class ~~ "multiply-quantity" text=$lbl}
            {else}
                {set $lbl}{t 'Order at least %count% item' 'Order at least %count% items' $model->min_amount}{/set}
                {include "product/messages/_p_label.tpl" cls=$class ~~ "last-items" text=$lbl}
            {/if}
        {/if}
    {else}
        {if $fill! && $fill}
            {if $model->eta_date_mm_dd_yyyy && $model->eta_date_mm_dd_yyyy > time()}
                {set $lbl}{t 'Expected availability'}{/set}
                {include "product/messages/_p_label.tpl" cls=$class ~~ "out-of-stock" text=$lbl ~ ": {$model->eta_date_mm_dd_yyyy|date_format:"%d %b %Y"}"}
            {else}
                {set $lbl}{t 'Out of stock'}{/set}
                {include "product/messages/_p_label.tpl" cls=$class ~~ "out-of-stock" text=$lbl}
            {/if}
        {else}
            {set $lbl}{t 'Out of stock'}{/set}
            {include "product/messages/_p_label.tpl" cls=$class ~~ "out-of-stock" text=$lbl}

            {if $model->eta_date_mm_dd_yyyy && $model->eta_date_mm_dd_yyyy > time()}
                <div class="eta-date">
                    {set $lbl}{t 'Eta date'}{/set}
                    {$lbl}: {$model->eta_date_mm_dd_yyyy|date_format:"%d %b %Y"}
                </div>
            {/if}
        {/if}
    {/if}
{/if}