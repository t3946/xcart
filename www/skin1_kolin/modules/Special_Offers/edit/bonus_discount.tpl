{* $Id: bonus_discount.tpl,v 1.9.2.3 2006/07/11 08:39:34 svowl Exp $ *}
<script type="text/javascript" language="JavaScript 1.2">
<!--
var currency_symbol = '{$config.General.currency_symbol|escape:javascript}';
{literal}
function discount_type_changed() {
	var type = document.getElementById('discount_type');
	if (type) {
		if (type.value == '%') {
			document.getElementById('da_primary').innerHTML = '%';
			document.getElementById('da_limit').innerHTML = currency_symbol;
		}
		else {
			document.getElementById('da_primary').innerHTML = currency_symbol;
			document.getElementById('da_limit').innerHTML = '%';
		}
	}
}
{/literal}
-->
</script>

<table cellpadding="3" cellspacing="1">
<tr>
	<td>{$lng.lbl_sp_bonus_discount_amount}:</td>
	<td>
    <input type="text" size="5" name="bonus[{$bonus.bonus_type}][amount_min]" value="{$bonus.amount_min|default:0|formatprice}" />
	<span id="da_primary">{if $bonus.amount_type eq "%"}%{else}{$config.General.currency_symbol}{/if}</span>
	</td>
</tr>
<tr>
	<td>{$lng.lbl_sp_bonus_discount_type}:</td>
	<td>
	<select id="discount_type" name="bonus[{$bonus.bonus_type}][amount_type]" onchange="javascript: discount_type_changed()">
		<option value="$"{if $bonus.amount_type eq "$"} selected="selected"{/if}>{$lng.lbl_absolute}</option>
		<option value="%"{if $bonus.amount_type eq "%"} selected="selected"{/if}>{$lng.lbl_percent}</option>
	</select>
</td>
</tr>
<tr>
	<td>{$lng.lbl_sp_bonus_discount_limit}:</td>
	<td>
<input type="text" size="5" name="bonus[{$bonus.bonus_type}][amount_max]" value="{$bonus.amount_max|default:0|formatprice}" />
<span id="da_limit">{if $bonus.amount_type eq "%"}{$config.General.currency_symbol}{else}%{/if}</span>
	</td>
</tr>

{if $bonus.params eq ""}
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" /></td>
</tr>
{/if}{* $bonus.params eq "" *}

</table>
<br />
{if $bonus.params eq ""}
<center>{$lng.txt_sp_empty_params_bonus_discount}</center>
{else}
{$lng.lbl_sp_bonus_apply_to_list}
{/if}
{include file="modules/Special_Offers/edit/product_n_category.tpl" params=$bonus.params mainid=$bonus.bonus_type form_name="wizardform"}
