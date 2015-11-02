{* $Id: product_n_category.tpl,v 1.10.2.2 2006/07/11 08:39:34 svowl Exp $ *}
{if $with_qnty eq "Y"}
{assign var="param_cols" value="3"}
{else}
{assign var="param_cols" value="2"}
{/if}
<table cellpadding="3" cellspacing="1" width="100%">
{if $params ne ""}
<tr class="TableHead">
	<th width="5">&nbsp;</th>
	<th width="100%">{$lng.lbl_product}</th>
{if $with_qnty eq "Y"}
	<th>{$lng.lbl_sp_quantity}</th>
{/if}
</tr>
{foreach name=params from=$params item=param}
{if $smarty.foreach.params.first ne 1}
<tr class="TableSubHead">
	<td colspan="{$param_cols}" align="center">
{if $join_type eq "and"}
 {$lng.lbl_sp_and}
{else}
 {$lng.lbl_sp_or}
{/if}
	</td>
</tr>
{/if}
<tr>
	<td width="10" valign="top">
<input type="hidden" name="param[{$param.paramid}][param_type]" value="{$param.param_type}" />

{if $viewonly eq "Y"}
&nbsp;
{else}
<input type="checkbox" name="param_del[{$param.paramid}]" />
{/if}
	</td>
	<td>
   {if $param.param_type eq "P"}
   {$lng.lbl_product}: ({$param.productcode}) {$param.product}
   {elseif $param.param_type eq "C"}
   {$lng.lbl_sp_products_from_cat_s|substitute:"cat":$param.category}
   <br />
   {$lng.lbl_sp_with_subcategories}:
	<select name="param[{$param.paramid}][param_arg]">
		<option value="Y"{if $param.param_arg eq "Y"} selected="selected"{/if}>{$lng.lbl_yes}</option>
		<option value=""{if $param.param_arg ne "Y"} selected="selected"{/if}>{$lng.lbl_no}</option>
	</select>
   {/if}
	</td>
{if $with_qnty eq "Y"}
	<td valign="top" align="center"><input type="text" name="param[{$param.paramid}][param_qnty]" value="{$param.param_qnty}" size="4" /></td>
{/if}
</tr>
{/foreach}

<tr>
	<td colspan="{$param_cols}" class="SubmitBox">
<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="document.{$form_name}.action.value='delete';document.{$form_name}.submit();" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" />
	</td>
</tr>
{/if}{* $params ne "" *}

</table>

{* NEW PARAM *}
{if $viewonly ne "Y"}
<table width="100%">
<tr>
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_sp_add_param}</td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td>

<table cellpadding="3" cellspacing="1">
<tr>
	<td>{$lng.lbl_product}:</td>
	<td nowrap="nowrap">
<input type="hidden" name="new_param_{$mainid}_productid" />
<input type="text" name="prod_name_{$mainid}" size="40" style="width=50%" disabled="disabled" /> <input type="button" value="{$lng.lbl_browse_|strip_tags:false|escape}" onclick="javascript: popup_product('{$form_name}.new_param_{$mainid}_productid', '{$form_name}.prod_name_{$mainid}');" />
	</td>
</tr>

<tr>
	<td>{$lng.lbl_category}:</td>
	<td nowrap="nowrap">{include file="main/category_selector.tpl" field="new_param_`$mainid`_categoryid" display_empty="P"}</td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td><input type="button" value=" {$lng.lbl_add|strip_tags:false|escape} " onclick="javascript: document.{$form_name}.action.value='add'; document.{$form_name}.submit();" /></td>
</tr>
</table>

	</td>
</tr>
{/if}
</table>
