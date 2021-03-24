{add $brand = $item->cache()->brand}
{add $site = $.getSite}
{add $site_currency = $site->getCurrency()}

<div class="item product{if $item->isOutOfStockFrontend()} out_of_stock{/if} {if $item->isGroupRoot()} group{/if}"
     data-product="{$item->productid}"
     data-name="{$item->getFrontendName()|escape}"
     data-source="{$analytics_source}"
     {if $brand}
         data-brand="{$brand->brand}"
     {/if}
     data-prices='{$item->getPrices()|json_encode}'
     {if $item->getFrontendPrice() < $item->list_price}
     data-list-price="{$item->list_price}"
     {/if}
     {if !$schema_off}
     itemscope
     itemtype="http://schema.org/Product"
     itemprop="itemListElement"
     {/if}>

        <div class="image_container container">
            <a href="{$item->getAbsoluteUrl()}" title="{$item->getFrontendName()|escape}" class="link">


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
                    <meta itemprop="mpn" content="{$item->getMpn()}" />
                    {if $item->upc}
                    <meta itemprop="gtin" content="{$item->upc}" />
                    {/if}
                {/if}

                {if $item->isNewProduct()}
                    <span class="splash splash-new show-for-large">{t 'New'}</span>
                {/if}

                {if $item->isSaleSticker()}
                    <span class="splash splash-sale show-for-large">{t 'Sale'}</span>
                {/if}

            </a>
        </div>

        <div class="info_container container">
            <h4 class="title " itemprop="name">
                {if $item->isGroupRoot()}
                    {set $title = $item->getFrontendName()}
                {else}
                    {set $title = $item->product}
                {/if}

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
                    {t 'SKU'}: <span class="style" itemprop="sku">{$item.productcode}</span>
                </span>
            </div>


            {if $brand}
            <div class="brand show-for-small">

                {t 'Brand'}:
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

                    <a href="{$item->getAbsoluteUrl()}" class="show-for-medium see">{t 'See details'}</a>
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

        </div>

        <div class="cart_price_container container">
            <div class="price_container" {if !$schema_off}itemprop="offers" itemscope itemtype="http://schema.org/Offer"{/if}>
                {if $item->list_price > $item->getFrontendPrice()}
                    <span class="old">
                        <span class="title">{t 'List Price'}:</span>
                        <span class="price">{$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if} {$site_currency->getCurrencyFormat($item->list_price)}</span>&nbsp;{if $site_currency->after}{$site_currency}{/if}
                    </span>
                {/if}

                {if !$item->isGroupRoot()}
                <meta itemprop="url" content="{$item->getAbsoluteUrl(true)}" />
                <meta itemprop="priceValidUntil" content="{$item->getPriceValidUntil()->format('Y-m-d')}" />
                <span class="current">
                    <span class="title">{t 'Price'}:</span>
                    <span class="price">
                        <span {if !$schema_off}itemprop="priceCurrency" content="{$site_currency->currency_code}"{/if}>{$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}</span>
                        <span {if !$schema_off}itemprop="price" content="{$item->getFrontendPrice()}"{/if} var-price>{$site_currency->getCurrencyFormat($item->getFrontendPrice())}</span>{if $site_currency->after}&nbsp;{$site_currency}{/if}
                        {if !$schema_off}
                            {if !$item->isOutOfStockFrontend() && $item->isOutOfStock()}
                                <link itemprop="availability" href="http://schema.org/OutOfStock" />
                            {elseif $item->isOutOfStockFrontend()}
                                <link itemprop="availability" href="http://schema.org/OutOfStock" />
                            {else}
                                <link itemprop="availability" href="http://schema.org/InStock" />
                            {/if}
                        {/if}
                    </span>
                </span>
                {else}
                    {if $item->getFrontendPrice() != $item->getFrontendPrice(2)}
                        <meta itemprop="priceValidUntil" content="{$item->getPriceValidUntil()->format('Y-m-d')}" />
                    <div>
                        <span class="price-title">{t 'Price from'}:</span>
                        <span {if !$schema_off}itemprop="priceCurrency"{/if} content="{$site_currency->currency_code}">{$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}</span>
                        <span {if !$schema_off}itemprop="price" content="{$item->getFrontendPrice(1)}"{/if}>{$site_currency->getCurrencyFormat($item->getFrontendPrice(1))}</span>{if $site_currency->after}{$site_currency}{/if}
                    </div>

                    <div>
                        <span class="price-title">{t 'Price to'}:</span>
                        <span {if !$schema_off}itemprop="priceCurrency"{/if} content="{$site_currency->currency_code}">{$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}</span>
                        <span {if !$schema_off}itemprop="price" content="{$item->getFrontendPrice(2)}"{/if}>{$site_currency->getCurrencyFormat($item->getFrontendPrice(2))}</span>{if $site_currency->after}{$site_currency}{/if}
                    </div>
                    {else}
                        <span class="current">
                            <span class="title">{t 'Price'}:</span>
                            <span class="price">
                                <span {if !$schema_off}itemprop="priceCurrency"{/if} content="{$site_currency->currency_code}">{$site_currency->symbol_prefix}{if !$site_currency->after}{$site_currency}{/if}</span>
                                <span {if !$schema_off}itemprop="price" content="{$item->getFrontendPrice(1)}"{/if}>{$site_currency->getCurrencyFormat($item->getFrontendPrice(1))}</span>{if $site_currency->after}{$site_currency}{/if}
                            </span>
                        </span>
                    {/if}
                {/if}
            </div>

            <div class="overflow_container">
                {if $item->isGroupRoot()}
                    <div class="cart_buttons">
                        <a class="button waves waves-orange yellow-white see-other" href="{$item->getAbsoluteUrl()}">
                            <span class="text">
                                {set $pv = $item->getFrontendChilds()->count()}
                                {t 'See %count% product variation' 'See %count% products variation' $pv}
                            </span>
                        </a>
                    </div>
                {else}

                    {if !$item->isOutOfStockFrontend()}
                        <div class="cart_quantity">
                            <label for="quantity-{$item.productid}" class="show-for-large">
                                <span class="show-for-large">{t 'Quantity'}:</span>
                            </label>

                            {include "product/parts/_quantity_group.tpl" model=$item}
                        </div>

                        <div class="info_container">
                            {include "product/messages/_messages.tpl" model=$item}
                        </div>

                        <div class="cart_add cart_buttons">
                            {include "product/parts/_add_to_cart.tpl"}
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