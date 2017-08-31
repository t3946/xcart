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
            <tr>
                <td class="sku"><a href="{$child->getUrl()}" target="_blank">{$child->productcode}</a></td>
                <td></td>
                <td>{$child->product}</td>
                <td class="strike">{if floatval($child->list_price) > 0}{include file="currency.tpl" value=$child->list_price}{/if}</td>
                <td>{include file="currency.tpl" value=$child->getFrontendPrice()}</td>
                <td data-price="{$child->getFrontendPrice()}">{include file="customer/main/add_to_cart_input.tpl"}</td>
                <td class="extended"><span class="currency">US$ </span><span class="value"></span></td>
            </tr>
        {/foreach}
    </table>
    <table width="100%" cellspacing="0" cellpadding="3" class="subtotal">
        <tr>
            <td align="right">
                <div class="subtotal_class2"><span class="subtotal_class1">Subtotal:</span><span class="value"></span></div>
            </td>
        </tr>
    </table>
    <br/>
</div>
{literal}
    <script type="text/javascript">
        $(".spinner").spinner('changing', function(e, newVal, oldVal) {
            var spinner = $(this).closest('.spinner').parent();
            var sub = spinner.data('price') * newVal;
            spinner.next('.extended').find('span.currency').show().end().find('span.value').html(sub.toFixed(2));
        });
    </script>
{/literal}