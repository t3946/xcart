
<div class="item" data-product="{$item->productid}" itemscope itemtype="http://schema.org/Product">
        <div class="image_container container">
            <a href="{$item->getAbsoluteUrl()}">


                {if $item->isNewProduct()}
                    <span class="splash splash-new show-for-large">New</span>
                {/if}

                {if rand(1,2) > 1}
                    <span class="splash splash-sale show-for-large">Sale</span>
                {/if}

                {set $image = $item->images->limit(1)->get()}
                {set $site = $.getSite}

                {if $image}
                    <img src="//cdn.{$site->getBaseDomain()}{$image->getURL()}" width="{$image->image_x}" height="{$image->image_y}" alt="{$item.product}" class="loader" itemprop="image">
                {else}
                    <div class="not-avail">
                        <span class="text">
                            Image not available
                        </span>
                    </div>
                {/if}

            </a>
            <a href="#" class="button yellow-white button-quick-view hide">quick view</a>
        </div>
        <div class="info_container container">
            <h4 class="title " itemprop="name">
                <a href="{$item->getAbsoluteUrl()}">
                    {if $item.seo_product_name}
                        {raw $item.seo_product_name}
                    {else}
                        {raw $item.product}
                    {/if}
                </a>
            </h4>
            <div class="sku show-for-large">
                <span class="value">
                    SKU: <span class="style" itemprop="sku">{$item.productcode}</span>
                </span>
                <a data-tooltip class="has-tip right " title="What is SKU">?</a>
            </div>

            <div class="brand show-for-small">
                Brand: <span class="value" itemprop="brand">{$item->brand->brand}</span>
            </div>
            {if $item.descr || $item.fulldescr || $item.seo_fulldescr}
                {if $item.descr}
                    {set $description = $item.descr}
                {elseif $item.seo_fulldescr}
                    {set $description = $item.seo_fulldescr}
                {elseif $item.fulldescr}
                    {set $description = $item.fulldescr}
                {/if}

                <div class="description show-for-small-only" rel="noindex">
                    {raw $description|br2nl|strip_tags|truncate:70:'...'|nl2space}
                </div>
                <div class="description show-for-medium" itemprop="description">
                    {raw $description|br2nl|strip_tags|truncate:140:'...'|nl2space}

                    <a href="{$item->getAbsoluteUrl()}" class="show-for-medium">See details</a>
                </div>
            {/if}


            {set $p_list = $item->getParamList()}
            {if $p_list}
                <div class="parameters show-for-medium">
                    <ul class="no-bullet">
                        {foreach $p_list as $param index=$index}
                            <li>
                                {$param.name}: {raw $param.values|join}
                            </li>

                            {if $index >= 3}
                                {break}
                            {/if}
                        {/foreach}

                    </ul>
                </div>
            {/if}


            <div class="price_container hide-for-medium">
                {if $item->list_price > $item->getFrontendPrice()}
                    <span class="old"><span class="price">US$ {$item->list_price}</span></span>
                {/if}
                <span class="current"><span class="price">US$ {$item->getFrontendPrice()|number_format:2}</span></span>
            </div>
        </div>


        <div class="cart_price_container container show-for-medium">
            <div class="price_container">
                {if $item->list_price > $item->getFrontendPrice()}
                    <span class="old">List Price: <span class="price">US$ {$item->list_price}</span></span>
                {/if}
                <span class="current">Price: <span class="price" itemprop="price">US$ {$item->getFrontendPrice()|number_format:2}</span></span>

                <meta itemprop="priceCurrency" content="USD" />
            </div>


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

                {if $item.lead_time_message}
                <div class="lead-time icon info">
                    <i></i> {$item.lead_time_message}
                </div>
                {/if}

                {if $item->mult_order_quantity == 'Y'}
                <div class="multiply-quantity icon info">
                    <i></i> Order in multiples of {$item->min_amount} items
                </div>
                {/if}

                {if $item->min_amount >= $item->avail}
                    <div class="last-items icon info">
                        <i></i> Order at least {$item->avail} items
                    </div>
                {/if}

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