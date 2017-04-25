
<div class="item" data-product="{$item->productid}">
        <div class="image_container container">
            <a href="{$item->getAbsoluteUrl()}">
                {*{if rand(1,2) > 1}*}
                    {*<span class="item__rect-sale_yellow hidden-xs hidden-sm hidden-md">Sale</span>*}
                {*{/if}*}

                {*{if rand(1,2) > 1}*}
                    {*<span class="item__circle-new_red hidden-xs hidden-sm hidden-md">New</span>*}
                {*{/if}*}
                {set $image = $item->images->limit(1)->get()}
                {set $site = $.getSite}

                {if $image}
                    <img src="//cdn.{$site->getBaseDomain()}{$image->getURL()}" alt="{$item.product}" >
                {else}
                    Not avail
                {/if}

                {*{if rand(1,2) > 1}*}
                    {*<img src="/static/frontend/demo_images/category/1280/029-alv-esp12-1.png" alt="{$item.product}" />*}
                {*{else}*}
                    {*<img src="/static/frontend/demo_images/category/1280/alv-1334d-1.png" alt="{$item.product}" />*}
                {*{/if}*}
            </a>
            <a href="#" class="button yellow-white button-quick-view hide">quick view</a>
        </div>
        <div class="info_container container">
            <h4 class="title">
                <a href="{$item->getAbsoluteUrl()}">
                    {$item.product}
                </a>
            </h4>
            <div class="sku show-for-large">
                <span class="value">
                    SKU: {$item.productcode}
                </span>
                <a data-tooltip class="has-tip right " title="What is SKU">?</a>
            </div>

            {if $item.descr || $item.fulldescr || $item.seo_fulldescr}
                <div class="description show-for-medium">
                    {if $item.descr}
                        {set $description = $item.descr}
                    {elseif $item.fulldescr}
                        {set $description = $item.fulldescr}
                    {elseif $item.seo_fulldescr}
                        {set $description = $item.seo_fulldescr}
                    {/if}

                    {raw $description|br2nl|strip_tags|truncate:160:'...'|nl2space}

                    <a href="{$item->getAbsoluteUrl()}" class="show-for-medium">See details</a>
                </div>
            {/if}



            {*<div class="parameters show-for-medium">*}
                {*<ul class="no-bullet">*}
                    {*<li>Pain type: Watercolor</li>*}
                    {*<li>Hair: Synthetic</li>*}
                    {*<li>Form: Angular</li>*}

                    {*{if rand(1,2) > 1}*}
                        {*<li>Sizes: 4, 8, 12, 16, 20, 32</li>*}
                    {*{else}*}
                        {*<li>Colors:*}
                            {*<i class="color-box color-box_light-green"></i>*}
                            {*<i class="color-box color-box_blue"></i>*}
                            {*<i class="color-box color-box_purple"></i>*}
                            {*<i class="color-box color-box_orange"></i>*}
                            {*<i class="color-box color-box_red"></i>*}
                            {*<i class="color-box color-box_white"></i>*}
                            {*<i class="color-box color-box_grey"></i>*}
                            {*<a href="#" class="show-all-link">Show all</a>*}
                        {*</li>*}
                    {*{/if}*}
                {*</ul>*}
            {*</div>*}


            <div class="price hide-for-large">
                {*<span class="old">US$ {$item->getPrice()}</span>*}
                <span class="current">US$ {$item->getPrice()}</span>
            </div>
        </div>


        <div class="cart_price_container container">
            <div class="price_container">
                <span class="old">List Price: <span class="price">US$ {$item->getPrice()}</span></span>
                <span class="current">Price: <span class="price">US$ {$item->getPrice()}</span></span>
            </div>
            <div class="cart_quantity">
                <label for="quantity-{$item.productid}" class="show-for-large">
                    <span class="show-for-xlarge">Quantity:</span>
                    <span class="show-for-large-only">Qty:</span>
                </label>

                <div class="quantity-group">
                    <span class="btn dec">-</span>
                    <input type="number" name="quantity" min="1" max="9999" value="1" id="quantity-{$item.productid}" />
                    <span class="btn inc active">+</span>
                </div>
            </div>

            <div class="cart_add">
                <span class="add button yellow">Add to cart</span>
            </div>
            {*<div class="subtotal_container">*}
                {*<div class="subtotal">*}
                    {*Subtotal: US$ 400.01*}
                {*</div>*}
                {*<div class="safe">*}
                    {*Save 41% (US$ 5.27 per unit)*}
                {*</div>*}
            {*</div>*}


        </div>
</div>