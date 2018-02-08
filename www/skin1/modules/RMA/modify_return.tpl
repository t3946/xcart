{* $Id: modify_return.tpl,v 1.8.2.3 2006/07/11 08:39:33 svowl Exp $ *}
{capture name=dialog}
<div align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_returns_list href="returns.php"}</div>
<form action="returns.php" method="post">
<input type="hidden" name="returnid" value="{$returnid}" />
<input type="hidden" name="mode" value="modify" />
<table>
{if $usertype ne 'C'}
<tr>
	<td>{$lng.lbl_customer}</td>
	<td><a href="user_modify.php?user={$return.order.login|escape:"url"}&amp;usertype=C">{$return.order.firstname} {$return.order.lastname}</a></td>
</tr>
<tr>
	<td>{$lng.lbl_product}</td>
	<td>
{if $return.product.productid > 0}<a href="product_modify.php?productid={$return.product.productid}">{/if}
{$return.product.product}
{if $return.product.productid > 0}</a>{/if}
{if $return.product.product_options ne "" && $active_modules.Product_Options ne ''}
<div style="padding-left: 20px;">
{include file="modules/Product_Options/display_options.tpl" options=$return.product.product_options}
</div>
{/if}
	</td>
</tr>
<tr>
	<td>{$lng.lbl_order}</td>
	<td><a href="order.php?orderid={$return.order.orderid}">{$return.order.orderid}</a></td>
</tr>
<tr>
	<td>{$lng.lbl_date}</td>
	<td>{$return.date|date_format:$config.Appearance.datetime_format}</td>
</tr>
{/if}
{if $reasons ne ''}
<tr>
	<td>{$lng.lbl_reason_for_returning}</td>
	<td><select name="posted_data[reason]">
	{foreach from=$reasons item=v key=k}
	<option value='{$k}'{if $k eq $return.reason} selected="selected"{/if}>{$v}</option>
	{/foreach}
	</select></td>
</tr>
{/if}
{if $actions ne ''}
<tr> 
    <td>{$lng.lbl_what_you_would_like_us_to_do}</td>
    <td><select name="posted_data[action]">
    {foreach from=$actions item=v key=k} 
    <option value='{$k}'{if $k eq $return.action} selected="selected"{/if}>{$v}</option>
    {/foreach}
    </select></td>
</tr>
{/if}
<tr>
	<td>{$lng.lbl_comment}</td>
	<td><textarea rows="3" cols="60" name="posted_data[comment]">{$return.comment|escape}</textarea></td>
</tr>
{if $usertype ne 'C'}
<tr>  
    <td>{$lng.lbl_status}</td>
    <td><select name="posted_data[status]">
    {foreach from=$statuses item=v key=k}       
    <option value='{$k}'{if $k eq $return.status} selected="selected"{/if}>{$v}</option>
    {/foreach} 
    </select></td> 
</tr>
{/if}
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="{$lng.lbl_modify|strip_tags:false|escape}" />{if $return.status eq 'A' || $return.status eq 'C'}&nbsp;&nbsp;&nbsp;<input type="button" value="{$lng.lbl_print_return_slip|strip_tags:false|escape}" onclick="javascript: window.open('returns.php?mode=print&returnid={$return.returnid}','PRINT_RETURN_SLIP','width=350,height=300,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=yes,location=no,direction=no')" />{/if}</td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_modify extra='width="100%"'}
