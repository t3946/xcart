{set $fill_class = ($fill! && $fill) ? 'fill' : '' }
{set $class = ($class ? $class : '') ~~ $fill_class}
{set $dx = $model->distributor}
{set $brand = $model->brand}
{if !$model->isGroupRoot()}
    {if !$model->isOutOfStockFrontend()}
        {if $model->isFreeShipping()}
            {set $lbl}{t 'Free Shipping within contiguous U.S.'}{/set}
            {include "product/messages/_p_label.tpl" containerClass=$class type="free-shipping"  text=$lbl}
        {/if}
        {if $model->isFlatRate()}
            {set $lbl}{t '$8.99 flat rate shipping within contiguous U.S.'}{/set}
            {include "product/messages/_p_label.tpl" containerClass=$class type="flat-shipping" text=$lbl}
        {/if}

        {if $model->lead_time_message|trim}
            {include "product/messages/_p_label.tpl" containerClass=$class type="lead-time" text=$model->lead_time_message}
        {elseif $brand->leadtime_from}
            {if $brand->leadtime_from === $brand->leadtime_to || !$brand->leadtime_to}
                {set $lbl}{t 'Lead time for this product is %count% business day' 'Lead time for this product is %count% business days' $brand->leadtime_from}{/set}
                {include "product/messages/_p_label.tpl" containerClass=$class ~~ "product-label-icon__lead-time" text=$lbl}
            {else}
                {set $lbl1}{t 'Lead time for this product is'}{/set}
                {set $lbl2}{t 'business days'}{/set}
                {include "product/messages/_p_label.tpl" containerClass=$class type="lead-time" text=$lbl1 ~~ $brand->leadtime_from~"-"~$brand->leadtime_to ~~ $lbl2}
            {/if}
        {elseif $dx->dx_leadtime}
            {if $dx->dx_leadtime === $dx->dx_leadtime_to || !$dx->dx_leadtime_to}
                {set $lbl}{t 'Lead time for this product is %count% business day' 'Lead time for this product is %count% business days' $dx->dx_leadtime}{/set}
                {include "product/messages/_p_label.tpl" containerClass=$class type="lead-time" text=$lbl}
            {else}
                {set $lbl1}{t 'Lead time for this product is'}{/set}
                {set $lbl2}{t 'business days'}{/set}
                {include "product/messages/_p_label.tpl" containerClass=$class type="lead-time" text=$lbl1 ~~ $dx->dx_leadtime~"-"~$dx->dx_leadtime_to ~~ $lbl2}
            {/if}
        {/if}

        {if $model->min_amount > 1}
            {if $model->mult_order_quantity == 'Y'}
                {set $lbl}{t 'Order in multiples of %count% item' 'Order in multiples of %count% items' $model->min_amount}{/set}
                {include "product/messages/_p_label.tpl" containerClass=$class type="multiply-quantity" text=$lbl}
            {else}
                {set $lbl}{t 'Order at least %count% item' 'Order at least %count% items' $model->min_amount}{/set}
                {include "product/messages/_p_label.tpl" containerClass=$class type="last-items" text=$lbl}
            {/if}
        {/if}
        {if $model->eta_date_mm_dd_yyyy && $model->eta_date_mm_dd_yyyy > time()}
            {if $dx->dx_eta_date}
                {set $lbl}{t 'Warehouse is closed until'}{/set}
            {else}
                {set $lbl}{t 'Expected availability'}:{/set}
            {/if}
            {include "product/messages/_p_label.tpl" containerClass=$class type="out-of-stock" text=$lbl ~ " {$model->getFrontendEtaDate()}"}
        {/if}
    {else}
        {if $fill! && $fill}
            {if $model->eta_date_mm_dd_yyyy && $model->eta_date_mm_dd_yyyy > time()}
                {set $lbl}{t 'Expected availability'}{/set}
                {include "product/messages/_p_label.tpl" containerClass=$class type="out-of-stock" text=$lbl ~ ": {$model->getFrontendEtaDate()}"}
            {else}
                {set $lbl}{t 'Out of stock'}{/set}
                {include "product/messages/_p_label.tpl" containerClass=$class type="out-of-stock" text=$lbl}
            {/if}
        {else}
            {set $lbl}{t 'Out of stock'}{/set}
            {include "product/messages/_p_label.tpl" containerClass=$class type="out-of-stock" text=$lbl}

            {if $model->eta_date_mm_dd_yyyy && $model->eta_date_mm_dd_yyyy > time()}
                <div class="eta-date">
                    {set $lbl}{t 'Eta date'}{/set}
                    {$lbl}: {$model->getFrontendEtaDate()}
                </div>
            {/if}
        {/if}
    {/if}
    {if $model->manufacturerid == 85}
        {set $lbl}{t 'All sales are final. No returns or exchanges are allowed.'}{/set}
        {include "product/messages/_p_label.tpl" containerClass=$class type="out-of-stock" text=$lbl}
    {/if}
{/if}