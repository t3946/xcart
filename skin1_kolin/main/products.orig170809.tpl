{* $Id: products.tpl,v 1.42.2.2 2006/11/13 06:46:17 max Exp $ *}
{if $products ne ""}
{include file="main/check_all_row.tpl" style="line-height: 170%;" form="processproductform" prefix="productids"}

<script type="text/javascript">
<!--
var txt_pvariant_edit_note_list = "{$lng.txt_pvariant_edit_note_list|escape:javascript|replace:'"':'\"'}";

{literal}
function pvAlert(obj) {
	if (obj.pvAlertFlag)
		return false;

	alert(txt_pvariant_edit_note_list);
	obj.pvAlertFlag = true;
	return true;
}
{/literal}
-->
</script>

<table cellpadding="2" cellspacing="1" width="100%">

{if $main eq "category_products"}
{assign var="url_to" value="category_products.php?cat=`$cat`&page=`$navpage`"}
{else}
{assign var="url_to" value="search.php?mode=search&page=`$navpage`"}
{/if}

<tr class="TableHead">
	<td width="5">&nbsp;</td>
	<td>{$lng.lbl_products_more}</td>
	<td nowrap="nowrap">{if $search_prefilled.sort_field eq "productcode"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=productcode&amp;sort_direction={if $search_prefilled.sort_field eq "productcode"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_sku}</a></td>
	<td width="100%" nowrap="nowrap">{if $search_prefilled.sort_field eq "title"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=title&amp;sort_direction={if $search_prefilled.sort_field eq "title"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_product}</a></td>
{if $main eq "category_products"}
	<td nowrap="nowrap">{if $search_prefilled.sort_field eq "orderby"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=orderby&amp;sort_direction={if $search_prefilled.sort_field eq "orderby"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_pos}</a></td>
{/if}
	<td nowrap="nowrap">{if $search_prefilled.sort_field eq "price"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=price&amp;sort_direction={if $search_prefilled.sort_field eq "price"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_price}</a></td>
	<td>{$lng.lbl_list_price}</td>
	<td>{if $search_prefilled.sort_field eq "quantity"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=quantity&amp;sort_direction={if $search_prefilled.sort_field eq "quantity"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_in_stock}</a></td>
   	<td nowrap="nowrap">{$lng.lbl_weight}</td>
    <td>{$lng.lbl_shipping_freight}</td>
    <td>{$lng.lbl_avail}</td>
</tr>

{section name=prod loop=$products}

<tr{cycle values=', class="TableSubHead"'}>
	<td width="5"><input type="checkbox" name="productids[{$products[prod].productid}]" /></td>
	<td><a href="product_modify.php?productid={$products[prod].productid}{if $navpage}&page={$navpage}{/if}">{$lng.lbl_products_more}</a></td>
	<td nowrap><input type="text" size="12" name="posted_data[{$products[prod].productid}][productcode]" value="{$products[prod].productcode}" /></td>
	<td width="100%">{if $products[prod].main eq "Y" or $main ne "category_products"}<b>{/if}<input type="text" size="45" name="posted_data[{$products[prod].productid}][product]" value="{$products[prod].product|escape}" />{if $products[prod].main eq "Y" or $main ne "category_products"}</b>{/if}</td>
{if $main eq "category_products"}
	<td><input type="text" size="6" maxlength="10" name="posted_data[{$products[prod].productid}][orderby]" value="{$products[prod].orderby}" /></td>
{/if}
	<td>
	{if $products[prod].product_type ne 'C'}
	<input type="text" size="6" maxlength="15" name="posted_data[{$products[prod].productid}][price]" value="{$products[prod].price|formatprice}"{if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if} />
	{/if}
	</td>
	<td>
	{if $products[prod].product_type ne 'C'}
	<input type="text" size="6" maxlength="15" name="posted_data[{$products[prod].productid}][list_price]" value="{$products[prod].list_price|formatprice}"{if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if} />
	{/if}
	</td>
	<td align="center">
{if $products[prod].product_type ne 'C'}
<input type="text" size="6" maxlength="10" name="posted_data[{$products[prod].productid}][avail]" value="{$products[prod].avail}"{if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if} />
{/if}
	</td>
	<td>
	{if $products[prod].product_type ne 'C'}
	<input type="text" name="posted_data[{$products[prod].productid}][weight]" size="6" value="{$products[prod].weight|formatprice|default:$zero }" {if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if}/>
	{/if}
	</td>
	<td>
	{if $products[prod].product_type ne 'C'}
	<input type="text" name="posted_data[{$products[prod].productid}][shipping_freight]" size="6" value="{$products[prod].shipping_freight|formatprice|default:$zero }" /> 
	{/if}
	</td>
	<td>
		<select name="posted_data[{$products[prod].productid}][forsale]" width="10">
			<option value="Y"{if $products[prod].forsale eq "Y"} selected="selected"{/if}>Available</option>
			<option value="H"{if $products[prod].forsale eq "H"} selected="selected"{/if}>Hidden</option>
			<option value="N"{if $products[prod].forsale ne "Y" && $products[prod].forsale ne "H" && ($products[prod].forsale ne "B" || not $active_modules.Product_Configurator)} selected="selected"{/if}>{$lng.lbl_disabled}</option>
		{if $active_modules.Product_Configurator}
			<option value="B"{if $products[prod].forsale eq "B"} selected="selected"{/if}>Bundled</option>
		{/if}
		</select>
	</td>

</tr>

{/section}

</table>
{/if}
