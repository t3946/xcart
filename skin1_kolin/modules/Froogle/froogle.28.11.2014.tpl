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
<table cellspacing="5" cellpadding="0" width="100%">

<tr>
    <td width="50%" style="padding-bottom: 5px;">{$lng.lbl_froogle_select_language}</td>
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
		document.getElementById('check_cidev_exclude_product_without_img').style.display = '';
	}
	else {
		document.getElementById('cidev_froogle_file').value = "thefind.txt";
		document.getElementById('check_cidev_exclude_product_without_img').style.display = 'none';
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


<tr {if $cidev_feed_type eq "thefind"}style="display: none;"{/if} id="check_cidev_exclude_product_without_img">
  <td colspan="2">

	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
        <td width="50%">Do not include products without images</td>
        <td><input type="checkbox" id="cidev_exclude_product_without_img" name="cidev_exclude_product_without_img" value="Y" {if $cidev_exclude_product_without_img eq "Y"}checked="checked"{/if} /></td>
	</tr>

        <tr>
        <td>Maximum CPC-group value ($US):</td>
        <td>
		<input type="text" id="cidev_max_cpc_group" name="cidev_max_cpc_group" value="{$cidev_max_cpc_group|default:$config.Froogle.froogle_max_cpc_group_last_used}" size="6" />
	</td>
        </tr>

        <tr>
        <td>Number of clicks needed for conversion:</td>
        <td>
		<input type="text" id="cidev_number_clicks" name="cidev_number_clicks" value="{$cidev_number_clicks|default:$config.Froogle.froogle_number_clicks_last_used}" size="6" />
        </td>
        </tr>

	</table>
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
	<input type="button" value="Upload to Froogle" onclick="javascript: submitForm(this, 'fupload');" />
	{/if}
</td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_froogle_export content=$smarty.capture.dialog extra='width="100%"'}

