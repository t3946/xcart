<div class="tab-shipping">

    {add $warehouse = $model->distributor}
    {add $is_specs = ($model->weight > 0 || $model->shipping_weight > 0 || $model->dim_x || $model->shipping_dim_x)}
    {add $size = $site->dimension_size->value}
    {add $weight_label = $site->dimension_weight->value}

    <div class="row">
        {if $is_specs}
        <div class="columns small-12 large-4 block">
            <div class="h2 title">{t 'Shipping specs'}</div>
            <div class="options">
                <div class="content">
                    {if $model->weight > 0}
                        {set $lbl}{t 'Weight'}{/set}
                        {include 'product/tabs/__option.tpl' title=$lbl value="{$model->weight} {$weight_label}"}
                    {/if}

                    {if $model->dim_x > 0 || $model->dim_y > 0 || $model->dim_z > 0}
                        {set $lbl}{t 'Dimensions'}{/set}
                    {include  'product/tabs/__option.tpl'
                        title=$lbl
                        value="{$model->dim_x}{$size} x {$model->dim_y}{$size} x {$model->dim_z}{$size} "
                    }
                    {/if}

                    {if $model->shipping_weight > 0}
                        {set $lbl}{t 'Shipping weight'}{/set}
                        {include 'product/tabs/__option.tpl'
                            title=$lbl
                            value="{$model->shipping_weight} {$weight_label}"
                        }
                    {/if}

                    {if $model->shipping_dim_x || $model->shipping_dim_y || $model->shipping_dim_z}
                        {set $lbl}{t 'Shipping dimensions'}{/set}
                        {include  'product/tabs/__option.tpl'
                            title=$lbl
                            value="{$model->shipping_dim_x}{$size} x {$model->shipping_dim_y}{$size} x {$model->shipping_dim_z}{$size} "
                        }
                    {/if}

                </div>
            </div>
        </div>
        {/if}
        <div class="columns small-12 {if $is_specs}large-8{else}large-12{/if} block">
            <div class="h2 title">{t 'Shipping from'}</div>
            <div class="content">
                <div class="row">
                    <div class="columns small-12">
                        {t 'This product is shipped from our warehouse in'} {$warehouse->m_city}, {if $config.show_full_state_country === 'Y'}{$warehouse->state_model}{else}{$warehouse->m_state}{/if}, {$warehouse->country_model}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>