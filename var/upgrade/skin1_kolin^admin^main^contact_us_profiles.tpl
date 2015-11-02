{* $Id: contact_us_profiles.tpl,v 1.6.2.2 2006/07/11 08:39:26 svowl Exp $ *}

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
<td>

{if $js_enabled}
	{include file="check_email_script.tpl"}
{/if}

<form action="configuration.php" method="post">
<input type="hidden" name="option" value="{$option}" />
<input type="hidden" name="mode" value="update_status" />

<table cellpadding="3" cellspacing="1" width="100%">


<tr class="TableHead">
	<td rowspan="2" width="30%" nowrap="nowrap">{$lng.lbl_field_name}</td>
{foreach from=$usertypes_array item=to_disable key=utype}
	<td align="center">
	{if $utype eq "B"}{$lng.lbl_partner}{elseif $utype eq "P"}{$lng.lbl_provider}{else}{$lng.lbl_customer}{/if}
	</td>
{/foreach}
</tr>

{math equation="floor(80/x)" x=$usertypes_array_count assign="col_width"}

<tr class="TableHeadLevel2">
{foreach from=$usertypes_array item=to_disable key=utype}
	<td width="{$col_width}%" align="center" nowrap="nowrap">{$lng.lbl_active} / {$lng.lbl_required}</td>
{/foreach}
</tr>

{math equation="x*2+1" x=$usertypes_array_count assign="colspan"}

{foreach from=$default_fields item=item key=field}

<tr{cycle values=", class='TableSubHead'"}>
	<td>
	{$item.title}
	<input type="hidden" name="default_data[{$item.field}][flag]" value="Y" />
	</td>
{foreach from=$usertypes_array item=to_disable key=utype}
	<td align="center">
	<input type="checkbox" id="da_{$item.field}_{$utype}" onclick="javascript: document.getElementById('dr_{$item.field}_{$utype}').disabled = !this.checked; if('{$item.field}' == 'department') {ldelim}$('#dr_{$item.field}_{$utype}').attr('checked', $('#da_{$item.field}_{$utype}').attr('checked'));{rdelim}" name="default_data[{$item.field}][avail][{$utype}]"{if $item.avail.$utype eq "Y"} checked="checked"{/if} />
	&nbsp;/&nbsp;
	<input type="checkbox" id="dr_{$item.field}_{$utype}" name="default_data[{$item.field}][required][{$utype}]"{if $item.required.$utype eq "Y"} checked="checked"{/if}{if $item.avail.$utype ne "Y"} disabled="disabled"{/if} onclick="javascript:if('{$item.field}' == 'department') {ldelim}$('#da_{$item.field}_{$utype}').attr('checked', $('#dr_{$item.field}_{$utype}').attr('checked')); document.getElementById('dr_{$item.field}_{$utype}').disabled = !document.getElementById('da_{$item.field}_{$utype}').checked{rdelim}" />
	</td>
{/foreach}
</tr>

{if $item.field eq 'department'}

<tr>
	<td colspan="3">
	<br />
	<br />

	<script type="text/javascript">
	<!--
		var lbl_department = '{$lng.lbl_department}';
		var lbl_email = '{$lng.lbl_email}';
		var lbl_add = '{$lng.lbl_add|escape}';
		var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
		var ImagesDir = '{$ImagesDir}';
		{if $qdeps}
			var row_max_index = {$qdeps};
		{else}
			var row_max_index = 1;
		{/if}
	-->
	</script>
	{include file="main/include_js.tpl" src="admin/main/manage_departments.js"}

	<table cellpadding="0" cellspacing="0">
	{if $departments}
		{foreach from=$departments item="department" key=key name="depforeach"}
		
		<tr id="dep_{$key}">
			<td style="padding: 2px 0px;">
				{$lng.lbl_department|cat:":"}&nbsp;<input type="text" size="45" name="deps[{$key}][name]" value="{$department.name|escape}" />&nbsp;&nbsp;&nbsp;{$lng.lbl_email|cat:":"}&nbsp;<input type="text" size="60" name="deps[{$key}][email]" value="{$department.email|escape}" onchange="javascript: checkEmailAddress(this);" />&nbsp;<a href="javascript: void(0);" onclick="javascript: add_department_row('{$key}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>{if !$smarty.foreach.depforeach.first}&nbsp;<a href="javascript: void(0);" onclick="javascript: remove_department_row('{$key}');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>{/if}
			</td>
		</tr>
		
		{/foreach}
	{else}
		<tr id="dep_1">
			<td style="padding: 2px 0px;">
				{$lng.lbl_department|cat:":"}&nbsp;<input type="text" size="45" name="deps[1][name]" value="" />&nbsp;&nbsp;&nbsp;{$lng.lbl_email|cat:":"}&nbsp;<input type="text" size="60" name="deps[1][email]" value="" onchange="javascript: checkEmailAddress(this);" />&nbsp;<a href="javascript: void(0);" onclick="javascript: add_department_row(1);"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
			</td>
		</tr>
	{/if}

	</table>

	</td>
