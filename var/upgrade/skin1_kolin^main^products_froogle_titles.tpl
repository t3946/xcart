{* $Id: products_froogle_titles.tpl,v 1.0 2011/07/12 16:00:17 kate Exp $ *}
{if $products ne ""}
    {literal}
    <style>
    @-moz-document url-prefix() {
      .froogle_main_title_input {
        width: 799px;
      }
    }
    </style>
    {/literal}
    {assign var="url_to" value="search.php?mode=search&page=`$navpage`"}

    <script type="text/javascript">
    <!--
        var froogle_title_length = '{$FROOGLE_TITLE_LENGTH}';
        {literal}
        function copy_product_title_to_froogle(name_id, froogle_id) {
            var froogle_title = $('#' + name_id).val().substring(0,froogle_title_length);
            $('#' + froogle_id).val(froogle_title);
        }

        function calculate_symbols(el) {
            var length = $(el).val().length;
            $(el).parent().find('input[name="all_symbol"]').val(length);
            $(el).parent().find('input[name="left_symbol"]').val(length > froogle_title_length ? 0 : froogle_title_length - length);
        }

        $(document).ready(function() {
            $('.froogle_title_input').keyup(function() {
                calculate_symbols(this);
            });
            $('.froogle_title_input').each(function() {
                calculate_symbols(this);
            });
        })
        {/literal}
    -->
    </script>

    <table cellpadding="6" cellspacing="1">

    <tr class="TableHead">
        <td width="7%" nowrap="nowrap">{if $search_prefilled.sort_field eq "productcode"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=productcode&amp;sort_direction={if $search_prefilled.sort_field eq "productcode"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_sku}</a></td>
        <td width="30%" nowrap="nowrap">{if $search_prefilled.sort_field eq "title"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=title&amp;sort_direction={if $search_prefilled.sort_field eq "title"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_product}</a> / {$lng.lbl_product_name_froogle}</td>
    <td>{$lng.lbl_format}</td>
</tr>

{section name=prod loop=$products}

<tr{cycle values=', class="TableSubHead"'}>
 <td nowrap="nowrap" class="link-center">
    <a href="product_modify.php?productid={$products[prod].productid}{if $navpage}&page={$navpage}{/if}">{$products[prod].productcode}</a>
    <br />
    <a href="{$catalogs.customer}/product.php?productid={$products[prod].productid}" target="_blank">{$lng.lbl_frontend}</a>
 </td>
 <td>
    {if $products[prod].product|strlen > $FROOGLE_TITLE_LENGTH}{$products[prod].product|substr:0:$FROOGLE_TITLE_LENGTH}<span class="override_name">{$products[prod].product|substr:$FROOGLE_TITLE_LENGTH}</span><br />{/if}
    <input type="text" name="posted_data[{$products[prod].productid}][product]" value="{$products[prod].product|escape}" id="product_name_{$products[prod].productid}" size="125" class="froogle_main_title_input" />
    <br />
    <input type="text" name="posted_data[{$products[prod].productid}][product_froogle]" value="{$products[prod].product_froogle|escape}" id="froogle_{$products[prod].productid}" size="117" maxlength="{$FROOGLE_TITLE_LENGTH}" class="froogle_title_input"/>
    <input type="text" size="2" name="all_symbol" class="froogle_calc_size"/>
    <input type="text" size="2" name="left_symbol" class="froogle_calc_size"/>
 </td>
 <td>
	{include file="capitalize_js.tpl" id="product_name_`$products[prod].productid`"}
    <br />
    <input type="button" value=" {$lng.lbl_copy|strip_tags:false|escape} " onclick="javascript: copy_product_title_to_froogle('product_name_{$products[prod].productid}', 'froogle_{$products[prod].productid}');" />
    
 </td>

</tr>

{/section}

</table>
{/if}
