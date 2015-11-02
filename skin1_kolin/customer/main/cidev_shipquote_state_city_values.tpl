<td colspan="3" width="515">
<table align="left" border="0" cellpadding="0" cellspacing="0">
	<tr style="height: 30px;">
        	<td align="right" width="200"><b>{$lng.lbl_state}</b></td>
                <td width="15">&nbsp;</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>{if $cidev_state_name ne ""}{$cidev_state_name}{else}{* not found *}{/if}</td>
		<td>{if $td_s_state_show_text ne ""}&nbsp;({/if}</td>
                <td nowrap="nowrap" align="left" id="td_s_state_show_text">{if $td_s_state_show_text ne ""}{$td_s_state_show_text}{/if}</td>
		<td>{if $td_s_state_show_text ne ""}){/if}</td>
	</tr>
        <tr style="">
		<td align="right"><b>{$lng.lbl_city}</b></td>
                <td>&nbsp;</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td nowrap="nowrap" align="left" id="td_s_city_show_text" colspan="4">{if $td_s_city_show_text ne ""}{$td_s_city_show_text}{else}{* not found *}{/if}</td>
	</tr>
</table>
<td>
