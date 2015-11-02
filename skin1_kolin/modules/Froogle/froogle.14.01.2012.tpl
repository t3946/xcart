{* $Id: froogle.tpl,v 1.7.2.6 2008/05/08 06:31:20 max Exp $ *}

{include file="page_title.tpl" title=$lng.lbl_froogle_export}

{$lng.txt_froogle_note}

<br /><br />

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->

<br />

{capture name=dialog}
{$lng.txt_froogle_format_note}
<br />
<br />

<form action="froogle.php" method="post" name="froogleform">
<input type="hidden" name="mode" value="fcreate" />
<table cellspacing="5" cellpadding="0">

<tr>
    <td style="padding-bottom: 5px;">{$lng.lbl_froogle_select_language}</td>
    <td>
{if $all_languages_cnt gt 1}
<select name="froogle_lng">
{foreach from=$all_languages item=l}
	<option value="{$l.code|escape}"{if $froogle_lng eq $l.code} selected="selected"{/if}>{$l.language}</option>
{/foreach}
</select>
{else}
{$all_languages.0.language}
{/if}
	</td>
</tr>
<tr>
	<td width="50%" style="padding-bottom: 5px;">{$lng.lbl_froogle_enter_language_code}</td>
	<td><input type="text" name="froogle_iso" value="{$froogle_iso|default:"en"}" maxlength="2" size="2" /></td>
</tr>

{* --- *}
<tr>
        <td>Feed type</td>
        <td>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
function update_froogle_file(){
	var cidev_feed_type = document.getElementById('cidev_feed_type').value;

	if (cidev_feed_type == "froogle_googlebase"){
		document.getElementById('cidev_froogle_file').value = "froogle.txt";
	}
	else {
		document.getElementById('cidev_froogle_file').value = "thefind.txt";
	}
}
{/literal}
-->
</script>


	<select id="cidev_feed_type" name="cidev_feed_type" onchange="javascript: update_froogle_file();">
		<option value="froogle_googlebase" {if $cidev_feed_type eq "froogle_googlebase"}selected="selected"{/if}>Froogle/GoogleBase</option>
		<option value="thefind" {if $cidev_feed_type eq "thefind"}selected="selected"{/if}>TheFind.com</option>
	</select>

	</td>
</tr>
{* --- *}

<tr>
	<td>{$lng.lbl_filename}</td>
	<td>{$cidev_sf_info_prefix}<input type="text" id="cidev_froogle_file" name="froogle_file" value="{$froogle_file|default:$def_froogle_file}" size="25" /></td>
</tr>
<tr>
	<td colspan="2" class="SubmitBox">
	<input type="button" value="{$lng.lbl_generate|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'fcreate');" />
	<input type="button" value="{$lng.lbl_download|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'fdownload');" />
	{if $is_ftp_module eq 'Y'}
	<input type="button" value="{$lng.lbl_upload|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'fupload');" />
	{/if}
</td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_froogle_export content=$smarty.capture.dialog extra='width="100%"'}

