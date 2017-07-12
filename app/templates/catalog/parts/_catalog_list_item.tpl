<div class="item product{if $item->isOutOfStock()} out_of_stock{/if}" data-product="{$item->productid}" itemscope itemtype="http://schema.org/Product" itemprop="itemListElement">

        <div class="image_container container">
            <a href="{$item->getAbsoluteUrl()}" title="{$item.product}" class="link">


                {set $image = $item->images->limit(1)->get()}
                {if $image!}
                    {if $.isBot}
                        <img src="//cdn.{$site->getBaseDomain()}{$image->getURL()}" width="{$image->image_x}" height="{$image->image_y}" alt="{$item.product}" itemscope itemprop="image">
                    {else}
                        <img data-original="//cdn.{$site->getBaseDomain()}{$image->getURL()}" width="{$image->image_x}" height="{$image->image_y}" alt="{$item.product}" class="lazy lazy-img" itemprop="image">
                    {/if}
                {else}
                    
                    {*<img src="http://via.placeholder.com/200x200/efefef/a6a6a6/?text=No+image" alt="Image not available">*}
                    <div class="not-avail">
                        <span class="text">
                            Image not available
                        </span>
                    </div>
                {/if}

                {if $item->isNewProduct()}
                    <span class="splash splash-new show-for-large">New</span>
                {/if}

                {if $item->isSaleSticker()}
                    <span class="splash splash-sale show-for-large">Sale</span>
                {/if}

            </a>
            <a href="#" class="button yellow-white button-quick-view hide waves">quick view</a>
        </div>
        <div class="info_container container">
            <h4 class="title " itemprop="name">
                {set $title = $item->getFrontendName()}

                <a href="{$item->getAbsoluteUrl()}" title="{$title}">
                    {if $q!}
                        {raw $title|words_highlight:$q:"span.highlight"}
                    {else}
                        {raw $title}
                    {/if}
                </a>
            </h4>
            {*<div class="sku show-for-large">*}
            <div class="sku show-for-large">
                <span class="value">
                    SKU: <span class="style" itemprop="sku">{$item.productcode}</span>
                </span>
                {*<a data-tooltip class="has-tip right " title="What is SKU">?</a>*}
            </div>

            <div class="brand show-for-small">
                {if $brand_page!}
                    {set $brand = $brand_page}
                {else}
                    {set $brand = $item->cache()->brand}
                {/if}

                Brand:
                <a class="value" itemprop="brand"  href="{$brand->getAbsoluteUrl()}">
                    {$brand->brand}
                </a>
            </div>
            {if $item->getFrontendDescription()}
                {set $description = $item->getFrontendDescription()}

                <div class="description show-for-medium" >
                    <span itemprop="description">
                        {set $description = $description|br2nl|strip_tags|truncate:140:'...'|nl2space}

                        {if $q!}
                            {raw $description|words_highlight:$q:"span.highlight"}
                        {else}
                            {raw $description}
                        {/if}
                    </span>

                    <a href="{$item->getAbsoluteUrl()}" class="show-for-medium see">See details</a>
                </div>

                <noindex>
                    <div class="description show-for-small hide-for-medium">
                        {set $description = $description|br2nl|strip_tags|truncate:70:'...'|nl2space}

                        {if $q!}
                            {raw $description|words_highlight:$q:"span.highlight"}
                        {else}
                            {raw $description}
                        {/if}
                    </div>
                </noindex>
            {/if}


            {*{set $p_list = $item->getParamList()}*}
            {*{if $p_list}*}
                {*<div class="parameters show-for-medium">*}
                    {*<ul class="no-bullet">*}
                        {*{foreach $p_list as $param index=$index}*}
                            {*<li>*}
                                {*{$param.name}: {raw $param.values|join}*}
                            {*</li>*}

                            {*{if $index >= 3}*}
                                {*{break}*}
                            {*{/if}*}
                        {*{/foreach}*}

                    {*</ul>*}
                {*</div>*}
            {*{/if}*}

        </div>


        <div class="cart_price_container container">
            <div class="price_container" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                {if $item->list_price > $item->getFrontendPrice()}
                    <span class="old">
                        <span class="title">List Price:</span>
                        <span class="price">US$ {$item->list_price}</span>
                    </span>
                {/if}
                <span class="current">
                    <span class="title">Price:</span>
                    <span class="price">
                        <span itemprop="priceCurrency" content="USD">US$</span>
                        <span itemprop="price">{$item->getFrontendPrice()|number_format:2}</span>
                        {if $item->isOutOfStock()}
                            <link itemprop="availability" href="http://schema.org/OutOfStock" />
                        {else}
                            <link itemprop="availability" href="http://schema.org/InStock" />
                        {/if}

                    </span>
                </span>
            </div>

            <div class="overflow_container">
                {if !$item->isOutOfStock()}
                    <div class="cart_quantity">
                        <label for="quantity-{$item.productid}" class="show-for-large">
                            <span class="show-for-xlarge">Quantity:</span>
                            <span class="show-for-large-only">Qty:</span>
                        </label>

                        <div class="quantity-group">
                            <span class="btn dec">-</span>
                            <input type="number"
                                   name="quantity"
                                   min="{$item->min_amount}"
                                   max="{$item->avail}"
                                   step="{if $item->mult_order_quantity == 'Y'}{$item->min_amount}{else}1{/if}"
                                   value="{$item->min_amount}"
                                   id="quantity-{$item.productid}"
                            />
                            <span class="btn inc active">+</span>
                        </div>
                    </div>

                    <div class="info_container">
                        {if $item.lead_time_message|trim}
                            <div class="lead-time icon info">
                                {$item.lead_time_message}
                            </div>
                        {/if}

                        {if $item->mult_order_quantity == 'Y'}
                            <div class="multiply-quantity icon info padding">
                                Order in multiples of {$item->min_amount} items
                            </div>
                        {/if}

                        {if $item->min_amount >= $item->avail}
                            <div class="last-items icon info">
                                Order at least {$item->avail} items
                            </div>
                        {/if}
                    </div>

                    <div class="cart_add">
                        <span class="add button waves waves-orange yellow" data-url="{url 'catalog:cart:add' key=$item->getUniqueId()}">
                            <span class="text">
                                Add to cart
                            </span>
                        </span>
                    </div>

                    <div class="subtotal_container">
                        <div class="subtotal">
                            Subtotal: US$ 400.01
                        </div>
                        <div class="safe">
                            Save 41% (US$ 5.27 per unit)
                        </div>
                    </div>
                {else}
                    <div class="out-of-stock">
                        <div class="title icon">
                            <i></i> Out of stock
                        </div>

                        {if $item.eta_date_mm_dd_yyyy && $item.eta_date_mm_dd_yyyy > time()}
                            <div class="eta-date">
                            Eta date: {$item.eta_date_mm_dd_yyyy|date_format:"%d %b %Y"}
                        </div>
                        {/if}
                        <div class="notify">

                        </div>
                    </div>
                {/if}
            </div>

        </div>
</div>