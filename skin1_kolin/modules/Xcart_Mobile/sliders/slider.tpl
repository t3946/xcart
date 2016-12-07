{getSliderData mode=$mode productid=$productid assign="slider"}

{if $slider|@count gt 0}
    <div class="slider-products {$mode}">
        {foreach item=item from=$slider}
            <div class="product">
                <div data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div"
                data-icon="arrow-r" data-iconpos="right" data-theme="c"
                class="ui-btn ui-btn-up-c ui-btn-icon-right ui-li-has-arrow ui-li">

                <a href="{$current_location}/product.php?productid={$item.productid}" class="ui-link-inherit">
                    <span class="product-thumbnail">
                        {include file="product_thumbnail.tpl" productid=$item.productid product=$item.product tmbn_url=$item.tmbn_url}

                        <span class="labels">

                        </span>
                    </span>
                </a>
                </div>
            </div>
        {/foreach}
    </div>
{/if}

