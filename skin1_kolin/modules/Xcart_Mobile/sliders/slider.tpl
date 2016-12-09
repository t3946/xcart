{getSliderData mode=$mode productid=$productid assign="slider"}

{if $slider|@count gt 0}
<noindex>
    <div class="slider-container">
        <div class="ui-content">
            <div class="slider-head ui-btn ui-btn-up-c ui-fullsize ui-btn-icon-left " data-class="{$mode}">
            <span class="ui-btn-inner ui-corner-top ui-corner-bottom">
                <span class="ui-btn-text">
                    {if $title}
                        {$title}
                    {else}
                        Hello
                    {/if}
                </span>
                <span class="ui-icon ui-icon-plus ui-icon-shadow">&nbsp;</span>
            </span>
            </div>
        </div>
        <div class="slider-products ui-listview {$mode}">
            {foreach item=item from=$slider}
                <div class="slide">
                    <div class="product">
                        <div class="ui-shadow">
                            <a href="{$current_location}/product.php?productid={$item.productid}" class="ui-link-inherit">
                                <span class="product-thumbnail row">
                                    {include file="product_thumbnail.tpl" productid=$item.productid product=$item.product tmbn_url=$item.tmbn_url}
                                </span>

                                <span class="label row">
                                    {$item.product}
                                    <span class="grad">&nbsp;</span>
                                </span>

                                <span class="price row">
                                    Price: US$ {$item.price}
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
</noindex>
{/if}

