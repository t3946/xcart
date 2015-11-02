<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Checks_deposited_options">
    <input type="hidden" name="mode" value="">

<table cellpadding="3" cellspacing="1" width="100%">

 <tr>
  <td width="50%">When there is a problem or inconsistency with the check deposited, set the following Attention
tag for the order:</td>
  <td>
	<select name="Checks_deposited_Attention_tag">
	<option value=""></option>
	{foreach from=$attention_tags_values item=v key=k}
		<option value="{$v.status_id}" {if $config.Checks_deposited_options.Checks_deposited_Attention_tag eq $v.status_id}selected="selected"{/if}>{$v.status}</option>
	{/foreach}
	</select>
  </td>
 </tr>

</table>

<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />

</form>
