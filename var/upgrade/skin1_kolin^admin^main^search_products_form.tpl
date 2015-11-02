{* $Id: search_products_form.tpl,v 1.9.2.1 2006/07/11 08:39:26 svowl Exp $ *}

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td>

<form action="configuration.php" method="post">
<input type="hidden" name="option" value="{$option}" />
<input type="hidden" name="mode" value="update_status" />

<input type="hidden" name="update[manufacturers][exist]" value='Y' />
<input type="hidden" name="update[category][exist]" value='Y' />
<input type="hidden" name="update[price][exist]" value='Y' />
<input type="hidden" name="update[weight][exist]" value='Y' />

{include file="main/subheader.tpl" title=$lng.lbl_search_products_sep1 class="black"}
<table cellpadding="3" cellspacing="1" width="100%">
<tr>
    <td class="TableSubHead" width="23%"><label for="search_products_box_code">{$lng.lbl_search_products_box_code}:</label></td>
    <td class="TableSubHead" width="77%"><textarea name="search_products_box_code" cols="92" rows="15" style="width: 99%">{$config.Search_products.search_products_box_code|escape:html}</textarea></td>
</tr>
<tr>
    <td width="23%"><label for="search_products_result_code">{$lng.lbl_search_products_results_code}:</label></td>
    <td width="77%"><textarea name="search_products_result_code" cols="92" rows="5" style="width: 99%">{$config.Search_products.search_products_result_code|escape:html}</textarea></td>
</tr>
</table>
<br />
<br />
{include file="main/subheader.tpl" title=$lng.lbl_search_products_sep2 class="black"}

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
	<td width="20%" nowrap="nowrap">{$lng.lbl_field_name}</td>
	<td align="center">{$lng.lbl_active}</td>
	<td align="center">{$lng.lbl_default_value}</td>
</tr>

{if $active_modules.Manufacturers && $manufacturers}
{if $active_modules.Multiple_Storefronts && $domain_specific_config.Search_products.search_products_manufacturers eq "Y" && $current_storefront neq 0}
	<tr>
		<td colspan="3"><br />{$lng.txt_domain_specific_options}</td>
	</tr>
{/if}
<tr>
	<td>{$lng.lbl_manufacturers}</td>
	<td align="center"><input type="checkbox" name="update[manufacturers][avail]" value='Y'{if $config.Search_products.search_products_manufacturers eq 'Y'} checked="checked"{/if}{if $active_modules.Multiple_Storefronts && $domain_specific_config.Search_products.search_products_manufacturers eq "Y" && $current_storefront neq 0} disabled="disabled"{/if} /></td>
	<td><select name="update[manufacturers][default][]" multiple="multiple"{if $active_modules.Multiple_Storefronts && $domain_specific_config.Search_products.search_products_manufacturers eq "Y" && $current_storefront neq 0} disabled="disabled"{/if}>
	{foreach from=$manufacturers item=v}
	<option value='{$v.manufacturerid}'{if $v.selected eq 'Y'} selected="selected"{/if}>{$v.manufacturer}</option>
	{/foreach}
	</select></td>
</tr>
{/if}

<tr>
    <td>{$lng.lbl_category}</td>
    <td align="center"><input type="checkbox" name="update[category][avail]" value='Y'{if $config.Search_products.search_products_category eq 'Y'} checked="checked"{/if} /></td>
    <td>&nbsp;</td>
</tr> 

<tr> 
    <td>{$lng.lbl_price}</td> 
    <td align="center"><input type="checkbox" name="update[price][avail]" value='Y'{if $config.Search_products.search_products_price eq 'Y'} checked="checked"{/if} /></td>
    <td><input size="10" type="text" name="update[price][default][begin]" value='{$config.Search_products.search_products_price_d|regex_replace:"/-.*$/":""}' />&nbsp;-&nbsp; 
	<input size="10" type="text" name="update[price][default][end]" value='{$config.Search_products.search_products_price_d|regex_replace:"/^.*-/":""}' /></td>
</tr> 

<tr>
    <td>{$lng.lbl_weight}</td>   
    <td align="center"><input type="checkbox" name="update[weight][avail]" value='Y'{if $config.Search_products.search_products_weight eq 'Y'} checked="checked"{/if} /></td>
    <td><input size="10" type="text" name="update[weight][default][begin]" value='{$config.Search_products.search_products_weight_d|regex_replace:"/-.*$/":""}' />&nbsp;-&nbsp;
    <input size="10" type="text" name="update[weight][default][end]" value='{$config.Search_products.search_products_weight_d|regex_replace:"/^.*-/":""}' /></td>
</tr>

{if $active_modules.Extra_Fields && $extra_fields ne ''}
<tr>
	<td colspan="3"><br />{include file="main/subheader.tpl" title=$lng.lbl_extra_fields class="grey"}</td>
</tr>

{foreach from=$extra_fields item=v}
<tr>
    <td>{$v.field}</td>
    <td><input type="checkbox" name="extra_fields[{$v.fieldid}]" value='Y'{if $v.selected eq 'Y'} checked="checked"{/if} /></td>
</tr>

{/foreach}
{/if}

<tr>
	<td colspan="3"><br /><input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} " /></td>
</tr>

</table>
</form>

</td>
</tr>
</table>

