<div class="row">
    <div class="column small-12 large-4 block">

        <div class="options">
            <h2 class="title">Options</h2>
            <div class="content">

                {*<div class="option">*}
                    {*<div class="title">Production</div>*}
                    {*<div class="value">*}
                        {*<div class="multiline">*}
                            {*{$model->distributor->manufacturer}*}
                        {*</div>*}
                    {*</div>*}
                {*</div>*}

                <div class="option">
                    <div class="title">Brand</div>
                    <div class="value">
                        <div class="multiline">
                            {$model->brand->brand}
                        </div>
                    </div>
                </div>

                {foreach $model->getParamList() as $item}
                    <div class="option">
                    <div class="title">{$item.name}</div>
                    <div class="value">
                        <div class="multiline">
                            {$item.values|implode:', '}
                        </div>
                    </div>
                </div>
                {/foreach}

                {if $model->upc}
                    <div class="option">
                    <div class="title">Barcode</div>
                    <div class="value">
                        <div class="multiline">
                            UPC:
                            {$model->upc}
                        </div>
                    </div>
                </div>
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