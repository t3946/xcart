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
 <td width="7%" nowrap="nowrap">{if $search_prefilled.sort_field eq "productcode"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=productcode&amp;sort_direction={if $search_prefilled.sort_field eq "productcode"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_sku}</a></td>
 <td width="30%" nowrap="nowrap">{if $search_prefilled.sort_field eq "title"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=title&amp;sort_direction={if $search_prefilled.sort_field eq "title"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_product}</a></td>

{if $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results eq "Y"}
<td nowrap="nowrap">
Description
<img src="{$ImagesDir}/spacer.gif" width="200" height="1" alt="" />
</td>
{/if}

<td width="10%">{$lng.lbl_main_add_categories}</td>

<td align="center">Prevent search index</td>

{if $main eq "category_products"}
 <td nowrap="nowrap">{if $search_prefilled.sort_field eq "orderby"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=orderby&amp;sort_direction={if $search_prefilled.sort_field eq "orderby"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_pos}</a></td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>{$lng.lbl_list_price}</td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>Cost to us</td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
<script language="JavaScript" type="text/javascript">
<!--
{literal}
function checkAll_price(flag, form, prefix) {
        if (!form)
                return;

        if (prefix)
                var reg = new RegExp("^"+prefix, "");
        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].type == "checkbox" && (!prefix || form.elements[i].name.search(reg) == 0) && !form.elements[i].disabled){
                        var cidev_id = 'cidev_'+form.elements[i].name;
                        document.getElementById(cidev_id).readOnly = false;
                        document.getElementById(cidev_id).style.background = '#ffffff';
                }
        }
	document.getElementById('cidev_box2').style.display=''; 
	document.getElementById('cidev_box1').style.display='none';
}
{/literal}
-->
</script>

 {if $usertype eq "A"}
 <td nowrap="nowrap">

<div id="cidev_box1">
<a style="text-decoration: none; border-bottom: 1px dashed #000000;" href="javascript: checkAll_price(true, document.processproductform, 'productids');">{$lng.lbl_price}</a>
</div>

