<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="OTRS_options">
    <input type="hidden" name="mode" value="">

<table cellpadding="3" cellspacing="1" width="100%">

 <tr>
  <td width="40%">OTRS passphrase:</td>
  <td>
<input type="text" name="OTRS_passphrase" value="{$otrs_options.OTRS_passphrase}" style="width: 98%;" />
  </td>
 </tr>

 <tr>
  <td colspan="2">New mail notification options</td>
 </tr>

 <tr>
  <td>Order 'Attention tag' to add:</td>
  <td>
{if $attention_tags_values ne ""}
  <select name="status_id">
        <option value="0"></option>
	{foreach from=$attention_tags_values item=v key=k}
        <option value="{$v.status_id}" {if $otrs_options.status_id eq $v.status_id}selected="selected"{/if}>{$v.status}</option>
	{/foreach}
  </select>
{/if}
  </td>
 </tr>

</table>

<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />

</form>
