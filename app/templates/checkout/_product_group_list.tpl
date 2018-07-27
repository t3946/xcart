<div class="product-group-container">
    <div class="table-product-group">
        {foreach $items as $key=>$position}
            <div class="table-row">
                <div class="table-cell image">
                    {*{include 'catalog/parts/_item_image.tpl' model=$position->object}*}
                </div>
                <div class="table-cell quantity">
                    {$position->quantity}
                </div>
                <div class="table-cell title">
                    {$position->object}
                </div>
            </div>
        {/foreach}
    </div>
</div>
