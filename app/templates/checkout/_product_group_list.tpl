<div class="product-group-container">
    <div class="table-product-group">
        {foreach $items as $key=>$position}
            <div class="table-row">
                <div class="table-cell image">
                    {include 'catalog/parts/_item_image.tpl' model=$position->object}
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
    <a class="table-product-group-close-line">
        <span class="label-hide-items">
            <span class="up-icon-text">Hide items</span>
            <span class="up-icon">
                <svg class="triangle-up" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                    <path class="triangle-up-path" d="M4 24 H28 L16 6 z"/>
                </svg>
            </span>
        </span>
    </a>
</div>
