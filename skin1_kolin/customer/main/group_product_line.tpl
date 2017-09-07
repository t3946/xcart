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
                min-width: 120px;
            }

            table.group_product td div.info .icon {
                display: inline-block;
                text-indent: -9999px;
                width: 18px;
                height: 18px;
            }

            table.group_product td div.info.clock .icon{
                background: url(/skin1_kolin/images/group/out_of_stock.svg) no-repeat;
            }

            table.group_product td div.info.least .icon {
                background: url(/skin1_kolin/images/group/least.svg) no-repeat;
            }

            table.group_product td div.info span {
                line-height: 24px;
                vertical-align: bottom;
            }

            table.group_product td div.info span.title {
                color: red;
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

            table.group_product div.quantity {
                width: 105px;
                margin: 0 auto;
                height: 30px;
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
            <tr class="row">
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
                <td class="spinner_cell" data-price='{getPricingArray pricing=$child->pricing json=true}'>
                    {if !$child->isProductOutOfStock()}
                        {if $child->mult_order_quantity == 'Y' && $child->min_amount > 1}
                            {assign var=step value=$child->min_amount}
                         {else}
                            {assign var=step value=1}
                        {/if}
                        {include file="customer/main/add_to_cart_input.tpl" min=$child->min_amount max=$child->avail step=$step}
                        {if $child->min_amount > 1}
                            <div class="info least">
                                <i class="icon"></i>
                                <span class="subline">Order at least {$child->min_amount}</span>
                            </div>
                        {/if}
                    {/if}
                </td>
                <td class="extended"><span class="currency">US$ </span><span class="value"></span></td>
            </tr>
        {/foreach}
    </table>
    <table width="100%" cellspacing="0" cellpadding="3" class="subtotal">
        <tr>
            <td align="right">
                <div class="subtotal_class2"><span class="subtotal_class1">Subtotal:</span><span>US$ </span><span
                            class="value"></span></div>
            </td>
        </tr>
    </table>
    <br/>
</div>
{literal}
    <script type="text/javascript">
        (function () {

            function getPrice(aprice, newVal) {
                var cur_price = 0;
                for (var index in aprice) {
                    if (newVal > 0 && newVal >= index) {
                        cur_price = index;
                    }
                }
                return cur_price;
            }

            $(".spinner").spinner('changing', function (e, newVal, oldVal) {
                var spinner = $(this).closest('.spinner').parent();
                var aprice = spinner.data('price');
                var sub = '';
                var subtotal = 0;

                var cur_price = getPrice(aprice, newVal);

                if (newVal === 0) {
                    sub = '';
                    spinner.next('.extended').find('span').hide().end().find('span.value').html(sub);
                } else {
                    sub = aprice[cur_price].price * newVal;
                    spinner.next('.extended').find('span').show().end()
                        .find('span.value').html(sub
                        .toLocaleString('en-US', {
                            style: 'decimal',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                            currency: 'USD'
                        })
                    );
                }


                $('.group_product .row').each(function () {
                    var spinner = $(this).find('.spinner_cell');
                    var val = parseInt(spinner.find('input.quantity').val());
                    var ap = spinner.data('price');
                    if (val > 0) {
                        subtotal += ap[getPrice(ap, val)].price * val;
                    }
                });

                $('table.subtotal .subtotal_class2').find('.value')
                    .html(
                        subtotal.toLocaleString('en-US', {
                            style: 'decimal',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                            currency: 'USD'
                        })
                    )
                    .end()
                    .show();
            });

        })();
    </script>
{/literal}