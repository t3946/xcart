{* $Id: product_details.tpl,v 1.44.2.8 2006/10/13 10:41:21 svowl Exp $ *}

{capture name=dialog}

{if $taxes}
<script type="text/javascript" language="JavaScript 1.2">
<!--
function ChangeTaxesBoxStatus() {ldelim}
	if (document.modifyform && document.modifyform.elements['taxes[]'])
		document.modifyform.elements['taxes[]'].disabled = (document.modifyform.free_tax.value == 'Y');
{rdelim}

{literal}
function updateCategoryIds() {
	var elm = document.getElementById('categoryids_select');
	if (elm) {
		txt = '';
		for (var i=0; i < elm.options.length; i++) {
			if (elm.options[i].selected) {
				if (txt) {
					txt = txt + ',';
				}
				txt = txt + elm.options[i].value;
			}
		}
	}
	output = document.getElementById('categoryids_input');
	if (output) {
		output.value = txt;
	}
}



function cidev_change_discount_table() {

	var min_amount = document.getElementById('min_amount').value;

	if (min_amount > 1){
		txt = '';
		for (var i=1; i < 6; i++) {
			txt = txt + i*min_amount;
			
			if (i < 5){
				txt = txt + ',';
			}
		}

		document.getElementById('discount_table').value = txt;
	}

        if (min_amount == 1){
                document.getElementById('discount_table').value = '2,3,4,6,8,12';
        }
}


{/literal}
-->
</script>
{/if}

<script type="text/javascript" language="JavaScript 1.2">
<!--
var reps = Array();
{foreach from=$replacements item=r key=key}
	reps['{$key}'] = ['{$r.what|escape:javascript}', '{$r.by|escape:javascript}'];
{/foreach}

{literal}

function cap_first() {
	return arguments[0].toUpperCase();
}

function capitalize(id) {
	var text = $('#' + id).val();
	text = text.replace(/\b[a-z]/g, cap_first);
	for (i = 0; i < reps.length; i++) {
		pattern = new RegExp(reps[i][0], 'g');
		text = text.replace(pattern, reps[i][1]);
	}
	$('#' + id).val(text);
}

function copy_product_title_to_froogle() {
	var froogle_title = $('#product_name').val().substring(0,70);
	/*if (froogle_title.length > 70) {
		var froogle_title = froogle_title.substring(0,67);
		froogle_title = froogle_title + '...';
	}*/
	$('#froogle_title').val(froogle_title);
}

{/literal}

function generate_price(id) {ldelim}
	var res = 0;
	var list_price = $('#list_price').val();
	if (list_price == '') {ldelim}
		list_price = 0;
	{rdelim}
	var cost_to_us = $('#cost_to_us').val();
	if (cost_to_us == '') {ldelim}
		cost_to_us = 0;
	{rdelim}
	if (id == 'cost_to_us') {ldelim}
		res += {$product.cost_to_us_coef_x|default:0} * list_price;
	{rdelim}
	if (id == 'price') {ldelim}
		res += ({$product.price_coef_x|default:0} * cost_to_us + {$product.price_coef_y|default:0}) / {$product.price_coef_z|default:1};
	{rdelim}

        if (id == 'map_price') {ldelim}
                res += {$product.map_price_coef_x|default:0} * list_price;
        {rdelim}

	$('#' + id).val(round(res, 2));
{rdelim}
-->
</script>

{include file="check_froogle_upc_js.tpl"}

{if $product}
<table width="100%">

<tr>
	<td align="center" class="TopLabel">
        {if $product.forsale neq "N"}<a href="{$product.customer_url}" title="" target="_blank">{/if}{$lng.lbl_current_product}: "{$product.product}"{if $product.forsale neq "N"}</a>{/if}
	</td>
</tr>

</table>
{/if}

<form action="process_product.php" method="post" name="cloneproductform">
<input type="hidden" name="mode" value="clone" />
<input type="hidden" name="clone_detailed" value="" />
<input type="hidden" name="productid" value="{$product.productid}" />
</form>

