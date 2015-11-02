
{$lng.lbl_templates_order_related_messages_top}

<table>
<tr>
<td>Templates for communicating to</td>
<td>

        <script type="text/javascript">
        <!--
                var lbl_add = '{$lng.lbl_add|escape}';
                var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
                var ImagesDir = '{$ImagesDir}';
                var row_max_index = {$row_max_index};
        -->
        </script>

{include file="main/include_js.tpl" src="main/manage_templates_for_communication.js"}

{if $department_arr ne ""}
<form name="osnotificform" action="configuration.php" method="GET">
    <input type="hidden" name="option" value="Templates_OrderRelatedMessages">
    <select name="department" onchange="javascript: document.osnotificform.submit();">
	<option value="">Please select</option>
    	{foreach from=$department_arr item=item key=key}
	<option value="{$key}"{if $department eq $key} selected="selected"{/if}>{$item}</option>
	{/foreach}
    </select>
</form>
</td>
<td>
<a href="javascript: void(0);" onclick="javascript: add_template('{$row_max_index}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
</td>
</tr>
</table>
{/if}

<br />
<br />
{if $department_arr[$department] ne ""}
Selected: {$department_arr[$department]}
{/if}
<br />

<form name="osnotificform2" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Templates_OrderRelatedMessages">
    <input type="hidden" name="department" value="{$department}">
    <input type="hidden" name="mode" value="update_department">

<table width="100%">
<tr {if $templates_for_communication eq ""}style="display: none;"{/if} id="tr_header_row">
	<td width="10"><b>Active</b></td>
	<td width="4%" align="center"><b>Pos.</b></td>
	<td width="30%"><b>Email settings</b></td>
{*
	<td width="20%"><b>'Send to' email</b></td>
	<td width="20%"><b>Subject line</b></td>
*}
	<td width="*"><b>Message body</b></td>
	<td width="20"></td>
</tr>

        {if $templates_for_communication}
                {foreach from=$templates_for_communication item="template_item" key=key name="depforeach"}

                <tr id="template_row{$key}">

<td valign="top" align="center" width="10">
<input type="checkbox" name="templates_for_communication[{$key}][active]" value="Y" {if $template_item.active eq "Y"}checked="checked"{/if} style="padding: 0px; margin: -2px 0 0 0;" />
</td>

<td valign="top" align="center" width="4%">
<input type="text"  name="templates_for_communication[{$key}][pos]" value="{$template_item.pos}" size="2" />
</td>

<td valign="top" align="center" width="30%">

<table width="100%">
<tr><td><b>Template name</b></td></tr>
<tr><td><input type="text"  name="templates_for_communication[{$key}][template_name]" value="{$template_item.template_name}" style="width: 96%;" /></td></tr>
<tr><td><b>'Send to' email</b></td></tr>
<tr><td><input type="text"  name="templates_for_communication[{$key}][send_to_email]" value="{$template_item.send_to_email}" style="width: 96%;" /></td></tr>
<tr><td><b>Subject line</b></td></tr>
<tr><td><input type="text"  name="templates_for_communication[{$key}][subject_line]" value="{$template_item.subject_line}" style="width: 96%;" /></td></tr>
</table>

</td>

{*
<td valign="top" align="center" width="20%">
<input type="text"  name="templates_for_communication[{$key}][send_to_email]" value="{$template_item.send_to_email}" style="width: 96%;" />
</td>

<td valign="top" align="center" width="20%">
<input type="text"  name="templates_for_communication[{$key}][subject_line]" value="{$template_item.subject_line}" style="width: 96%;" />
</td>
*}

<td valign="top" align="center" width="*">
<textarea cols="45" rows="8" name="templates_for_communication[{$key}][message_body]" style="width: 96%;" />{$template_item.message_body}</textarea>
</td>

<td valign="top" align="center" width="20">
<a href="javascript: void(0);" onclick="javascript: remove_template('{$key}');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>
</td>
                </tr>

                {/foreach}
        {/if}

                <tr id="template_row{$row_max_index}"><td colspan="7"></td></tr>
		

<tr {if $templates_for_communication eq ""}style="display: none;"{/if} id="tr_submit_row">
	<td colspan="7"><input type="submit" name="Save" value="Save" /></td>
</tr>

</table>
</form>
