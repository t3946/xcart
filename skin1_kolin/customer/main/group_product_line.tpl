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

            table.group_product td {
                font-size: 14px;
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

            table.subtotal .subtotal_class2{
                display: none;
            }

            table.subtotal .subtotal_class1{
                margin-right: 16px;
            }

            table.group_product div.quantity{
                width: 105px;
                margin: 0 auto;
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
                <td><img src="{$thumbnail->getURL()}" /></td>
                <td>{$child->product}</td>
                <td class="strike">{if floatval($child->list_price) > 0}{include file="currency.tpl" value=$child->list_price}{/if}</td>
                <td>{include file="currency.tpl" value=$child->getFrontendPrice()}</td>
                <td class="spinner_cell" data-price='{getPricingArray pricing=$child->pricing json=true}'>
                    {include file="customer/main/add_to_cart_input.tpl"}
                </td>
                <td class="extended"><span class="currency">US </span><span class="value"></span></td>
            </tr>
        {/foreach}
    </table>
    <table width="100%" cellspacing="0" cellpadding="3" class="subtotal">
        <tr>
            <td align="right">
                <div class="subtotal_class2"><span class="subtotal_class1">Subtotal:</span><span>US </span><span class="value"></span></div>
            </td>
        </tr>
    </table>
    <br/>
</div>
{literal}
    <script type="text/javascript">
        (function() {

            function getPrice(aprice, newVal){
                var cur_price = 0;
                for(var index in aprice) {
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
                        style: 'currency',
                        currency: 'USD'
                        })
                    );
                }


                $('.group_product .row').each(function(){
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
                        style: 'currency',
                        currency: 'USD'})
                    )
                    .end()
                    .show();
            });

        })();
    </script>
{/literal}