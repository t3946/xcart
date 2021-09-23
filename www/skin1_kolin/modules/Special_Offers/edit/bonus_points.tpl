{* $Id: bonus_points.tpl,v 1.8 2006/04/10 07:36:18 max Exp $ *}
<table>
<tr>
	<td>
<script type="text/javascript">
<!--
var bonus_{$bonus.bonus_type}_enable = {ldelim}
	F: ["bonus_{$bonus.bonus_type}_amount_min_F"],
	S: ["bonus_{$bonus.bonus_type}_amount_max_S","bonus_{$bonus.bonus_type}_amount_min_S"]
{rdelim};

function bonus_{$bonus.bonus_type}_click(active) {ldelim}
	var rules = bonus_{$bonus.bonus_type}_enable;
{literal}
	for (var label in rules) {
		for (var idx in rules[label]) {
			if (document.getElementById(rules[label][idx]))
				document.getElementById(rules[label][idx]).disabled = (label != active);
		}
	}
{/literal}
{rdelim}
-->
</script>
<input id="bonus_{$bonus.bonus_type}_type_F" type="radio" name="bonus[{$bonus.bonus_type}][amount_type]" onclick="bonus_{$bonus.bonus_type}_click('F')" value="F"{if $bonus.amount_type ne "S"} checked="checked"{/if} />
	</td>
	<td colspan="5">
<label for="bonus_{$bonus.bonus_type}_type_F">{$lng.lbl_sp_points_fixed_amount}</label>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>{$lng.lbl_sp_points}:</td>
	<td><input type="text" size="5" name="bonus[{$bonus.bonus_type}][amount_min]" id="bonus_{$bonus.bonus_type}_amount_min_F" value="{$bonus.amount_min|string_format:"%d"}" /></td>
</tr>
<tr>
	<td>
<input id="bonus_{$bonus.bonus_type}_type_S" type="radio" name="bonus[{$bonus.bonus_type}][amount_type]" onclick="bonus_{$bonus.bonus_type}_click('S')" value="S"{if $bonus.amount_type eq "S"} CHECKED{/if} />
	</td>
	<td colspan="5">
<label for="bonus_{$bonus.bonus_type}_type_S">{$lng.lbl_sp_points_per_subtotal}</label>
	</td>
</tr>
<tr>
	 <td>&nbsp;</td>
	<td>{$lng.lbl_sp_points}:</td>
	<td><input type="text" size="5" name="bonus[{$bonus.bonus_type}][amount_min]" id="bonus_{$bonus.bonus_type}_amount_min_S" value="{$bonus.amount_min|string_format:"%d"}" /></td>
	<td>{$lng.lbl_sp_points_per_} {$config.General.currency_symbol}</td>
	<td><input type="text" size="5" name="bonus[{$bonus.bonus_type}][amount_max]" id="bonus_{$bonus.bonus_type}_amount_max_S" value="{$bonus.amount_max|string_format:"%d"}" /></td>
	<td>{$lng.lbl_sp_points_of_subtotal_}</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="5">
		<script type="text/javascript">
		<!--
		setTimeout("bonus_{$bonus.bonus_type}_click('{$bonus.amount_type}')", 100);
		-->
		</script>
		<input type="submit" value=" {$lng.lbl_update} ">
	</td>
</tr>
</table>
