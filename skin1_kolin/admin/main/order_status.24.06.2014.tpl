{include file="page_title.tpl" title="Order statuses"}

{capture name=dialog}

<script type="text/javascript" language="JavaScript 1.2">
<!--
function mark_all_e(status) {ldelim}
    var fieldname;
{section name=pg loop=$order_statuses}
    fieldname = 'posted_data['+'{$order_statuses[pg].code}'+'][to_delete]';
    document.order_statusesform.elements[fieldname].checked = status;
{/section}
{rdelim}

-->
</script>

<form action="order_statuses.php" method="post" name="order_statusesform">
<input type="hidden" name="mode" value="update" />

<table cellpadding="3" cellspacing="1" align="center">

{if $order_statuses}

{capture name=embedorder_statuses}

{section name=pg loop=$order_statuses}

<tr{cycle name="embed" values=", class='TableSubHead'"}>
<td align="center"><input type="checkbox" name="posted_data[{$order_statuses[pg].code}][to_delete]" value="{$order_statuses[pg].code}" /></td>
<td align="center"><input type="text" name="posted_data[{$order_statuses[pg].code}][orderby]" value="{$order_statuses[pg].orderby}" size="2" /></td>
<td align="center"><input type="text" name="posted_data[{$order_statuses[pg].code}][code]" value="{$order_statuses[pg].code}" size="2" readonly="readonly" /></td>
<td align="center"><input type="text" name="posted_data[{$order_statuses[pg].code}][name]" value="{$order_statuses[pg].name}" size="25" /></td>
</tr>

{/section}

{/capture}

{/if}

<tr>
<td colspan="5"><div style="line-height: 170%;"><a href="javascript: mark_all_e(true);">{$lng.lbl_check_all}</a> / <a href="javascript: mark_all_e(false);">{$lng.lbl_uncheck_all}</a></div></td>
</tr>

<tr class="TableHead">
        <td width="10">&nbsp;</td>
        <td width="100">{$lng.lbl_pos}</td>
        <td width="100">Code</td>
        <td width="200">{$lng.lbl_status}</td>
</tr>

{$smarty.capture.embedorder_statuses}

<tr>
        <td colspan="4" class="SubmitBox">
        <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: if (confirm('Delete?')) submitForm(this, 'delete');" />
        <input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />
        </td>
</tr>

</table>
</form>

<br />
        <B>Add new status</B>
        <hr size="1" noshade="noshade" />
<br />

<form action="order_statuses.php" method="post" name="order_statusesform2">
<input type="hidden" name="mode" value="add" />

<table cellpadding="3" cellspacing="1" align="center">

<tr class="TableHead">
        <td width="10">&nbsp;</td>
        <td width="100">{$lng.lbl_pos}</td>
        <td width="100">Code</td>
        <td width="200">{$lng.lbl_status}</td>
</tr>

<tr>
        <td width="10">&nbsp;</td>
        <td align="center" width="100"><input type="text" name="orderby" value="" size="2" /></td>
        <td align="center" width="100"><input type="text" name="code" value="" size="2" maxlength="2" /></td>
        <td align="center" width="200"><input type="text" name="name" value="" size="25" /></td>
</tr>

<tr>
        <td colspan="4" class="SubmitBox">
	        <input type="submit" value="Add" name="Add" />
        </td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title="Order statuses" content=$smarty.capture.dialog extra='width="100%"'}

