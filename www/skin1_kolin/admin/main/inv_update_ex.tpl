{* $Id: inv_update.tpl,v 1.10.2.2 2006/07/11 08:39:37 dem Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_update_inventory}

{capture name=dialog1}

<div>SKU; Quantity; Price; List-Price; Weight</div>

<form id="uploadform" method="post" action="inv_update_ex.php" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="4" width="100%">
<tr>
	<td>{$lng.lbl_csv_delimiter}</td>
	<td>{include file="provider/main/ie_delimiter.tpl"}</td>
</tr>
<tr>
	<td>{$lng.lbl_csv_file}</td>
	<td><input type="file" name="userfile" />
{if $upload_max_filesize}
<br /><font class="Star">{$lng.lbl_warning}!</font> {$lng.txt_max_file_size_that_can_be_uploaded}: {$upload_max_filesize}b.
{/if} 
	</td>
</tr>

<tr>
<td>Compare to</td>
<td>{include file="admin/main/menu_manufacturers2.tpl" }</td>
</tr>
<tr>
	{*<td class="SubmitBox"><input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" /></td>*}
{*<td style="padding-top: 10px;">{include file="buttons/button.tpl" button_title="Upload" style="button" href="javascript: document.uploadform.submit();"}</td>*}

<input type="hidden" id="modeex" name="modeex" value="0" />
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
function submitMode(mv) {

	document.getElementById('modeex').value = mv;

	document.getElementById('uploadform').submit();

	return true;
}
{/literal}
-->
</script>

<td><input type="button" OnClick="submitMode('1')" value="Upload" /></td>
<td><input type="button" OnClick="submitMode('2')" value="Compare" /></td>

</tr>

</table>
</form>

<form name="uploadform" method="post" action="inv_update_ex.php" enctype="multipart/form-data">
<input type="hidden" id="mode" name="mode" value="0" />


{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog1 title=$lng.lbl_update_inventory extra='width="100%"'}

<p />

{capture name=dialog2}

<table width="100%" cellspacing="0" cellpadding="0">

<tr>

<td style="border: 1px solid silver; padding: 10px;" width="49%" valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td align="center"><font style="color: #330000; font-weight: bold;">New products</font></td></tr>
{foreach from=$newprods item=pid}
{if $pid ne ''}
<tr><td>{$pid}</td></tr>
{/if}
{/foreach}
</table>
</td>
<td width="1%"></td>
<td style="border: 1px solid silver; padding: 10px;" width="49%" valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td align="center"><font style="color: #330000; font-weight: bold;">Discontinued products</font></td></tr>
{foreach from=$discprods item=pid}
{if $pid ne ''}
<tr><td>{$pid}</td></tr>
{/if}
{/foreach}
</table>
</td>

</tr>
</table>

{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog2 title="Comparative table" extra='width="100%"'}
