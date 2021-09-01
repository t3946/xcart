Time zone          EST     

<table {* cellpadding="0" cellspacing="0" *} >

{foreach from=$working_days key=k_working_day item=v_working_day}
<tr>
<td>{$k_working_day|ucfirst}</td>
<td><input type="radio" name="working_hours_{$k_working_day}" value="non_working"{if $v_working_day.type eq 'non_working'} checked="checked"{/if} /></td>
<td>Non-working&nbsp;&nbsp;&nbsp;</td>
<td><input type="radio" name="working_hours_{$k_working_day}" value="all_day"{if $v_working_day.type eq 'all_day'} checked="checked"{/if} /></td>
<td>All day&nbsp;&nbsp;&nbsp;</td>
<td><input type="radio" name="working_hours_{$k_working_day}" value="custom"{if $v_working_day.type eq 'custom'} checked="checked"{/if} /></td>
<td>Custom</td>
<td>&nbsp;</td>
<td>From</td>
<td><input type="text" name="working_hours_{$k_working_day}_from" value="{$v_working_day.from}" size="5" /></td>
<td>to</td>
<td><input type="text" name="working_hours_{$k_working_day}_to" value="{$v_working_day.to}" size="5" /></td>
</tr>
{/foreach}

</table>