<form action="product_modify.php" method="post" name="modifyform">
<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="section" value="main" />
<input type="hidden" name="mode" value="product_modify" />
<input type="hidden" name="geid" value="{$geid}" />

<table cellpadding="4" cellspacing="0" width="100%">

{if $geid ne ''}
<tr>
	<td width="15" class="TableSubHead">&nbsp;</td>
	<td class="TableSubHead" colspan="2"><b>* {$lng.lbl_note}:</b> {$lng.txt_edit_product_group}</td>
</tr>
{/if}

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_product_owner}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td class="FormButton" width="20%" nowrap="nowrap">{if $usertype eq "A" and $new_product eq 1}{$lng.lbl_provider}{else}{$lng.lbl_last_modified}{/if}:</td>
	<td class="ProductDetails" width="80%">
{if $usertype eq "A" and $new_product eq 1}
	<select name="provider" class="InputWidth">
{section name=prov loop=$providers}
		<option value="{$providers[prov].login}">{$providers[prov].login} ({$providers[prov].title} {$providers[prov].lastname} {$providers[prov].firstname})</option>
{/section}
	</select>
{else}
  {if $new_product eq 1}
    {assign var=mod_date value=$smarty.now|date_format:"%d-%b-%Y"}
    {assign var=mod_time value=$smarty.now|date_format:"%H:%M"}
  {else}
    {assign var=mod_date value=$product.mod_date|date_format:"%d-%b-%Y"}
    {assign var=mod_time value=$product.mod_date|date_format:"%H:%M"}
  {/if}
{assign var=provider_login value="`$provider_info.title` `$provider_info.firstname` `$provider_info.lastname` (`$provider_info.login`)"}
{$lng.txt_last_modified|substitute:login:$provider_login:date:$mod_date:time:$mod_time}
{/if}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[forsale]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_availability}:</td>
	<td class="ProductDetails">
	<select name="forsale">
		<option value="Y"{if $product.forsale eq "Y" || ($product.forsale ne "N" && $product.forsale ne "H" && ($product.forsale ne "B" || not $active_modules.Product_Configurator))} selected="selected"{/if}>{$lng.lbl_avail_for_sale}</option>
		<option value="H"{if $product.forsale eq "H"} selected="selected"{/if}>{$lng.lbl_hidden}</option>
		<option value="N"{if $product.forsale eq "N"} selected="selected"{/if}>{$lng.lbl_disabled}</option>
{if $active_modules.Product_Configurator}
		<option value="B"{if $product.forsale eq "B"} selected="selected"{/if}>{$lng.lbl_bundled}</option>
{/if}
	</select>
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_classification}</td>
</tr>

{if $active_modules.Manufacturers ne ""}
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[manufacturer]" /></td>{/if}
    <td class="FormButton" nowrap="nowrap">{$lng.lbl_manufacturer}:</td>
    <td class="ProductDetails">
	<select name="manufacturerid">
	    <option value=''{if $product.manufacturerid eq ''} selected="selected"{/if}>{$lng.lbl_no_manufacturer}</option>
    {foreach from=$manufacturers item=v}
    	<option value='{$v.manufacturerid}'{if $v.manufacturerid eq $product.manufacturerid} selected="selected"{/if}>{$v.manufacturer}</option>
    {/foreach}
    </select>
	</td>
</tr>
{/if}

{if $active_modules.Brands ne ""}
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[brand]" /></td>{/if}
    <td class="FormButton" nowrap="nowrap">{$lng.lbl_brand}:</td>
    <td class="ProductDetails">
	<select name="brandid">
	    <option value=''{if $product.brandid eq ''} selected="selected"{/if}>{$lng.lbl_no_brand}</option>
    {foreach from=$brands item=v}
    	<option value='{$v.brandid}'{if $v.brandid eq $product.brandid} selected="selected"{/if}>{$v.brand}</option>
    {/foreach}
    </select>
	</td>
