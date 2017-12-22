{* $Id: shipping_rates.tpl,v 1.46.2.4 2006/10/25 06:39:36 max Exp $ *}

{if $type eq "D"}
{include file="page_title.tpl" title=$lng.lbl_shipping_charges}
{$lng.txt_shipping_charges_note|substitute:"weight_symbol":$config.General.weight_symbol}
{else}
{include file="page_title.tpl" title=$lng.lbl_shipping_markups}
{$lng.txt_shipping_markups_note|substitute:"weight_symbol":$config.General.weight_symbol}
{/if}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
function func_hide_show_real_drop_ship_fee(id_rate, id_real_drop_ship_fee){

	var rate_val = $('#'+id_rate).val();
	rate_val = parseFloat(rate_val);

	if (rate_val > 0){
		$('#'+id_real_drop_ship_fee).attr('readonly', false);
	}
	else {
		$('#'+id_real_drop_ship_fee).attr('readonly', true);
		$('#'+id_real_drop_ship_fee).val('0');
	}
}
{/literal}
//]]>
</script>


{* ------------------------- 
{if $cidev_marckups_less_than_n ne ""}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

  $(document).ready(function() {  

	$('#cidev_distibutors_link').click(function() {
		$('#cidev_distibutors_div').toggle('slow', function() {
		// Animation complete.
		});
	});


  });

{/literal}
//]]>
</script>


<a href="#" id="cidev_distibutors_link">Distibutors with less than {$cidev_marckup_nums} shipping markups defined</a>
<br />
<br />
<div id="cidev_distibutors_div" style="display: none;">
{capture name=dialog}
<table>
<tr>
<th align="center">Markups defined</th>
<th>Distributor</th>
</tr>
{foreach from=$cidev_marckups_less_than_n item=v key=k}
<tr>
<td align="center" width="40">{$v.count}</td>
<td><a href="manufacturers.php?manufacturerid={$v.manufacturerid}{if $smarty.get.type ne ""}&type={$smarty.get.type}{/if}">{$v.manufacturer}</a></td>
</tr>
{/foreach}
</table>
{/capture}
{include file="dialog.tpl" title="Distibutors with less than $cidev_marckup_nums shipping markups defined" content=$smarty.capture.dialog extra='width="100%"'}
<br />
<br />
</div>
{/if}
 ------------------------- *}


<form action="manufacturers.php" method="get" name="zoneform">

<input type="hidden" name="distributor_section" value="{$distributor_section}" />

<input type="hidden" name="type" value="{$type}" />

{*
<b>{if $type eq "D"}{$lng.lbl_edit_charges_for}{else}{$lng.lbl_edit_markups_for}{/if}</b><br />
*}

<table>
<tr>
{* {if $type eq "R"} *}
<td>
{*
&nbsp;<B>Distibutors:</B>
*}
</td>
{* {/if} *}
<td>&nbsp;<B>Shipping methods:</B></td>
<td>&nbsp;<B>Shipping zones:</B></td>
</tr>

<tr>

{* {if $type eq "R"} *}
<td>
{foreach from=$manufacturers item=v}
{if $smarty.get.manufacturerid eq $v.manufacturerid}
<input type="hidden" value='{$v.manufacturerid}' name="manufacturerid">
{* {$v.manufacturer} *}
{/if}
{/foreach}
</td>
{* {/if} *}

<td>
<select name="shippingid" onchange="document.zoneform.submit()">
	<option value="">{$lng.lbl_all_methods}</option>
{section name=ship_num loop=$shipping}
	<option value="{$shipping[ship_num].shippingid}"{if $smarty.get.shippingid ne "" and $smarty.get.shippingid eq $shipping[ship_num].shippingid} selected="selected"{/if}>{$shipping[ship_num].shipping|trademark} ({if $shipping[ship_num].destination eq "I"}{$lng.lbl_intl}{else}{$lng.lbl_national}{/if})</option>
{/section}
</select>
</td>

<td>
<select name="zoneid" onchange="document.zoneform.submit()">
	<option value="">{$lng.lbl_all_zones}</option>
{section name=zone loop=$zones}
	<option value="{$zones[zone].zoneid}"{if $smarty.get.zoneid ne "" and $smarty.get.zoneid eq $zones[zone].zoneid} selected="selected"{/if}>{$zones[zone].zone}</option>
{/section}
</select>
</td>

</tr>
</table>

</form>

<br /><br />

{capture name=dialog}

{if $shipping_rates_avail gt 0}

