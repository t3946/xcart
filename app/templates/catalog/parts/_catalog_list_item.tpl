{add $brand = $item->cache()->brand}

<div class="item product{if $item->isOutOfStock()} out_of_stock{/if} {if $item->isGroupRoot()} group{/if}"
     data-product="{$item->productid}"
     data-name="{$item->getFrontendName()|escape}"
     data-source="{$analytics_source}"
     {if $brand}
         data-brand="{$brand->brand}"
     {/if}
     {*data-uid="{$item->getUniqueId()}"*}
     data-prices='{$item->getPrices()|json_encode}'
     {if $item->getFrontendPrice() < $item->list_price}
     data-list-price="{$item->list_price}"
     {/if}
     {*data-price-precalc*}
     {*data-cart-action="{url 'cart:quantity:set:post' key=$item->getUniqueId()}"*}
     itemscope
     itemtype="http://schema.org/Product"
     itemprop="itemListElement">

        <div class="image_container container">
            <a href="{$item->getAbsoluteUrl()}" title="{$item.product}" class="link">


                {if $item->isGroupRoot()}
                    {set $childrens = $item->getFrontendChilds()->limit(4)->all()}
                    <div class="images images-many images-{$childrens|count}">
                        {foreach $childrens as $child}
                            {include "catalog/parts/_item_image.tpl" model=$child}
                        {/foreach}
                    </div>
                {else}
                    <div class="images images-1">
                        {include "catalog/parts/_item_image.tpl" model=$item}
                    </div>
                {/if}


                {if $item->isNewProduct()}
                    <span class="splash splash-new show-for-large">New</span>
                {/if}

                {if $item->isSaleSticker()}
                    <span class="splash splash-sale show-for-large">Sale</span>
                {/if}

                {*{if $item->isOutOfStock()}*}
                    {*<span class="splash splash-out">Out of stock</span>*}
                {*{/if}*}

            </a>
            {*<a href="#" class="button yellow-white button-quick-view hide waves">quick view</a>*}
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

            <div class="sku show-for-large">
                <span class="value">
                    SKU: <span class="style" itemprop="sku">{$item.productcode}</span>
                </span>
                {*<a data-tooltip class="has-tip right " title="What is SKU">?</a>*}
            </div>


            {if $brand}
            <div class="brand show-for-small">

                Brand:
                <a class="value" itemprop="brand"  href="{$brand->getAbsoluteUrl()}">
                    {$brand->brand}
                </a>
            </div>
            {/if}

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

                {if !$item->isGroupRoot()}
                <span class="current">
                    <span class="title">Price:</span>
                    <span class="price">
                        <span itemprop="priceCurrency" content="USD">US$</span>
                        <span itemprop="price" var-price>{$item->getFrontendPrice()|number_format:2}</span>

                        {if $item->isOutOfStock()}
                            <link itemprop="availability" href="http://schema.org/OutOfStock" />
                        {else}
                            <link itemprop="availability" href="http://schema.org/InStock" />
                        {/if}
                    </span>
                </span>
                {else}
                    <div>
                        <span class="price-title">Price from:</span>
                        <span itemprop="priceCurrency" content="USD">US$</span>
                        <span itemprop="price">{$item->getPrice(1)|number_format:2}</span>
                    </div>

                    <div>
                        <span class="price-title">Price to:</span>
                        <span itemprop="priceCurrency" content="USD">US$</span>
                        <span itemprop="price">{$item->getPrice(2)|number_format:2}</span>
                    </div>

                {/if}
            </div>

            <div class="overflow_container">
                {if $item->isGroupRoot()}
                    <div class="cart_buttons">
                        <a class="button waves waves-orange yellow-white see-other" href="{$item->getAbsoluteUrl()}">
                            <span class="text">
                                See {$item->getFrontendChilds()->count()} product variation
                            </span>
                        </a>
                    </div>
                {else}

                    {if !$item->isOutOfStock()}
                        <div class="cart_quantity">
                            <label for="quantity-{$item.productid}" class="show-for-large">
                                <span class="show-for-xlarge">Quantity:</span>
                                <span class="show-for-large-only">Qty:</span>
                            </label>

                            {include "product/parts/_quantity_group.tpl" model=$item}
                        </div>

                        <div class="info_container">
                            {include "product/messages/_messages.tpl" model=$item}
                        </div>

                        <div class="cart_add cart_buttons">
                            <a class="add button waves waves-orange yellow waves-effect">
                                <span class="text">
                                    Add to cart
                                </span>
                            </a>
                        </div>

                        <div class="subtotal_container hide" cont-subtotal>
                            <div class="subtotal">
                                Subtotal: US$ <span class="price" var-price-extended>400.01</span>
                            </div>
                            <div class="safe">
                                Save <span class="percentage" var-percent-safe>41</span>% (US$ <span class="price" var-price-perunit-safe>5.27</span> per unit)
                            </div>
                        </div>
                    {else}
                        <div class="out-of-stock">
                            {include "product/messages/_messages.tpl" model=$item}
                        </div>
                    {/if}

                {/if}
            </div>

        </div>
</div>