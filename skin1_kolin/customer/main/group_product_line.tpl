<div id="group_product_line">
    {assign var=count value=$oProduct->childs->count()}
    <br/>
    <table style="margin-top: -10px;" width="100%" cellspacing="0">
        <tr>
            <td style="background-color: #FEF6F3;" class="DialogTitle valign-top">
                <b>
                    Product line ({$count} item{if $count > 1}s{/if})
                </b>
            </td>
        </tr>
    </table>
    <br/>
    <table width="100%" cellspacing="0" cellpadding="3" class="group_product">
        <tr>
            <th>SKU</th>
            <th>Thumbnail</th>
            <th>Title</th>
            <th>List price</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Extended</th>
        </tr>
        {foreach from=$oProduct->childs->all() item=child}
            {assign var=amc value=$child->category_main->limit(1)}
            {assign var=main_cat value=$amc->get()}
            <tr class="row" data-product-id="{$child->productid}" data-brand="{$child->brand->brand|escape}"
                data-title="{$child->product|escape}" data-category="{$main_cat->category->category|escape}" data-sfid="{$current_storefront}"
                data-price='{getPricingArray pricing=$child->pricing json=true}'>
                <td class="sku"><a href="{$child->getUrl()}" target="_blank">{$child->productcode}</a></td>
                {assign var=thumbnail_m value=$child->thumbnail}
                {assign var=thumbnail value=$thumbnail_m->get()}
                <td><img src="{include file="product_image_src.tpl" tmbn_url=$thumbnail->getUrl()}"/></td>
                <td class="title">
                    <div>{$child->product}</div>
                    {if $child->isProductOutOfStock()}
                        <div class="info clock">
                            <i class="icon"></i>
                            <span class="title">Out of stock</span>
                            {if $child->eta_date_mm_dd_yyyy}
                                <span class="subline">ETA date: {$child->eta_date_mm_dd_yyyy|date_format:'%d %b %Y'}</span>
                            {/if}
                        </div>
                    {/if}
                </td>
                <td class="strike">{if floatval($child->list_price) > 0}{include file="currency.tpl" value=$child->list_price}{/if}</td>
                <td>{include file="currency.tpl" value=$child->getFrontendPrice()}</td>
                <td class="spinner_cell">
                    {if !$child->isProductOutOfStock()}
                        {if $child->min_amount > 1 && $child->mult_order_quantity == 'Y'}
                            {assign var=step value=$child->min_amount}
                        {else}
                            {assign var=step value=1}
                        {/if}
                        {include file="customer/main/add_to_cart_input.tpl" min=$child->min_amount max=$child->avail step=$step}
                        {if $child->min_amount > 1}
                            {if $child->mult_order_quantity == 'Y'}
                                <div class="info mult">
                                    <i class="icon"></i>
                                    <span class="subline">Order multiples <br>of {$child->min_amount} items</span>
                                </div>
                            {else}
                                <div class="info least">
                                    <i class="icon"></i>
                                    <span class="subline">Order at least {$child->min_amount}</span>
                                </div>
                            {/if}
                        {/if}
                    {else}
                    <div class="info notify">
                        <span class="subline subscribe">Notify me when this product is in stock</span>
                        <div class="notify_form">
                            <div class="email"><input name="notify_email" value="" type="text" placeholder="Your email address"></div>
                            <div class="submit cidev_new_button cidev_new_white">Notify me</div>
                        </div>
                    </div>
                    {/if}
                </td>
                <td class="extended"><span class="currency">US$ </span><span class="value"></span></td>
            </tr>
        {/foreach}
    </table>
    <table width="100%" cellspacing="0" cellpadding="3" class="subtotal">
        <tr>
            <td class="sub" align="right">
                <div class="subtotal_class2"><span class="subtotal_class1">Subtotal:</span><span>US$ </span>
                    <span class="value"></span></div>
            </td>
        </tr>
        <tr>
            <td align="right">
                <div id="add_cart_group" class="btn_atcart_big disable"></div>
            </td>
        </tr>
    </table>
    <br/>
</div>
