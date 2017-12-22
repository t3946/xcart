{*$Id: hlp_tstlbl.tpl,v 1.1.2.3 2006/12/12 11:11:27 svowl Exp $*}
<table>
<tr>
	<td>
	{$lng.usps_labels_help_1}
	<br />
	<i>{$tmp_dir}</i>
	<br /><br />
	<form action="usps_test.php" method="post">
		<input type="submit" value="{$lng.lbl_get_usps_test_labels}"{if $config.Shipping_Label_Generator.usps_userid eq ''} disabled="disabled"{/if} /><br />
		{if $status eq 'E' and $error}
			<font color="#ff0000">
				{$lng.lbl_usps_labels_error}
				<br />
				{foreach from=$error item="error_txt" key="method"}
				<b>{$method}</b>: {$error_txt}<br />
				{/foreach}
			</font>
			
		{elseif $status eq 'S'}
			<font color="#087e27">{$lng.lbl_usps_labels_success}</font>
		{/if}
	</form>
	<br />
	{$lng.usps_labels_help_2}
	</td>	
</tr>
</table>
