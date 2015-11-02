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
    <td>{$lng.lbl_pos}</td>
</tr>

{math equation="floor(80/x)" x=$usertypes_array_count assign="col_width"}

<tr class="TableHeadLevel2">
{foreach from=$usertypes_array item=to_disable key=utype}
	<td width="{$col_width}%" align="center" nowrap="nowrap">{$lng.lbl_active} / {$lng.lbl_required}</td>
{/foreach}
    <td>&nbsp;</td>
</tr>

{math equation="x*2+2" x=$usertypes_array_count assign="colspan"}

{foreach from=$all_fields item=item key=key}

<tr{cycle values=", class='TableSubHead'"}>
	<td>
	{$item.title}
	<input type="hidden" name="data[{$item.ftype}][{$item.field}][flag]" value="Y" />
	</td>
{foreach from=$usertypes_array item=to_disable key=utype}
	<td align="center">
	<input type="checkbox" id="da_{$item.ftype}_{$item.field}_{$utype}" onclick="javascript: document.getElementById('dr_{$item.ftype}_{$item.field}_{$utype}').disabled = !this.checked; if('{$item.field}' == 'department') {ldelim}$('#dr_{$item.ftype}_{$item.field}_{$utype}').attr('checked', $('#da_{$item.ftype}_{$item.field}_{$utype}').attr('checked'));{rdelim}" name="data[{$item.ftype}][{$item.field}][avail][{$utype}]"{if $item.avail.$utype eq "Y"} checked="checked"{/if} />
	&nbsp;/&nbsp;
	<input type="checkbox" id="dr_{$item.ftype}_{$item.field}_{$utype}" name="data[{$item.ftype}][{$item.field}][required][{$utype}]"{if $item.required.$utype eq "Y"} checked="checked"{/if}{if $item.avail.$utype ne "Y"} disabled="disabled"{/if} onclick="javascript:if('{$item.field}' == 'department') {ldelim}$('#da_{$item.ftype}_{$item.field}_{$utype}').attr('checked', $('#dr_{$item.ftype}_{$item.field}_{$utype}').attr('checked')); document.getElementById('dr_{$item.ftype}_{$item.field}_{$utype}').disabled = !document.getElementById('da_{$item.ftype}_{$item.field}_{$utype}').checked{rdelim}" />
	</td>
{/foreach}
    <td><input type="text" name="data[{$item.ftype}][{$item.field}][orderby]" value="{$item.orderby}" size="4" /></td>
</tr>

{if $item.field eq 'department'}

<tr>
	<td colspan="{$colspan}">
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
				{$lng.lbl_department|cat:":"}&nbsp;<input {if $department.frozen eq "Y"}readonly="readonly"{/if} type="text" size="30" name="deps[{$key}][name]" value="{$department.name|escape}" {if $department.frozen eq "Y"}style="background-color: #cccccc;"{else}style="background-color: #ffffff;"{/if} />&nbsp;&nbsp;&nbsp;{$lng.lbl_email|cat:":"}&nbsp;<input {if $department.frozen eq "Y"}readonly="readonly"{/if} type="text" size="30" name="deps[{$key}][email]" value="{$department.email|escape}" onchange="javascript: checkEmailAddress(this);" {if $department.frozen eq "Y"}style="background-color: #cccccc;"{else}style="background-color: #ffffff;"{/if} />&nbsp;<a href="javascript: void(0);" onclick="javascript: add_department_row('{$key}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
{* {if !$smarty.foreach.depforeach.first} *}
&nbsp;<a href="javascript: void(0);" {if $department.frozen ne "Y"} onclick="javascript: remove_department_row('{$key}');"{else}onclick="javascript: alert('You cannot delete it.');"{/if}><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>
{* {/if} *}

&nbsp;<B>Frozen</B> <input type="checkbox" name="deps[{$key}][frozen]" {if $department.frozen eq "Y"}checked="checked"{/if} value="Y" disabled="disabled" />



			</td>
		</tr>
		
		{/foreach}
	{else}
		<tr id="dep_1">
			<td style="padding: 2px 0px;">
				{$lng.lbl_department|cat:":"}&nbsp;<input type="text" size="30" name="deps[1][name]" value="" />&nbsp;&nbsp;&nbsp;{$lng.lbl_email|cat:":"}&nbsp;<input type="text" size="30" name="deps[1][email]" value="" onchange="javascript: checkEmailAddress(this);" />&nbsp;<a href="javascript: void(0);" onclick="javascript: add_department_row(1);"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>

&nbsp;<B>Frozen</B> <input type="checkbox" name="deps[1][frozen]" value="Y" disabled="disabled" />


			</td>
		</tr>
	{/if}

	</table>

	</td>
</tr>
{/if}

{/foreach}

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