<script type="text/javascript" language="JavaScript 1.2">
<!--
checkboxes_form = 'shippingratesform';
checkboxes = new Array({section name=zone loop=$zones_list}{foreach key=shipid item=shipping_method from=$zones_list[zone].shipping_methods}{if $comma ne ""},{else}{assign var="comma" value=1}{/if}'sm_{$zones_list[zone].zone.zoneid}_{$shipid}'{section name=rate loop=$shipping_method.rates},'posted_data[{$shipping_method.rates[rate].rateid}][to_delete]'{/section}{/foreach}{/section});
-->  
</script> 
{include file="main/include_js.tpl" src="change_all_checkboxes.js"}

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td><div style="line-height:170%"><a href="javascript:change_all(true);">{$lng.lbl_check_all}</a> / <a href="javascript:change_all(false);">{$lng.lbl_uncheck_all}</a></div></td>
	<td align="right">
{if $type eq "D"}{include file="buttons/button.tpl" button_title=$lng.lbl_add_shipping_charge_values href="#addrate"}{else}{include file="buttons/button.tpl" button_title=$lng.lbl_add_shipping_markup_values href="#addrate"}{/if}
	</td>
</tr>
</table>

<br /><br />

<form action="manufacturers.php" method="post" name="shippingratesform">

<input type="hidden" name="distributor_section" value="{$distributor_section}" />
<input type="hidden" name="mode" value="update" />
<input type="hidden" name="zoneid" value="{$smarty.get.zoneid|escape:"html"}" />
<input type="hidden" name="shippingid" value="{$smarty.get.shippingid|escape:"html"}" />
<input type="hidden" name="manufacturerid" value="{$smarty.get.manufacturerid|escape:"html"}" />
<input type="hidden" name="type" value="{$type}" />

<table cellpadding="0" cellspacing="1" width="100%">

{* $zones_list = array("zone"=>array(...), "shipping_methods"=>array(...)) *}
{section name=zone loop=$zones_list}

{if $zones_list[zone].shipping_methods}

<tr>
	<td>{include file="main/subheader.tpl" title=$zones_list[zone].zone.zone class="black"}</td>
</tr>

{capture name=rates_list}
{foreach key=shipid item=shipping_method from=$zones_list[zone].shipping_methods}
{* $shipping_method = array(array("shippingid"=>..., "shipping"=>..., "rates"=>array(...))) *}

<tr>
	<td class="SubHeaderGreyLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>

<tr class="TableSubHead">
	<td>
<table cellpadding="2" cellspacing="0">

<script type="text/javascript" language="JavaScript 1.2">
<!--
checkboxes{$zones_list[zone].zone.zoneid}_{$shipid} = new Array({section name=rate loop=$shipping_method.rates}{if not %rate.first%},{/if}'posted_data[{$shipping_method.rates[rate].rateid}][to_delete]'{/section});
-->  
</script> 

<tr>
	<td><input type="checkbox" id="sm_{$zones_list[zone].zone.zoneid}_{$shipid}" name="sm_{$zones_list[zone].zone.zoneid}_{$shipid}" onclick="javascript:change_all(this.checked, checkboxes_form, checkboxes{$zones_list[zone].zone.zoneid}_{$shipid});" /></td>
	<td><b><label for="sm_{$zones_list[zone].zone.zoneid}_{$shipid}">{$shipping_method.shipping|trademark} ({if $shipping_method.destination eq "I"}{$lng.lbl_intl}{else}{$lng.lbl_national}{/if})
	</label></b></td>
</tr>
</table>

	</td>
</tr>

<tr>
	<td class="SubHeaderGreyLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>

<tr>
	<td>

<table cellpadding="0" cellspacing="3" width="100%" border="0">

{section name=rate loop=$shipping_method.rates}
{assign var="shipping_rate" value=$shipping_method.rates[rate]}
{if $type eq "R"}
{*
<tr>
	<td colspan="4"><b>{$lng.lbl_manufacturer}:: {foreach from=$manufacturers item=manuf}{if $shipping_rate.manufacturerid eq $manuf.manufacturerid}{$manuf.manufacturer}{/if}{/foreach}</b></td>
</tr>	
*}
{/if}
<tr>
	<td rowspan="{if $type eq "R"}3{else}2{/if}}" nowrap="nowrap"><img src="{$ImagesDir}/spacer.gif" width="10" height="1" alt="" /><input type="checkbox" name="posted_data[{$shipping_rate.rateid}][to_delete]" /></td>
	<td>{$lng.lbl_weight_range}:</td>
	<td nowrap="nowrap">
