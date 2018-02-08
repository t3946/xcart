{* $Id: returns.tpl,v 1.17.2.3 2006/07/11 08:39:33 svowl Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_returns}

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<br />

{if $mode ne 'reasons' && $mode ne 'actions' && $mode ne 'modify'}
{capture name=dialog}
<form action="returns.php" method="post" name="searchreturnsform">
<input type="hidden" name="mode" value="search" />

<table>
<tr>
	<td>{$lng.lbl_period_from}</td>
	<td>{html_select_date prefix="start_date_" time=$search.start_date start_year=$config.Company.start_year end_year=$config.Company.end_year}</td>
</tr>
<tr>
    <td>{$lng.lbl_period_to}</td>
    <td>{html_select_date prefix="end_date_" time=$search.end_date start_year=$config.Company.start_year end_year=$config.Company.end_year}</td> 
</tr>
<tr>
    <td>{$lng.lbl_returnid}</td>
    <td><input type="text" name="search[returnid]" value="{$search.returnid}" size="5" /></td>
</tr>
<tr>
	<td>{$lng.lbl_statuses}</td>
	<td><select name="search[status]">
	<option value=''{if $search.status eq ''} selected="selected"{/if}>{$lng.lbl_all}</option>
    {foreach from=$statuses item=s key=k}
	{if $k ne 'E' || $usertype ne 'C'}
    <option value='{$k}'{if $k eq $search.status} selected="selected"{/if}>{$s}</option>
	{/if}
    {/foreach}
    </select></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="{$lng.lbl_search|strip_tags:false|escape}" /></td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_search extra='width="100%"'}

<br />
{/if}

{if $mode ne 'reasons' && $mode ne 'actions' && $mode ne 'modify'} 
{if $mode eq 'search'}
{if $returns_count eq ''}
{assign var="returns_count" value="0"}
{/if}
{$lng.txt_N_results_found|substitute:"items":$returns_count}
{/if}
{if $returns ne ''}
{capture name=dialog}
<script type="text/javascript" language="JavaScript 1.2">
<!--
checkboxes_form = 'returnsform';
checkboxes = new Array({foreach from=$returns item=v key=k}{if $k > 0},{/if}'to_delete[{$v.returnid}]'{/foreach});
-->
</script>
{include file="main/include_js.tpl" src="change_all_checkboxes.js"}

<div style="line-height:170%"><a href="javascript:change_all(true);">{$lng.lbl_check_all}</a> / <a href="javascript:change_all(false);">{$lng.lbl_uncheck_all}</a></div>

<form action="returns.php" method="post" name="returnsform">
<input type="hidden" name="mode" value="" />

{assign var="colspan" value="6"}
<table width="100%" cellpadding="3" cellspacing="1">
<tr class="TableHead">
	<td width="10">&nbsp;</td>
	<td align="center">{$lng.lbl_returnid}</td>
{if $usertype ne 'C'}
	{math assign="colspan" equation="x+1" x=$colspan}
	<td align="center">{$lng.lbl_customer}</td>
{/if}
	<td align="center">{$lng.lbl_product}</td>
	<td align="center">{$lng.lbl_order}</td>
	<td align="center">{$lng.lbl_date}</td>
	<td align="center">{$lng.lbl_status}</td>
{if $active_modules.Gift_Certificates ne '' && $usertype ne 'C'}
	{math assign="colspan" equation="x+1" x=$colspan}
	<td align="center">{$lng.lbl_credit_status}</td>
{/if}
</tr>
{foreach from=$returns item=v}
<tr{cycle values=', class="TableSubHead"'}>
	<td align="center"><input type="checkbox" name="to_delete[{$v.returnid}]" value="Y" /></td>
	<td align="right" valign="top"><a href="returns.php?mode=modify&amp;returnid={$v.returnid}">RMA#{$v.returnid}</a></td>
{if $usertype ne 'C'}
	<td valign="top"><a href="user_modify.php?user={$v.login|escape:"url"}&amp;usertype=C">{$v.firstname} {$v.lastname}</a></td>
{/if}
	<td valign="top">
{if $v.productid > 0}
<a href="{if $usertype eq 'C'}product.php{else}product_modify.php{/if}?productid={$v.productid}">{/if}{$v.product}{if $v.productid > 0}</a>
{/if}
{if $v.product_options ne "" && $active_modules.Product_Options ne ''}
<div style="padding-left: 20px;">
{include file="modules/Product_Options/display_options.tpl" options_txt=$v.product_options force_product_options_txt=true}
</div>
{/if}
	</td>
	<td align="right" valign="top"><a href="order.php?orderid={$v.orderid}">{$v.orderid}</a></td>
	<td nowrap="nowrap" valign="top">{$v.date|date_format:$config.Appearance.datetime_format}</td>
	<td valign="top">{if $usertype eq 'C'}
	{assign var="status" value=$v.status}
	{$statuses.$status}
	{else}<select name="update[{$v.returnid}][status]">
	{foreach from=$statuses item=s key=k}
	<option value='{$k}'{if $k eq $v.status} selected="selected"{/if}>{$s}</option>
	{/foreach}
	</select>
	{/if}
	</td>
{if $active_modules.Gift_Certificates ne '' && $usertype ne 'C'}
    <td valign="top" align="center">
	{if $v.credit ne ''}
	<a href="giftcerts.php?mode=modify_gc&amp;gcid={$v.credit}">{$lng.lbl_created}</a>
	{elseif $v.status eq 'A' || $v.status eq 'C'}
	{math assign="gc_amount" equation="x*y" x=$v.amount y=$v.price}
	<input type="text" id="gc_amount{$v.returnid}" value="{$gc_amount|formatprice}" size="8" /><br />
	<a href="javascript: self.location='returns.php?mode=credit_create&amp;returnid={$v.returnid}&amp;gc_amount='+document.getElementById('gc_amount{$v.returnid}').value;">{$lng.lbl_create}</a>
	{else}
	{$lng.lbl_creation_of_credit_forbidden}
	{/if}</td>
{/if}
</tr>
{/foreach}
{if $returns ne ''}
<tr>
	<td colspan="{$colspan}">
<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'delete');" />
{if $usertype ne 'C'}
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />
{/if}

<br /><br /><br />

{$lng.txt_operation_for_first_selected_only}

<br /><br />

<input type="button" value="{$lng.lbl_modify|strip_tags:false|escape}" onclick="document.returnsform.mode.value='modify'; document.returnsform.submit();" />
&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
</tr>
{/if}
</table>
</form>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_returns extra='width="100%"'}
{/if}

{elseif $mode eq 'reasons'}

{include file="modules/RMA/reasons.tpl"}

{elseif $mode eq 'actions'}

{include file="modules/RMA/actions.tpl"}

{elseif $mode eq 'modify'}
 
{include file="modules/RMA/modify_return.tpl"}

{/if}