</tr>
{/if}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;{* <input type="checkbox" value="Y" name="fields[categoryid]" /> *}</td>{/if}
	<td {* class="FormButton" *} nowrap="nowrap">{$lng.lbl_main_category}:</td>
	<td class="ProductDetails">{include file="main/category_selector.tpl" field="categoryid_text" extra=' style="width: 100%;"' categoryid=$product.categoryid|default:$default_categoryid override_onchange="javascript: document.getElementById('categoryid_input').value=this.options[this.selectedIndex].value;" display_only_selected=$product.productid|default:"Y"}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">{* &nbsp; *}<input type="checkbox" value="Y" name="fields[categoryid]" /></td>{/if}
	<td nowrap="nowrap" class="FormButton">{$lng.lbl_main_category_id}:</td>
	<td class="ProductDetails">
	<input type="text" name="categoryid" id="categoryid_input" size="8" value="{$product.categoryid|default:$default_categoryid}" />
	{if $top_message.fillerror ne "" and ($product.categoryid eq "" || $category_exists eq 'N')}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">{* <input type="checkbox" value="Y" name="fields[categoryids]" /> *}&nbsp;</td>{/if}
	<td {* class="FormButton" *} nowrap="nowrap">{$lng.lbl_additional_categories}:</td>
	<td class="ProductDetails">
	<select name="categoryids_text[]" id="categoryids_select" style="width: 100%;" multiple="multiple" size="8" onchange="javascript: updateCategoryIds();">
{foreach from=$allcategories item=c key=catid}
{if $c.productid eq $product.productid && $product.productid ne ""}
		<option value="{$catid}"{if ($c.productid eq $product.productid && $product.productid ne "") || ($product.productid eq '' && $product.add_categoryids && $product.add_categoryids[$catid])} selected="selected"{/if}>{$c.category_path}</option>
{/if}
{/foreach}
	</select>
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">{* &nbsp; *}<input type="checkbox" value="Y" name="fields[categoryids]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_additional_categories_ids}:</td>
	<td class="ProductDetails">
	<input type="text" name="categoryids" id="categoryids_input" size="40" value="{strip}
{assign var="need_comma" value=false}
{foreach from=$allcategories item=c key=catid}
{if ($c.productid eq $product.productid && $product.productid ne "") || ($product.productid eq '' && $product.add_categoryids && $product.add_categoryids[$catid])}{if $need_comma},{else}{assign var="need_comma" value=true}{/if}{$c.categoryid}{/if}
{/foreach}
{/strip}" style="width: 100%;"/>
	</td>
</tr>

{if $product.forsale eq 'H'}
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[categoryids]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_product_url}:</td>
	<td class="ProductDetails">{$catalogs.customer}/product.php?productid={$product.productid}&cat={$product.categoryid}</td>
</tr>
{/if}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_details}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_sku}:</td>
	<td class="ProductDetails"><input type="text" name="productcode" size="20" value="{$product.productcode}" class="InputWidth" /></td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_froogle_upc}:</td>
	<td class="ProductDetails"><input type="text" name="upc" size="20" value="{$product.upc}" class="InputWidth" /></td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_product_name}:</td>
	<td class="ProductDetails"> 
	<input type="text" name="product" id="product_name" size="45" class="InputWidth" value="{$product.product|escape}" />
	{if $top_message.fillerror ne "" and $product.product eq ""}<font class="Star">&lt;&lt;</font>{/if}
	&nbsp;<input type="button" value=" {$lng.lbl_capitalize|strip_tags:false|escape} " onclick="javascript: capitalize('product_name');" />
	</td>
</tr>

{if $product.product|strlen > $FROOGLE_TITLE_LENGTH || $new_product eq 1}
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_product_name_froogle}:</td>
	<td class="ProductDetails"> 
		<input type="text" name="product_froogle" id="froogle_title" size="45" maxlength="70" class="InputWidth" value="{$product.product_froogle|escape}" />
		&nbsp;<input type="button" value=" {$lng.lbl_copy|strip_tags:false|escape} " onclick="javascript: copy_product_title_to_froogle();" />
	</td>