<input type="text" name="posted_data[{$shipping_rate.rateid}][minweight]" size="9" value="{$shipping_rate.minweight|formatprice}" />
-
<input type="text" name="posted_data[{$shipping_rate.rateid}][maxweight]" size="9" value="{$shipping_rate.maxweight|formatprice}" />
	</td>
	<td><b>{if $type eq "R"}{$lng.lbl_flat_charge}{else}Flat rate{/if} ({$config.General.currency_symbol}):</b></td>
	<td nowrap="nowrap"><input type="text" id="rate_{$shipping_rate.rateid}" name="posted_data[{$shipping_rate.rateid}][rate]" size="5" value="{$shipping_rate.rate|formatprice}" onkeyup="javascript: func_hide_show_real_drop_ship_fee('rate_{$shipping_rate.rateid}', 'real_drop_ship_fee_{$shipping_rate.rateid}');" onchange="javascript: func_hide_show_real_drop_ship_fee('rate_{$shipping_rate.rateid}', 'real_drop_ship_fee_{$shipping_rate.rateid}');" /></td>
	<td>{$lng.lbl_percent_charge}:</td>
	<td><input type="text" name="posted_data[{$shipping_rate.rateid}][rate_p]" size="5" value="{$shipping_rate.rate_p|formatprice}" /></td>
</tr>

<tr>
	<td>{$lng.lbl_subtotal_range}:</td>
	<td nowrap="nowrap">
<input type="text" name="posted_data[{$shipping_rate.rateid}][mintotal]" size="9" value="{$shipping_rate.mintotal|default:0|formatprice}" />
-
<input type="text" name="posted_data[{$shipping_rate.rateid}][maxtotal]" size="9" value="{$shipping_rate.maxtotal|formatprice}" />
	</td>


	<td>Real drop-ship fee ({$config.General.currency_symbol}):</td>
	<td nowrap="nowrap"><input {if $shipping_rate.rate eq "0" || $shipping_rate.rate eq "0.00"}readonly="readonly"{/if} type="text" id="real_drop_ship_fee_{$shipping_rate.rateid}" name="posted_data[{$shipping_rate.rateid}][real_drop_ship_fee]" size="5" value="{$shipping_rate.real_drop_ship_fee|formatprice}" /></td>


	<td>{$lng.lbl_per_weight_charge|substitute:"weight":$config.General.weight_symbol} ({$config.General.currency_symbol}):</td>
	<td nowrap="nowrap"><input type="text" name="posted_data[{$shipping_rate.rateid}][weight_rate]" size="5" value="{$shipping_rate.weight_rate|formatprice}" /></td>
</tr>


<tr>
        <td>{if $type eq "R"}<b>Server quote multiplier:</b>{/if}</td>
        <td nowrap="nowrap">
{if $type eq "R"}
<input type="text" name="posted_data[{$shipping_rate.rateid}][cost_marcup]" size="9" value="{$shipping_rate.cost_marcup|default:0}" />
{/if}
        </td>
        <td></td>
        <td>{$lng.lbl_per_item_charge} ({$config.General.currency_symbol}):</td>
        <td nowrap="nowrap"><input type="text" name="posted_data[{$shipping_rate.rateid}][item_rate]" size="5" value="{$shipping_rate.item_rate|formatprice}" /></td>
        <td nowrap="nowrap"></td>
</tr>
{if $type eq "D"}
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td>Min shipping charge (US$ ):</td>
		<td><input type="text" name="posted_data[{$shipping_rate.rateid}][min_shipping_charge]" size="5" value="{$shipping_rate.min_shipping_charge|formatprice}" /></td>
		<td>Max shipping charge (US$ ):</td>
		<td><input type="text" name="posted_data[{$shipping_rate.rateid}][max_shipping_charge]" size="8" value="{$shipping_rate.max_shipping_charge|formatprice}" /></td>
	</tr>
{/if}


{if not %rate.last%}
<tr>
	<td colspan="7" class="SubHeaderGreyLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
{/if}

{/section}

</table>
	</td>
</tr>

{/foreach}
{/capture}

{if $smarty.capture.rates_list}
{$smarty.capture.rates_list}
<tr>
	<td>&nbsp;</td>
</tr>
{else}
<tr>
	<td>{if $type eq "D"}{$lng.lbl_no_shipping_rates_defined}{else}{$lng.lbl_no_shipping_markups_defined}{/if}</td>
</tr>
{/if}

{/if}

{/section}

<tr>
	<td>
<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'delete');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" />
	</td>
</tr>

</table>
</form>

<br /><br /><br />

<a name="addrate"></a>

{/if}

<p>{if $type eq "D"}{include file="main/subheader.tpl" title=$lng.lbl_add_shipping_charge_values}{else}{include file="main/subheader.tpl" title=$lng.lbl_add_shipping_markup_values}{/if}</p>

{if $shipping ne ""}

<form action="manufacturers.php" method="post" name="addshippingrate">
<input type="hidden" name="distributor_section" value="{$distributor_section}" />

<input type="hidden" name="mode" value="add" />
<input type="hidden" name="zoneid" value="{$zoneid}" />
<input type="hidden" name="shippingid" value="{$shippingid}" />
<input type="hidden" name="manufacturerid" value="{$manufacturerid}" />
<input type="hidden" name="type" value="{$type}" />