<div id="cidev_box2" style="display: none;">
{if $search_prefilled.sort_field eq "price"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=price&amp;sort_direction={if $search_prefilled.sort_field eq "price"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_price}</a>
</div>


</td>
 {else}
 <td nowrap="nowrap">{if $search_prefilled.sort_field eq "price"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=price&amp;sort_direction={if $search_prefilled.sort_field eq "price"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_price}</a></td>
 {/if}
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>Map Price</td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>Bridge Price</td>
{/if}

{if $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>{if $search_prefilled.sort_field eq "quantity"}{include file="buttons/sort_pointer.tpl" dir=$search_prefilled.sort_direction}&nbsp;{/if}<a href="{$url_to|amp}&amp;sort=quantity&amp;sort_direction={if $search_prefilled.sort_field eq "quantity"}{if $search_prefilled.sort_direction eq 1}0{else}1{/if}{else}{$search_prefilled.sort_direction}{/if}">{$lng.lbl_in_stock}</a></td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
    <td nowrap="nowrap">{$lng.lbl_weight}</td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
   {if $usertype ne "P"}<td>{$lng.lbl_shipping_freight}</td>{/if}
{/if}

    <td>{$lng.lbl_avail}</td>

{if $search_prefilled.show_product_attributes_in_search_results eq "Y"}
 <td>{$lng.lbl_cidev_filters}</td>
{/if}

</tr>

{section name=prod loop=$products}

<tr{cycle values=', class="TableSubHead"'}>
 <td width="5"><input type="checkbox" name="productids[{$products[prod].productid}]" /></td>
 <td><a href="{*http://{if $products[prod].domain ne ""}{$products[prod].domain}{else}artistsupplysource.com{/if}/{if $usertype eq "A"}admin{else}provider{/if}/*}product_modify.php?productid={$products[prod].productid}{if $navpage}&page={$navpage}{/if}">

{if $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results eq "Y"}

{include file="product_thumbnail.tpl" productid=$products[prod].productid image_x=$config.Appearance.thumbnail_width product=$products[prod].product tmbn_url=$products[prod].tmbn_url add_http_if_cdn='Y'}</a>

{else}
{$lng.lbl_products_more}
{/if}

</a></td>
 <td nowrap><input type="text" name="posted_data[{$products[prod].productid}][productcode]" value="{$products[prod].productcode}" size="20" /></td>
 <td>{if $products[prod].main eq "Y" or $main ne "category_products"}<b>{/if}<input type="text" size="45" name="posted_data[{$products[prod].productid}][product]" value="{$products[prod].product|escape}" />{if $products[prod].main eq "Y" or $main ne "category_products"}</b>{/if}</td>


{if $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results eq "Y"}
<td>
{if $products[prod].descr ne ""}
{$products[prod].descr|escape|truncate:325:"...":true}
{else}
{$products[prod].fulldescr|escape|truncate:325:"...":true}
{/if}
</td>
{/if}

<td align="center" nowrap="nowrap"><input type="text" size="5" name="posted_data[{$products[prod].productid}][main_category]" value="{$products[prod].main_cat}" />&nbsp;<input type="text" size="10" name="posted_data[{$products[prod].productid}][add_cats]" value="{$products[prod].add_cats}" /></td>

<td align="center">
{$products[prod].prevent_search_indexing}
</td>

{if $main eq "category_products"}
 <td><input type="text" size="6" maxlength="10" name="posted_data[{$products[prod].productid}][orderby]" value="{$products[prod].orderby}" /></td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>
 {if $products[prod].product_type ne 'C'}
 <input type="text" size="6" maxlength="15" name="posted_data[{$products[prod].productid}][list_price]" value="{$products[prod].list_price|formatprice}"{if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if} />
 {/if}
 </td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>
 {if $products[prod].product_type ne 'C'}
 <input type="text" size="6" maxlength="15" name="posted_data[{$products[prod].productid}][cost_to_us]" value="{$products[prod].cost_to_us|formatprice}" />
 {/if}
 </td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>
 {if $products[prod].product_type ne 'C'}
 <input id="cidev_productids[{$products[prod].productid}]" type="text" size="6" maxlength="15" name="posted_data[{$products[prod].productid}][price]" value="{$products[prod].price|formatprice}"{if $products[prod].is_variants eq 'Y'}  onclick="javascript: pvAlert(this);"{/if} readonly="readonly" style="background: #cccccc;" />
 {/if}
 </td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>
 {if $products[prod].product_type ne 'C'}
 <input type="text" size="6" maxlength="15" name="posted_data[{$products[prod].productid}][new_map_price]" value="{$products[prod].new_map_price|formatprice}"{if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if} />
 {/if}
 </td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>
 {if $products[prod].product_type ne 'C'}
 <input type="text" size="6" maxlength="15" name="posted_data[{$products[prod].productid}][map_price]" value="{$products[prod].map_price|formatprice}"{if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if} />
 {/if}
 </td>
{/if}

{if $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td align="center">
{if $products[prod].product_type ne 'C'}
<input type="text" size="6" maxlength="10" name="posted_data[{$products[prod].productid}][r_avail]" value="{$products[prod].r_avail}"{if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if} />
{/if}
 </td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 <td>
 {if $products[prod].product_type ne 'C'}
 <input type="text" name="posted_data[{$products[prod].productid}][weight]" size="6" value="{$products[prod].weight|formatprice|default:$zero }" {if $products[prod].is_variants eq 'Y'} readonly="readonly" onclick="javascript: pvAlert(this);"{/if}/>
 {/if}
 </td>
{/if}

{if $search_prefilled.show_product_attributes_in_search_results ne "Y" && $search_prefilled.show_product_descriptions_and_thumbnails_in_search_results ne "Y"}
 {if $usertype ne "P"}
 <td>
  {if $products[prod].product_type ne 'C'}
 <input type="text" name="posted_data[{$products[prod].productid}][shipping_freight]" size="6" value="{$products[prod].shipping_freight|formatprice|default:$zero }" /> 
  {/if}
 </td>
 {/if}
{/if}

 <td>
  <select name="posted_data[{$products[prod].productid}][forsale]" width="10">
   <option value="Y"{if $products[prod].forsale eq "Y"} selected="selected"{/if}>Available</option>
{*   <option value="H"{if $products[prod].forsale eq "H"} selected="selected"{/if}>Hidden</option> *}
   <option value="N"{if $products[prod].forsale ne "Y" && $products[prod].forsale ne "H" && ($products[prod].forsale ne "B" || not $active_modules.Product_Configurator)} selected="selected"{/if}>{$lng.lbl_disabled}</option>
  {if $active_modules.Product_Configurator}
   <option value="B"{if $products[prod].forsale eq "B"} selected="selected"{/if}>Bundled</option>
  {/if}
  </select>
 </td>

{if $search_prefilled.show_product_attributes_in_search_results eq "Y"}
 <td nowrap="nowrap">
                {if $cidev_filters_tree ne "" && $products[prod].cidev_filter_products ne ""}
                        {foreach from=$cidev_filters_tree item=v key=k}
                                {assign var="filter_name_is_shown" value=""}
                                {foreach from=$products[prod].cidev_filter_products item=vv key=kk}
                                        {if $v.f_id eq $vv.f_id}
                                                {if $filter_name_is_shown ne "Y"}
                                                        {if $v.f_id eq $f_id}<B>{/if}{$v.f_name}:{if $v.f_id eq $f_id}</B>{/if}
                                                        {assign var="filter_name_is_shown" value="Y"}
                                                {/if}

                                                {$vv.fv_name};
                                        {/if}
                                {/foreach}
                                {if $filter_name_is_shown eq "Y"}
                                <br />
                                {/if}
                        {/foreach}
                {/if}
</td>
{/if}

</tr>

{/section}

</table>
{/if}
