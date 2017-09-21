<div class="tab-shipping">

    {add $warehouse = $model->distributor}

    <div class="row">
        <div class="columns small-12 large-4 block">
            <h2 class="title">Shipping specs</h2>
            <div class="options">
                <div class="content">
                    {include 'product/tabs/__option.tpl'
                        title='Weight'
                        value="{$model->weight} Lbs"
                    }

                    {include  'product/tabs/__option.tpl'
                        title='Dimensions'
                        value="{$model->dim_x}\" x {$model->dim_y}\" x {$model->dim_z}\" "
                    }

                    {if $model->shipping_weight > 0}
                        {include 'product/tabs/__option.tpl'
                            title='Shipping weight'
                            value="{$model->shipping_weight} Lbs"
                        }
                    {/if}

                    {if $model->shipping_dim_x}
                        {include  'product/tabs/__option.tpl'
                            title='Shipping dimensions'
                            value="{$model->shipping_dim_x}\" x {$model->shipping_dim_y}\" x {$model->shipping_dim_z}\" "
                        }
                    {/if}

                </div>
            </div>
        </div>
        <div class="columns small-12 large-8 block">
            <h2 class="title">Shipping from</h2>
            <div class="content">
                This product is shipped from our warehouse in
                {$warehouse->m_city},
                {$warehouse->m_state},
                {$warehouse->m_country}.
            </div>

            {if $tab}
                <div class="raw-content">
                    <div class="row">
                        <div class="columns small-12">
                            {raw $tab.content}
                        </div>
                    </div>
                </div>
            {/if}
        </div>
    </div>


</div>