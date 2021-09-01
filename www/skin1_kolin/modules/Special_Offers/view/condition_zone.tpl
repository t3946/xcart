{* $Id: condition_zone.tpl,v 1.4 2005/12/07 14:07:32 max Exp $ *}

<table>
{foreach name=params from=$condition.params item=param}
{if $smarty.foreach.params.first ne 1}
<tr>
	<td align="center" style="TEXT-TRANSFORM: lowercase;">{$lng.lbl_sp_or}</td>
</tr>
{/if}
<tr>
	<td>{$param.zone_name} ({$lng.lbl_sp_zone_type}: {if $param.param_arg eq "B"}{$lng.lbl_sp_zone_billing}{else}{$lng.lbl_sp_zone_shipping}{/if})</td>
</tr>
{/foreach}
</table>
