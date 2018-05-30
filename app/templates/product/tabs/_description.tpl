<div class="row">
    <div class="column small-12 large-4 block">

        <div class="options">
            <h2 class="title">Options</h2>
            <div class="content">
{*
                {include 'product/tabs/__option.tpl'
                    title='Production'
                    value=$model->distributor->manufacturer
                }
*}

                {set $brand = $model->brand}
                {include 'product/tabs/__option.tpl'
                    title='Brand'
                    value="<a href='"~ $brand->getAbsoluteUrl() ~"'>" ~ $brand->brand ~ "</a>"
                }

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
            <h2 class="title">Description</h2>
            <div class="content">
                {raw $model->getFrontendDescription()}
            </div>
        </div>

    </div>
</div>