{* track_links.tpl, random *}
{include file="page_title.tpl" title=$lng.lbl_tracking_links}

<br />

{$lng.txt_track_links_top_text}

<br /><br />

{capture name=dialog}

<form action="track_links.php" method="post" name="linksform">
<input type="hidden" name="mode" value="update" />

<table cellpadding="2" cellspacing="1" width="100%">
<tr class="TableHead">
	<td width="15">&nbsp;</td>
	<td width="5%">{$lng.lbl_pos}</td>
	<td width="25%">{$lng.lbl_shipper}</td>
	<td width="70%">{$lng.lbl_tracking_link}</td>
</tr>

{foreach from=$links item=v}
<tr{cycle values=', class="TableSubHead"'}>
	<td><input type="checkbox" name="ids[]" value="{$v.linkid}" /></td>
	<td><input type="text" maxlength="32" name="data[{$v.linkid}][orderby]" value="{$v.orderby|escape}" style="width: 80%;" /></td>
	<td><input type="text" maxlength="128" name="data[{$v.linkid}][shipping]" value="{$v.shipping|escape}" style="width: 80%;" /></td>
	<td><input type="text" maxlength="255" name="data[{$v.linkid}][link]" value="{$v.link|escape}" style="width: 100%;" /></td>
</tr>
{foreachelse}
<tr>
	<td colspan="5" align="center">{$lng.txt_no_links_defined}</td>
</tr>
{/foreach}
{if $links ne ''}
<tr>
	<td>&nbsp;</td>
	<td colspan="4" class="SubmitBox">
	<input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" />
	<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'delete');" />
	</td>
</tr>
{/if}

<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td colspan="5">{include file="main/subheader.tpl" title=$lng.lbl_add_new}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="text" maxlength="32" name="add[orderby]" value="" style="width: 80%;" /></td>
	<td><input type="text" maxlength="128" name="add[shipping]" value="" style="width: 80%;" /></td>
	<td><input type="text" maxlength="255" name="add[link]" value="" style="width: 100%;" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="4"><input type="button" value="{$lng.lbl_add|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'add');" /></td>
</tr>

</table>
</form>

<br />
{/capture}
{include file="dialog.tpl" title=$lng.lbl_tracking_links content=$smarty.capture.dialog extra='width="100%"'}