</tr>
{/if}

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[google_search_term]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_google_prod_search_term}:</td>
	<td class="ProductDetails">
        <input type="text" name="google_search_term" class="InputWidth" value="{$product.google_search_term|escape:"html"}" />
        {if $config.Product_Page.google_prod_link_pattern && $product.google_search_term}
            &nbsp;<a href="{$config.Product_Page.google_prod_link_pattern|substitute:searchterm:$product.google_search_link}" target="_blank" title="">{$lng.lbl_google_prod_link}</a>
        {/if}
    </td>
</tr>

{if $active_modules.Egoods ne ""}
{include file="modules/Egoods/egoods.tpl"}
{/if}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[fulldescr]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_det_description}* :</td>
	<td class="ProductDetails">
	{include file="main/textarea.tpl" name="fulldescr" cols=45 rows=12 class="InputWidth" data=$product.fulldescr width="80%" btn_rows=4}
	{if $top_message.fillerror ne "" and $product.fulldescr eq ""}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[descr]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">
		{$lng.lbl_short_description}* :<br />
		<font style="font-weight: normal">{$lng.txt_short_descr}</font>
	</td>
	<td class="ProductDetails">
	{include file="main/textarea.tpl" name="descr" cols=45 rows=8 class="InputWidth" data=$product.descr width="80%" btn_rows=4}
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2">{$lng.txt_html_tags_in_description}</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_pricing}</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[list_price]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_list_price} <span class="Text">({$config.General.currency_symbol})</span></td>
	<td class="ProductDetails"><input type="text" name="list_price" id="list_price" size="18" value="{$product.list_price|formatprice|default:$zero}" /></td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[cost_to_us]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_cost_to_us} ({$config.General.currency_symbol})</td>
	<td class="ProductDetails">
		<input type="text" name="cost_to_us" id="cost_to_us" size="18" value="{$product.cost_to_us|formatprice|default:$zero}" />&nbsp;
		{if $product.cost_to_us_coef_x ne 0}
			<input type="button" value="{$lng.lbl_copy_to_us_button|replace:"X":"`$product.cost_to_us_coef_x`"}" onclick="javascript: generate_price('cost_to_us');" />&nbsp;
		{/if}	
		{if $top_message.fillerror ne "" and $product.cost_to_us eq ""}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">{if $product.is_variants eq 'Y'}&nbsp;{else}<input type="checkbox" value="Y" name="fields[price]" />{/if}</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_price} ({$config.General.currency_symbol})</td>
	<td class="ProductDetails">
{if $product.is_variants eq 'Y'}
<b>{$lng.lbl_note}:</b> {$lng.txt_pvariant_edit_note|substitute:"href":$variant_href}
{else}
	<input type="text" name="price" id="price" size="18" value="{ $product.price|formatprice|default:$zero}" />&nbsp;
	{if $product.price_coef_x ne 0 &&  $product.price_coef_y ne 0 &&  $product.price_coef_z ne 0}
		<input type="button" value="{$lng.lbl_price_button|replace:"X":"`$product.price_coef_x`"|replace:"Y":"`$product.price_coef_y`"|replace:"Z":"`$product.price_coef_z`"}" onclick="javascript: generate_price('price');" />&nbsp;
	{/if}	
	{if $top_message.fillerror ne "" and $product.price eq ""}<font class="Star">&lt;&lt;</font>{/if}
{/if}
	</td>
</tr>



