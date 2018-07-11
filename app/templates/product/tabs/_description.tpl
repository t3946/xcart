<div class="row">
    <div class="column small-12 large-4 block">

        <div class="options">
            <div class="h2 title">Options</div>
            <div class="content">
{*
                {include 'product/tabs/__option.tpl'
                    title='Production'
                    value=$model->distributor->manufacturer
                }
*}

                {set $brand = $model->brand}
                {if $brand}
                    {include 'product/tabs/__option.tpl'
                        title='Brand'
                        value="<a href='"~ $brand->getAbsoluteUrl() ~"'>" ~ $brand->brand ~ "</a>"
                    }
                {/if}

                {foreach $model->getParamList() as $item}
                    {include 'product/tabs/__option.tpl'
                        title=$item.name
                        value=$item.values|implode:', '
                    }
                {/foreach}

                {if $model->upc}
                    {include 'product/tabs/__option.tpl'
                        title="Barcode"
                        value=$model->upc
                    }
                {/if}

            </div>
        </div>

    </div>
    <div class="column small-12 large-8 block">

        <div class="description">
            <div class="h2 title">Description</div>
            <div class="content">
                {raw $model->getFrontendDescription()}
            </div>
        </div>

    </div>
</div>