<form name="reconform" action="configuration.php" method="POST">
<input type="hidden" name="option" value="Reconciliation">
<input type="hidden" id="Reconciliation_mode" name="mode" value="Update_Reconciliation">
<input type="hidden" id="Reconciliation_delete" name="Reconciliation_delete" value="">

{if $Reconciliations ne ""}
<table width="100%" cellpadding="3">
<tr>
	<td valign="top" nowrap="nowrap" align="left"><b>Code</b></td>
        <td valign="top" nowrap="nowrap" width="90%" align="left"><b>Search keyphrases to be dropped / Expense accounts</b></td>
        <td valign="top" nowrap="nowrap" width="*"><b>Delete</b></td>
</tr>

{foreach from=$Reconciliations item="r_item" key=key name="depforeach"}

<tr>

<td valign="top">
<input type="text"  name="Reconciliations[{$r_item.id}][code]" value="{$r_item.code}" size="3" maxlength="3" />
</td>

<td valign="top">
<input type="text"  name="Reconciliations[{$r_item.id}][search_keyphrase]" value="{$r_item.search_keyphrase}" style="width: 98%;" />
</td>

<td valign="top">
<input type="button" value="Delete" onclick="javascript: $('#Reconciliation_mode').val('Reconciliation_delete'); $('#Reconciliation_delete').val('{$r_item.id}'); document.reconform.submit();">
</td>
</tr>

{/foreach}
</table>
{/if}

<br />
<input type="submit" value=" Save ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Add new line" onclick="javascript: $('#Reconciliation_mode').val('Reconciliation_add'); document.reconform.submit();">
</form>
