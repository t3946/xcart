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

            table.group_product td div.info.clock .icon {
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

            table.subtotal td.sub {
                height: 30px;
            }

            table.group_product div.quantity {
                width: 105px;
                margin: 0 auto;
                height: 30px;
            }

            #add_cart_group.disable {
                pointer-events: none;
                cursor: pointer;
            }

            .btn_atcart_big {
                cursor: pointer;
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
            <tr class="row" data-product-id="{$child->productid}" data-brand="{$child->brand->brand}"
                data-title="{$child->product}" data-category="{$main_cat->category->category}">
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
                        {if $child->min_amount > 1}
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
                return aprice[cur_price].price * newVal;
            }

            function calcSubtotoal() {
                var subtotal = 0;
                $('.group_product .row').each(function () {
                    var spinner = $(this).find('.spinner_cell');
                    var val = parseInt(spinner.find('input.quantity').val());
                    if (val > 0) {
                        subtotal += getPrice(spinner.data('price'), val);
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
                return subtotal;
            }

            $(".spinner").spinner('changing', function (e, newVal, oldVal) {
                var spinner = $(this).closest('.spinner').parent();
                var aprice = spinner.data('price');

                if (newVal === 0) {
                    spinner.next('.extended').find('span').hide().end().find('span.value').html('');
                } else {
                    spinner.next('.extended').find('span').show().end()
                        .find('span.value').html(getPrice(aprice, newVal)
                        .toLocaleString('en-US', {
                            style: 'decimal',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                            currency: 'USD'
                        })
                    );
                }

                var subtotal = calcSubtotoal() || 0;
                if (subtotal > 0) {
                    $('#add_cart_group').removeClass('disable').removeClass().addClass('btn_atcart_big');
                } else {
                    $('#add_cart_group').addClass('disable');
                }

            });

            $('#add_cart_group').click(function () {

                var pr = {};
                var $this = $(this);
                var rows = $(this).closest('.subtotal')
                    .siblings('.group_product')
                    .find('.row');


                rows.each(function () {
                    var price_table = $(this).find('.spinner_cell').data('price');
                    var q = parseInt($(this).find('input.quantity').val()) || 0;
                    if (q > 0) {
                        pr[$(this).data('product-id')] = {
                            quantity: q,
                            price: getPrice(price_table, q),
                            brand: $(this).data('brand'),
                            title: $(this).data('title'),
                            category: $(this).data('category')
                        };
                    }
                });
                if (Object.keys(pr).length > 0) {

                    $this.removeClass('btn_atcart_big').addClass('btn_atcart_big_wait disable');

                    $.post('ajax.php', {
                            ajax_action: 'add_cart_group',
                            products: pr
                        },
                        function (data) {
                            if (data.error == 'Y') {
                                $this.removeClass('btn_atcart_big_wait').addClass('btn_atcart_big_error');
                            } else {
                                $this.removeClass('btn_atcart_big_wait').addClass('btn_atcart_big_added');
                                if (data.display) {
                                    $('#ajax_minicart').html(data.display);
                                }
                                rows.find('input.quantity').val(0).change();
                            }
                            setTimeout(function () {
                                $('#add_cart_group').removeClass('btn_atcart_big_added btn_atcart_big_error').addClass('btn_atcart_big');
                            }, 3000)

                        }, 'json');

                    $.each(pr, function (key, p) {
                        console.log(p);
                        ga('ec:addProduct', {
                            'id': key,
                            'name': p.title,
                            'category': p.category,
                            'brand': p.title,
                            'price': p.price,
                            'quantity': p.quantity
                        });
                    });

                    ga('ec:setAction', 'add', {list: 'detail_page'});
                    ga('send', 'event', 'UX', 'click', 'Add to cart group');
                }
            })

        })();
    </script>
{/literal}