</tr>
{/if}

{/foreach}

{if $additional_fields ne ''}
<tr> 
	<td colspan="{$colspan}"><br />{include file="main/subheader.tpl" title=$lng.lbl_additional_information class="grey"}</td>
</tr> 
{foreach from=$additional_fields item=v key=k}
<tr{cycle values=", class='TableSubHead'"}>
	<td>{$v.title|default:$v.field}</td>
{foreach from=$usertypes_array item=to_disable key=utype}
	<td align="center">	
	<input type="checkbox" onclick="javascript: document.getElementById('ar_{$v.fieldid}_{$utype}').disabled = !this.checked;" name="add_data[{$v.fieldid}][avail][{$utype}]"{if $v.avail.$utype eq "Y"} checked="checked"{/if} />
	&nbsp;/&nbsp;
	<input id="ar_{$v.fieldid}_{$utype}" type="checkbox" name="add_data[{$v.fieldid}][required][{$utype}]"{if $v.required.$utype eq "Y"} checked="checked"{/if}{if $v.avail.$utype ne "Y"} disabled="disabled"{/if} />
	</td>
{/foreach}
</tr>
{/foreach}
{/if}

<tr>
	<td colspan="{$colspan}"><br />
	<input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} " />
	</td>
</tr>

</table>
</form>
<br /><br />

<form action="configuration.php" method="post" name="fieldsform">
<input type="hidden" name="option" value="{$option}" />
<input type="hidden" name="mode" value="update_fields" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td colspan="4"><br />{include file="main/subheader.tpl" title=$lng.lbl_additional_fields}</td>
</tr>

<tr class="TableHead">
	<td>&nbsp;</td>
	<td nowrap="nowrap">{$lng.lbl_field_name}</td>
	<td>{$lng.lbl_type}</td>
	<td nowrap="nowrap">{$lng.lbl_pos}</td>
</tr>

{if $additional_fields}
{foreach from=$additional_fields item=v}
<tr>
	<td><input type="checkbox" name="fields[{$v.fieldid}]" value="Y" /></td>
	<td><input type="text" size="30" maxlength="100" name="update[{$v.fieldid}][field]" value="{$v.title|default:$v.field}" /></td>
	<td><select name="update[{$v.fieldid}][type]">
	{foreach from=$types item=t key=k}
	<option value="{$k}"{if $v.type eq $k} selected="selected"{/if}>{$t}</option>
	{/foreach}
	</select></td>
	<td><input type="text" name="update[{$v.fieldid}][orderby]" value="{$v.orderby}" size="5" /></td>
</tr>
{if $v.type eq 'S'}
<tr>
    <td>&nbsp;</td>
    <td colspan="3"><input type="text" size="60" name="update[{$v.fieldid}][variants]" value="{$v.variants}" /></td>
</tr>
{/if}
{/foreach}

<tr>
	<td colspan="4"><br />
	<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript:document.fieldsform.mode.value='delete';document.fieldsform.submit();" />
	</td>
</tr>

{else}

<tr>
	<td colspan="4" align="center">{$lng.txt_no_additional_fields}</td>
</tr>

{/if}

<tr>
	<td colspan="4"><br />{include file="main/subheader.tpl" title=$lng.lbl_add_new_field}</td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td><input type="text" name="newfield" size="30" maxlength="100" /></td>
	<td>
	<select name="newfield_type">
	{foreach from=$types item=v key=k}
	<option value="{$k}">{$v}</option>
	{/foreach}
	</select>
	</td>
	<td><input type="text" size="5" name="newfield_orderby" /></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td colspan="3">{$lng.lbl_variants_for_selectbox}:</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="3"><input type="text" size="60" name="newfield_variants" /></td>
</tr> 



<tr>
	<td colspan="4"><br />
	<input type="submit" value="{$lng.lbl_add_update|strip_tags:false|escape}" />
	</td>
</tr>

</table>
</form>

</td>
</tr>
</table>