<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[map_price]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_map_price} <span class="Text">({$config.General.currency_symbol})</span></td>
{*	<td class="ProductDetails"><input type="text" name="map_price" id="map_price" size="18" value="{$product.map_price|formatprice|default:$zero}" /></td> *}

        <td class="ProductDetails">
                <input type="text" name="map_price" id="map_price" size="18" value="{$product.map_price|formatprice|default:$zero}" />&nbsp;
                {if $product.map_price_coef_x ne 0}
                        <input type="button" value="{$lng.lbl_copy_to_us_button|replace:"X":"`$product.map_price_coef_x`"}" onclick="javascript: generate_price('map_price');" />&nbsp;
                {/if}
                {if $top_message.fillerror ne "" and $product.map_price eq ""}<font class="Star">&lt;&lt;</font>{/if}
        </td>


</tr>


 
<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_inventory}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">{if $product.is_variants eq 'Y'}&nbsp;{else}<input type="checkbox" value="Y" name="fields[avail]" />{/if}</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_quantity_in_stock}</td>
	<td class="ProductDetails">
{if $product.is_variants eq 'Y'}
<b>{$lng.lbl_note}:</b> {$lng.txt_pvariant_edit_note|substitute:"href":$variant_href}
{else}
	<input type="text" name="avail" size="18" value="{if $product.productid eq ""}{$product.avail|default:1000000}{else}{$product.avail}{/if}" />
	{if $top_message.fillerror ne "" and $product.avail eq ""}<font class="Star">&lt;&lt;</font>{/if}
{/if}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[low_avail_limit]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_lowlimit_in_stock}</td>
	<td class="ProductDetails"> 
	<input type="text" name="low_avail_limit" size="18" value="{if $product.productid eq ""}1000{else}{ $product.low_avail_limit }{/if}" />
	{if $top_message.fillerror ne "" and $product.low_avail_limit le 0}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[min_amount]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_min_order_amount}</td>
	<td class="ProductDetails"><input type="text" name="min_amount" size="18" value="{if $product.productid eq ""}1{else}{$product.min_amount}{/if}" id="min_amount" onBlur="javascript: cidev_change_discount_table();" onKeyUp="javascript: cidev_change_discount_table();" /></td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[mult_order_quantity]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_mult_order_quantity}</td>
	<td class="ProductDetails"><input type="checkbox" name="mult_order_quantity" value="Y"{if $product.mult_order_quantity eq "Y"} checked="checked"{/if} /></td>
</tr>

{if $active_modules.RMA ne ''}
<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[return_time]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_return_time}</td>
	<td class="ProductDetails"><input type="text" name="return_time" size="18" value="{$product.return_time}" /></td>
</tr>
{/if}

{*<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[membershipids]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_membership}</td>
	<td class="ProductDetails">{include file="main/membership_selector.tpl" data=$product}</td>
</tr>
*}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_taxes}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_tax]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_tax_exempt}</td>
	<td class="ProductDetails">
	<select name="free_tax"{if $taxes} onchange="javascript: ChangeTaxesBoxStatus();"{/if}>
		<option value='Y'{if $product.free_tax eq 'Y'} selected="selected"{/if}>{$lng.lbl_yes}</option>
		<option value='N'{if $product.free_tax eq 'N'} selected="selected"{/if}>{$lng.lbl_no}</option>
	</select> 
	</td>
</tr>

{if $taxes}
<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[taxes]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_apply_taxes}</td>
	<td class="ProductDetails"> 
	<select name="taxes[]" multiple="multiple"{if $product.free_tax eq "Y"} disabled="disabled"{/if}>
	{section name=tax loop=$taxes}
	<option value="{$taxes[tax].taxid}"{if $taxes[tax].selected gt 0} selected="selected"{/if}>{$taxes[tax].tax_name}</option>
	{/section}
	</select>
	<br />{$lng.lbl_hold_ctrl_key}
	{if $usertype eq "P" or $active_modules.Simple_Mode ne ""}<br /><a href="{$catalogs.provider}/taxes.php" class="SmallNote" target="_new">{$lng.lbl_click_here_to_manage_taxes}</a>{/if}
	</td>
</tr>
{/if}

