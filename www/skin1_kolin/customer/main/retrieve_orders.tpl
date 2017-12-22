{* $Id: retrieve_orders.tpl,v 1.0 2010/10/08 12:58:00 kate Exp $ *}
<p />

{capture name=dialog}

{$lng.txt_retrieve_orders}

<br /><br />

<form action="retrieve_orders.php" method="post" name="retrieveordersform">
<input type="hidden" name="mode" value="retrieve_orders" />

<table cellpadding="0" cellspacing="0">
	<tr> 
		<td height="10" width="78" class="FormButton">{$lng.lbl_email}</td>
		<td width="10" height="10"><font class="CustomerMessage">*</font></td>
		<td width="282" height="10"> 
			<input type="text" name="email" size="30" value="{$smarty.get.email}" />
		</td>
	</tr>

	{if $smarty.get.section eq "retrieve_order_error"}
	<tr>
		<td width="78" class="FormButton" height="5">&nbsp;</td>
		<td width="10" height="5">&nbsp;</td>
		<td width="282" height="5" class="ErrorMessage">{$lng.txt_email_invalid}</td>
	</tr>
	{/if}

	<tr> 
		<td width="78" class="FormButton">&nbsp;</td>
		<td width="10">&nbsp;</td>
		<td width="282">{include file="buttons/submit.tpl" href="javascript: document.retrieveordersform.submit()" js_to_href="Y"}</td>
	</tr>
</table>

</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_retrieve_orders content=$smarty.capture.dialog extra='width="100%"'}
