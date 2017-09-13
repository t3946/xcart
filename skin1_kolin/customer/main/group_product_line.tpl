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
    {literal}
        <style type="text/css">
            table.group_product {
                border-collapse: collapse;
            }

            table.group_product th {
                font-size: 14px;
                font-weight: inherit;
            }

            table.group_product td.title {
                text-align: left;
                padding-left: 5px;
            }

            table.group_product td, table.group_product td div {
                font-size: 14px;
            }

            table.group_product td div.info {
                text-align: left;
                font-size: 11px;
                margin-top: 4px;
            }

            table.group_product td.spinner_cell {
                /*min-width: 120px;*/
            }

            table.group_product td div.info .icon {
                display: inline-block;
                text-indent: -9999px;
                width: 20px;
                height: 20px;
            }

            table.group_product td div.info.clock .icon {
                background: url(/skin1_kolin/images/group/out_of_stock.svg) no-repeat;
            }

            table.group_product td div.info.least .icon {
                background: url(/skin1_kolin/images/group/least.svg) no-repeat;
            }

            table.group_product td div.info.mult .icon {
                background: url(/skin1_kolin/images/group/mult.svg) no-repeat;
                position: relative;
                top: 10px;
            }

            table.group_product td div.info span {
                line-height: 28px;
                vertical-align: bottom;
            }

            table.group_product td div.info span.title {
                color: red;
            }

            table.group_product td div.info {
                text-align: center;
                line-height: 16px;
            }

            table.group_product td div.info.clock {
                text-align: left;
            }

            table.group_product td div.info.mult span {
                line-height: 16px;
            }

            table.group_product td div.info.notify {
                margin: 0 10px;
            }

            table.group_product td div.info.notify .subline {
                line-height: 18px;
            }

            table.group_product td div.info.notify .subline.subscribe{
                color:#065B94;
                cursor: pointer;
                text-decoration: underline;
                text-decoration-style: dotted;
            }


            table.group_product td span.subline {
                color: #5D5B5C;
                font-size: 11px;
            }

            table.group_product td .clock span.subline {
                margin-left: 10px;
            }

            table.group_product .extended {
                min-width: 100px;
            }

            table.group_product .extended span {
                display: none;
            }

            table.group_product img {
                width: 60px;
            }

            table.group_product td.sku > a {
                color: #28842F;
                text-decoration: none;
            }

            table.group_product td.sku > a:hover {
                text-decoration: underline;
            }

            table.group_product td.strike {
                color: #5D5B5C;
                text-decoration: line-through;
            }

            table.group_product, table.group_product th, table.group_product td {
                border: 2px solid #B4B4B4;
                text-align: center;
            }

            table.subtotal .subtotal_class2 {
                display: none;
            }

            table.subtotal .subtotal_class1 {
                margin-right: 16px;
            }

            table.subtotal td.sub {
                height: 30px;
            }

            table.group_product div.quantity {
                width: 105px;
                margin: 0 auto;
                height: 30px;
            }

            table.group_product .notify_form {
                display: none;
            }

            table.group_product .notify_form .email {
                margin-bottom: 10px;
            }

            table.group_product .notify_form .email input {
                border: solid 1px #dacf9c;
            }

            #add_cart_group.disable {
                pointer-events: none;
                cursor: pointer;
            }

            .btn_atcart_big {
                cursor: pointer;
            }

            .full_product_cell .seal{
                margin-top: 20px;
            }

            .full_product_cell .btn_full_product_line {
                background: transparent url(/skin1_kolin/images/add-to-cart-100.png) no-repeat -1224px 0;
                height: 42px;
                width: 226px;
                display: inline-block;
                cursor: pointer;
            }
            .full_product_cell .btn_full_product_line:hover {
                background: transparent url(/skin1_kolin/images/add-to-cart-100.png) no-repeat -1224px -43px;
            }
            .full_product_cell .btn_full_product_line:active {
                background: transparent url(/skin1_kolin/images/add-to-cart-100.png) no-repeat -1224px -86px;
            }

            .full_product_cell .full_line_info {
                color: #5D5B5C;
                padding: 5px 10px;
            }


        </style>
    {/literal}
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
                <td><img src="{$thumbnail->getURL()}"/></td>
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
                                    <span class="subline">Order multiples of {$child->min_amount} items</span>
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