{*
<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_shipping]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_free_shipping}</td>
	<td class="ProductDetails">
	<select name="free_shipping">
		<option value='N'{if $product.free_shipping eq 'N'} selected="selected"{/if}>{$lng.lbl_no}</option>
		<option value='Y'{if $product.free_shipping eq 'Y'} selected="selected"{/if}>{$lng.lbl_yes}</option>
	</select> 
	</td>
</tr>
*}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_shipping}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">{if $product.is_variants eq 'Y'}&nbsp;{else}<input type="checkbox" value="Y" name="fields[weight]" />{/if}</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_weight} ({$config.General.weight_symbol})</td>
	<td class="ProductDetails"> 
{if $product.is_variants eq 'Y'}
<b>{$lng.lbl_note}:</b> {$lng.txt_pvariant_edit_note|substitute:"href":$variant_href}
{else}
	<input type="text" name="weight" size="18" value="{ $product.weight|formatprice|default:$zero }" />
{/if}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[dimensions]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_shipping_dimensions}</td>
	<td class="ProductDetails"><input type="text" name="dimensions" size="18" value="{$product.dim_x|default:0},{$product.dim_y|default:0},{$product.dim_z|default:0}" /></td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[shipping_freight]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_shipping_freight} ({$config.General.currency_symbol})</td>
	<td class="ProductDetails">
	<input type="text" name="shipping_freight" size="18" value="{$product.shipping_freight|formatprice|default:0.01}" />
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_ship_zone]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_free_ship_destination}</td>
	<td class="ProductDetails">
	<select name="free_ship_zone">
	<option value="-1"{if $product.free_ship_zone eq '-1'} selected="selected"{/if}>{$lng.lbl_no_free_ship}</option>
	<option value="0"{if $product.free_ship_zone eq '0'} selected="selected"{/if}>{$lng.lbl_zone_default}</option>
	{section name=zid loop=$shipping_zones}
	<option value="{$shipping_zones[zid].zoneid}"{if $product.free_ship_zone eq $shipping_zones[zid].zoneid} selected="selected"{/if}>{$shipping_zones[zid].zone_name}</option>
	{/section}
	</select> 
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_ship_text]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_free_ship_text}</td>
	<td class="ProductDetails"><input type="text" name="free_ship_text" size="45" class="InputWidth" value="{$product.free_ship_text}" /></td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_discount_settings}</td>
</tr>

{if $gcheckout_enabled}

<input type="hidden" name="valid_for_gcheckout" value="N" />
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[valid_for_gcheckout]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_gcheckout_product_valid}</td>
	<td class="ProductDetails">
	<input type="checkbox" name="valid_for_gcheckout" value="Y"{if $product.productid eq "" || $product.valid_for_gcheckout eq "Y"} checked="checked"{/if} />
	</td>
</tr>

{/if}

{if $active_modules.Extra_Fields ne ""}
{include file="modules/Extra_Fields/product_modify.tpl"}
{/if}

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_slope]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_discount_slope}:</td>
        <td class="ProductDetails"><input type="text" name="discount_slope" size="18" value="{$product.discount_slope|formatprice|default:'0.40'}" /></td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_table]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_discount_table}:</td>
        <td class="ProductDetails"><input type="text" name="discount_table" id="discount_table" size="45" class="InputWidth" value="{$product.discount_table|escape|default:'2,3,4,6,8,12'}" /></td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_avail]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_apply_global_discounts}</td>
	<td class="ProductDetails">
	<input type="checkbox" name="discount_avail" value="Y"{if $product.productid eq "" || $product.discount_avail eq "Y"} checked="checked"{/if} />
	</td>
</tr>

<tr>
	<td{if $geid ne ''} colspan="2"{/if}>&nbsp;</td>
	<td><br />
			<input type="button" value=" {$lng.lbl_save|strip_tags:false|escape} " onclick="javascript: if (check_froogle_upc_field(document.modifyform.upc)) document.modifyform.submit(); else return false;" />
		</td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_product_details content=$smarty.capture.dialog extra='width="100%"'}
