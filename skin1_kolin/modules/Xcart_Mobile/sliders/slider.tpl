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
                            <a href="{$current_location}/product.php?productid={$item->productid}" class="ui-link-inherit">
                                <span class="product-thumbnail row" style="width:150px;">
                                    {assign var='ImageTModel' value=$item->getThumbnail()}
                                    {assign var='tmbn_url' value=''}
                                    {if $ImageTModel}
                                        {assign var='tmbn_url' value=$ImageTModel->getURL()}
                                    {/if}
                                    {assign var='productid' value=$item->productid}
                                    {assign var='product' value=$item->getTitle()}
                                    {assign var='splash' value=$item->getSplash()}
                                    {if $config.Appearance.show_thumbnails eq "Y"}
                                        {include file="product_splash.tpl"}
                                        {if !$item->isGroupRoot()}
                                            <img data-lazy="{include file="product_image_src.tpl"}" {if $image_x ne 0} width="{$image_x}"{/if}{if $image_y ne 0} height="{$image_y}"{/if} alt="{$product|escape}"/>
                                        {else}
                                            {if $item->getThumbnails()}
                                                {foreach from=$item->getThumbnails() item=thumb name=group}
                                                    {if $smarty.foreach.group.index < 4}
                                                        <img data-lazy="{include file="product_image_src.tpl" tmbn_url=$thumb->getUrl()}" style="width:50%; height: 50%; float:left; padding:0;" alt="{$item->getTitle()|escape}" />
                                                    {/if}
                                                {/foreach}
                                            {/if}
                                        {/if}
                                    {/if}
                                </span>

                                <span class="label row">
                                    {$item->getTitle()}
                                    <span class="grad">&nbsp;</span>
                                </span>
                                {if !$item->isGroupRoot()}
                                <span class="price row">
                                    Price: US$ {$item->getFrontendPrice()}
                                </span>
                                {/if}
                            </a>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
</noindex>
{/if}