{* {if $type eq "R"} *}
{foreach from=$manufacturers item=v}
{if $smarty.get.manufacturerid eq $v.manufacturerid}
<input type="hidden" name="manufacturerid_new" value='{$v.manufacturerid}' />
{/if}
{/foreach}
{* {/if} *}

<table cellpadding="0" cellspacing="3">

<tr>
	<td><b>{$lng.lbl_zone}:</b></td>
	<td>&nbsp;</td>
	<td>
	<select name="zoneid_new">
{section name=zone loop=$zones}
		<option value="{$zones[zone].zoneid}" {if $smarty.get.zoneid eq $zones[zone].zoneid}selected{/if}>{$zones[zone].zone}</option>
{/section}
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_shipping_method}:</b></td>
	<td>&nbsp;</td>
	<td>
	<select name="shippingid_new">
		<option value="">{$lng.lbl_select_one}</option>
{section name=ship_num loop=$shipping}
		<option value="{$shipping[ship_num].shippingid}">{$shipping[ship_num].shipping|trademark} ({if $shipping[ship_num].destination eq "I"}{$lng.lbl_intl}{else}{$lng.lbl_national}{/if})</option>
{/section}
	</select>
	</td>
</tr>

</table>

<table cellpadding="0" cellspacing="3" width="100%">

<tr>
	<td>{$lng.lbl_weight_range}:</td>
	<td nowrap="nowrap">
<input type="text" name="minweight_new" size="9" value="{0|formatprice}" />
-
<input type="text" name="maxweight_new" size="9" value="{$maxvalue|formatprice}" />
	</td>
	<td><b>{$lng.lbl_flat_charge} ({$config.General.currency_symbol}):</b></td>
	<td nowrap="nowrap"><input type="text" id="rate_new" name="rate_new" size="5" value="{0|formatprice}" onkeyup="javascript: func_hide_show_real_drop_ship_fee('rate_new', 'real_drop_ship_fee_new');" onchange="javascript: func_hide_show_real_drop_ship_fee('rate_new', 'real_drop_ship_fee_new');" /></td>
	<td>{$lng.lbl_percent_charge}:</td>
	<td><input type="text" name="rate_p_new" size="5" value="{0|formatprice}" /></td>
</tr>

<tr>
	<td>{$lng.lbl_subtotal_range}:</td>
	<td nowrap="nowrap">
<input type="text" name="mintotal_new" size="9" value="{0|formatprice}" />
-
<input type="text" name="maxtotal_new" size="9" value="{$maxvalue|formatprice}" />
	</td>
	<td>Real drop-ship fee ({$config.General.currency_symbol}):</td>
	<td nowrap="nowrap"><input id="real_drop_ship_fee_new" type="text" name="real_drop_ship_fee_new" size="5" value="{0|formatprice}" /></td>
	<td>{$lng.lbl_per_weight_charge|substitute:"weight":$config.General.weight_symbol} ({$config.General.currency_symbol}):</td>
	<td nowrap="nowrap"><input type="text" name="weight_rate_new" size="5" value="{0|formatprice}" /></td>
</tr>


<tr>
        <td>{if $type eq "R"}Server quote multiplier:</b>{/if}</td>
        <td nowrap="nowrap">
{if $type eq "R"}
<input type="text" name="cost_marcup_new" size="5" value="1.00" />
{/if}
        </td>
        <td>{$lng.lbl_per_item_charge} ({$config.General.currency_symbol}):</td>
        <td nowrap="nowrap"><input type="text" name="item_rate_new" size="5" value="{0|formatprice}" /></td>
        <td></td>
        <td nowrap="nowrap"></td>
</tr>
{if $type eq "D"}
<tr>
	<td></td>
	<td></td>
	<td>Min shipping charge (US$ ):</td>
	<td><input type="text" name="min_shipping_charge" size="5" value="{'0'|formatprice}" /></td>
	<td>Max shipping charge (US$ ):</td>
	<td><input type="text" name="max_shipping_charge" size="8" value="{$maxvalue|formatprice}" /></td>
</tr>
{/if}

</table>

<br />
<input type="submit" value=" {$lng.lbl_add|strip_tags:false|escape} ">

</form>

{elseif $type eq "D"}

{$lng.txt_shipping_charge_rtc_note}

{/if}


{/capture}
{if $type eq "D"}
{include file="dialog.tpl" title=$lng.lbl_shipping_charges content=$smarty.capture.dialog extra='width="100%"'}
{else}
{include file="dialog.tpl" title="Shipping markups list" content=$smarty.capture.dialog extra='width="100%"'}
{/if}

