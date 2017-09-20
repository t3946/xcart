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