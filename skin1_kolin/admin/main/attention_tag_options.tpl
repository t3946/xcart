<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Attention_tag_options">
    <input type="hidden" name="mode" value="">

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
        <td width="10">Tag ID</td>
        <td width="5%">{$lng.lbl_pos}</td>
        <td width="*">Tag name / Description</td>
        <td width="10%">Active <br> Event trigger <br> Color </td>
        <td width="40%">Login / action</td>
</tr>

{section name=pg loop=$attention_tags_values}
<tr{cycle name="embed" values=", class='TableSubHead'"}>
<td align="center">
	{$attention_tags_values[pg].status_id}
	<input type="hidden" name="posted_data[{$attention_tags_values[pg].status_id}][status_id]" value="{$attention_tags_values[pg].status_id}" />
</td>
<td align="center"><input type="text" name="posted_data[{$attention_tags_values[pg].status_id}][orderby]" value="{$attention_tags_values[pg].orderby}" size="5" style="width: 90%;" /></td>
<td align="center">
    <input type="text" name="posted_data[{$attention_tags_values[pg].status_id}][status]" value="{$attention_tags_values[pg].status|escape}" size="15" style="width: 96%;" />
    <textarea name="posted_data[{$attention_tags_values[pg].status_id}][description]" style="height: 44px;width: 96%;">{$attention_tags_values[pg].description|escape}</textarea>
</td>
<td align="center">
    <select name="posted_data[{$attention_tags_values[pg].status_id}][active]" style="width: 100px;">
        <option value="Y"{if $attention_tags_values[pg].active eq "Y"} selected="selected"{/if}>{$lng.lbl_enabled}</option>
        <option value="N"{if $attention_tags_values[pg].active eq "N"} selected="selected"{/if}>{$lng.lbl_disabled}</option>
    </select>
    <select name="posted_data[{$attention_tags_values[pg].status_id}][events]" style="width: 100px;">
        <option value="0"{if $attention_tags_values[pg].events eq "0"} selected="selected"{/if}>None</option>
        <option value="1"{if $attention_tags_values[pg].events eq "1"} selected="selected"{/if}>Trigger</option>
    </select>
    <input type="color" name="posted_data[{$attention_tags_values[pg].status_id}][color]" value="{$attention_tags_values[pg].color}">
</td>
<td nowrap="nowrap">
  {if $attention_tags_values[pg].operators ne ""}
	{foreach from=$attention_tags_values[pg].operators item=vv key=kk}
	 <input type="checkbox" name="posted_data[{$attention_tags_values[pg].status_id}][delete_operators][{$vv.id}]" value="Y" />
	 {$vv.login|replace:'_ANY_':'Any'} - {$vv.action}<br />
	{/foreach}
  {/if}

  Add:
  <select name="posted_data[{$attention_tags_values[pg].status_id}][select_login]">
	<option value=""></option>
	<option value="_ANY_">Any</option>
	{foreach from=$allowed_operators item=v key=k}
	<option value="{$v.login}">{$v.firstname} ({$v.login})</option>
	{/foreach}
  </select>

  <select name="posted_data[{$attention_tags_values[pg].status_id}][select_action]">
	<option value="">Select action</option>
	<option value="set">set</option>
	<option value="unset">unset</option>
  </select>
</td>
</tr>
{/section}

<tr>
        <td colspan="3" class="SubmitBox">
            <input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />
	</td>
        <td colspan="2" class="SubmitBox" align="right">
            <input type="button" value="Add new attention tag" onclick="javascript: submitForm(this, 'add');" />
        </td>
</tr>

</table>

</form>
