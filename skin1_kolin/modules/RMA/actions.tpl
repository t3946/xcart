{* $Id: actions.tpl,v 1.3.2.2 2006/07/11 08:39:33 svowl Exp $ *}
{capture name=dialog}
<form action="returns.php" method="post">
<input type="hidden" name="mode" value="actions" />
<table>
{foreach from=$actions item=v key=k}
<tr>
	<td><input type="text" name="posted_data[{$k}]" value="{$v}" size="32" /></td>
	<td><a href="returns.php?mode=actions_delete&amp;idx={$k}">{$lng.lbl_delete}</a></td>
</tr>
{/foreach}
<tr>
    <td>&nbsp;</td>
</tr>
<tr> 
	<td class="TopLabel" colspan="2">{include file="main/subheader.tpl" title=$lng.lbl_new_action}</td>
</tr>
<tr>
    <td><input type="text" name="new" value="" size="32" /></td>
</tr> 
<tr>
	<td><input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" /></td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_add_modify_actions extra='width="100%"'}
