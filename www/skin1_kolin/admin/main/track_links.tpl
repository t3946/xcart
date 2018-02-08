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
        <td width="10%">Carrier</td>
	<td width="*">{$lng.lbl_tracking_link}</td>

	<td width="15%">Phone</td>
	<td width="15">&nbsp;</td>
	<td width="5%">{$lng.lbl_pos}</td>
	<td width="25%">Shipping method</td>
</tr>

{if $links ne ''}
{foreach from=$links item=carrier key=k_carrier}

  <tr>
   <td><input type="checkbox" name="carrier_ids[]" value="{$carrier.carrier_id}" /></td>
   <td><input type="text" maxlength="32" name="data[{$carrier.carrier_id}][carrier_orderby]" value="{$carrier.orderby|escape}" style="width: 80%;" /></td>
   <td><input type="text" maxlength="255" name="data[{$carrier.carrier_id}][carrier]" value="{$carrier.carrier|escape}" style="width: 90%;" /></td>
   <td><input type="text" maxlength="255" name="data[{$carrier.carrier_id}][link]" value="{$carrier.link|escape}" style="width: 96%;" /></td>
   <td><input type="text" maxlength="255" name="data[{$carrier.carrier_id}][phone]" value="{$carrier.phone|escape}" style="width: 92%;" /></td>
   <td colspan="3">&nbsp;</td>
  </tr>

	{if $carrier.shippings ne ""}
	{foreach from=$carrier.shippings item=v key=k}
	<tr{cycle values=', class="TableSubHead"'}>
		<td colspan="5">&nbsp;</td>
		<td><input type="checkbox" name="ids[]" value="{$v.linkid}" /></td>
		<td><input type="text" maxlength="32" name="data[{$carrier.carrier_id}][orderby][{$v.linkid}]" value="{$v.orderby|escape}" style="width: 80%;" /></td>
		<td><input type="text" maxlength="128" name="data[{$carrier.carrier_id}][shipping][{$v.linkid}]" value="{$v.shipping|escape}" style="width: 80%;" /></td>
	</tr>
	{/foreach}
	{else}
	<tr>
		<td colspan="5">&nbsp;</td>
		<td colspan="3" align="center">No shipping methods defined.</td>
	</tr>
	{/if}


{/foreach}

<tr>
	<td>&nbsp;</td>
	<td colspan="7" class="SubmitBox">
	<input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" />
	<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'delete');" />
	</td>
</tr>
{/if}

<tr>
	<td colspan="8"><br /><br /></td>
</tr>

<tr class="TableHead">
        <td width="15">&nbsp;</td>
        <td width="5%">{$lng.lbl_pos}</td>
        <td width="5%">Carrier</td>
        <td width="*">{$lng.lbl_tracking_link}</td>

        <td width="10%">Phone</td>
        <td width="15">&nbsp;</td>
        <td width="5%">{$lng.lbl_pos}</td>
        <td width="25%">Shipping method</td>
</tr>

<tr><td colspan="8">&nbsp;</td></tr> 

<tr>
        <td colspan="8">{include file="main/subheader.tpl" title="Add new carrier"}</td>
</tr>
<tr class="TableSubHead">
        <td>&nbsp;</td>
        <td><input type="text" maxlength="32" name="add_carrier[orderby]" value="" style="width: 80%;" /></td>
        <td><input type="text" maxlength="128" name="add_carrier[carrier]" value="" style="width: 90%;" /></td>
        <td><input type="text" maxlength="255" name="add_carrier[link]" value="" style="width: 96%;" /></td>
        <td><input type="text" maxlength="255" name="add_carrier[phone]" value="" style="width: 92%;" /></td>
	<td colspan="3"></td>
</tr>
<tr>
        <td colspan="8" align="center"><input type="button" value="{$lng.lbl_add|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'add_carrier');" /></td>
</tr>


<tr><td colspan="8">&nbsp;</td></tr>   


<tr>
	<td colspan="8">{include file="main/subheader.tpl" title="Add new shipping method"}</td>
</tr>
<tr class="TableSubHead">
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>
        <select name="add[carrier_id]" style="width: 100%;">
{if $links ne ''}
{foreach from=$links item=carrier key=k_carrier}
        <option value="{$carrier.carrier_id}">{$carrier.carrier}</option>
{/foreach}
{/if}
        </select>
	</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>

	<td>&nbsp;</td>
	<td><input type="text" maxlength="32" name="add[orderby]" value="" style="width: 80%;" /></td>
	<td><input type="text" maxlength="128" name="add[shipping]" value="" style="width: 80%;" /></td>
</tr>
<tr>
	<td colspan="8" align="center"><input type="button" value="{$lng.lbl_add|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'add');" /></td>
</tr>

</table>
</form>

<br />
{/capture}
{include file="dialog.tpl" title=$lng.lbl_tracking_links content=$smarty.capture.dialog extra='width="100%"'}
