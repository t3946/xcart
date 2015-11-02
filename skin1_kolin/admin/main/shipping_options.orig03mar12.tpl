{* $Id: shipping_options.tpl,v 1.36.2.10 2006/12/26 08:20:17 max Exp $ *}

{include file="page_title.tpl" title=$lng.lbl_shipping_options}

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->

<br />

{$lng.txt_shipping_options_top_text}

<br /><br />

{include file="check_email_script.tpl"}
{include file="check_zipcode_js.tpl"}

{$lng.lbl_select_service}:
{section name=carrier loop=$carriers}
{if $carriers[carrier].0 eq $carrier}
<b>{$carriers[carrier].1}</b>
{else}
<a href="shipping_options.php?carrier={$carriers[carrier].0}">{$carriers[carrier].1}</a>
{/if}
{if not %carrier.last%}&nbsp;::&nbsp;{/if}
{/section}

<br /><br />

{if $carrier eq "FDX"}

{capture name=dialog}

<div align="right"><a href="shipping.php?carrier=FDX#rt">{$lng.lbl_X_shipping_methods|substitute:"service":"FedEx"}</a></div>

{if $change_integration eq "Y"}

{$lng.txt_fedex_select_integration_type}

<br />
<br />

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="FDX" />

<table cellpadding="1" cellspacing="1">

<tr>
	<td><input type="radio" name="integration_type" id="integration_type1" value="T"{if $config.FEDEX_integration_type eq "T" or $config.FEDEX_intergation_type eq ""} checked="checked"{/if}></td>
	<td><label for="integration_type1"><b>FedEx Rate Tools</b></label></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td>{$lng.txt_fedex_offline_note}</td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
	<td><input type="radio" name="integration_type" id="integration_type2" value="A"{if $config.FEDEX_integration_type eq "A"} checked="checked"{/if}></td>
	<td><label for="integration_type2"><b>FedEx Ship Manager Direct</b></label></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td>{$lng.txt_fedex_online_note}</td>
</tr>

</table>

<br />
<br/>

<input type="submit" value="{$lng.lbl_apply}" name="update_integration_type" />

</form>

{else}

<table cellpadding="0" cellspacing="0">

<tr>
	<td>
<b>{$lng.lbl_fedex_integration_type}:</b>
<span style="FONT-SIZE: 13px; FONT-WEIGHT: bold;">
{if $config.FEDEX_integration_type eq "T" or $config.FEDEX_integration_type eq ""}
FedEx Rate Tools
{else}
FedEx Ship Manager Direct
{/if}
</span>
	&nbsp;&nbsp;
	</td>
	<td>
{include file="buttons/button.tpl" button_title=$lng.lbl_modify href="shipping_options.php?carrier=FDX&amp;intgr=Y"}
	</td>
</tr>

</table>

<br />
<br />

{if $config.FEDEX_integration_type eq "T" or $config.FEDEX_integration_type eq ""}

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="FDX" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td width="30%"><b>{$lng.lbl_company_type}:</b></td>
	<td>
	<select name="company_type">
		<option value="Express"{if $shipping_options.fdx.param05 eq "Express"} selected="selected"{/if}>FedEx Express</option>
		<option value="Ground"{if $shipping_options.fdx.param05 eq "Ground"} selected="selected"{/if}>FedEx Ground</option>
		<option value="Both"{if $shipping_options.fdx.param05 eq "Both"} selected="selected"{/if}>FedEx Ground & FedEx Express</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_packaging}:</b></td>
	<td>
	<select name="packaging">
		<option value="1"{if $shipping_options.fdx.param01 eq "1"} selected="selected"{/if}>My packaging</option>
		<option value="2"{if $shipping_options.fdx.param01 eq "2"} selected="selected"{/if}>FedEx Express Pak</option>
		<option value="3"{if $shipping_options.fdx.param01 eq "3"} selected="selected"{/if}>FedEx Express Box</option>
		<option value="4"{if $shipping_options.fdx.param01 eq "4"} selected="selected"{/if}>FedEx Express Tube</option>
		<option value="6"{if $shipping_options.fdx.param01 eq "6"} selected="selected"{/if}>FedEx Express Envelope</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_ship_to_residence}:</b></td>
	<td>
	<select name="dropoff_type">
		<option value="true"{if $shipping_options.fdx.param02 eq "true"} selected="selected"{/if}>{$lng.lbl_yes}</option>
		<option value="false"{if $shipping_options.fdx.param02 eq "false"} selected="selected"{/if}>{$lng.lbl_no}</option>
	</select>
	</td>
</tr>

<tr>
	<td colspan="2"><hr /></td>
</tr>

<tr>
	<td colspan="2"><h3>{$lng.lbl_fuel_surcharges}<br /></h3>{$lng.lbl_about_fuel_surcharges}</td>
</tr>

<tr>
	<td><b>{$lng.lbl_fedex_express_percent}:</b></td>
	<td><input size="20" name="expr_fuel_surch" value="{$shipping_options.fdx.param03}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_fedex_ground_percent}:</b></td>
	<td><input size="20" name="grnd_fuel_surch" value="{$shipping_options.fdx.param04}" /></td>
</tr>

<tr>
	<td colspan="2" class="SubmitBox"><input type="submit" name="update_options" value="{$lng.lbl_apply|strip_tags:false|escape}" /></td>
</tr>

</table>
</form>

<hr />

{********* start of uploading code **********}

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="FDX_IMPORT" />

<a name="fdx_import_rates"></a>

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td colspan="2"><h3>{$lng.lbl_import_rates_data}</h3></td>
</tr>

<tr>
	<td><b>{$lng.lbl_origin_zip_code}</b></td>
	<td>{$fdx_import_stat.ozip|default:"&nbsp;"}</td>
</tr>

<tr>
	<td><b>{$lng.lbl_zipcode_import_date}</b></td>
	<td>{$fdx_import_stat.date|date_format:$config.Appearance.datetime_format|default:"&nbsp;"}
{if $fdx_import_updated eq "true" and $fdx_import_stat.updated eq 1}
<b><font color="green"> - {$lng.lbl_updated}</font></b>
{/if}
	</td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
	<td><b>{$lng.lbl_shipping_methods_zones}</b></td>
	<td><b>{$lng.lbl_date_of_import}</b></td>
</tr>

{foreach from=$fdx_import_stat.files key=id item=name}

{if $name.date ne ""}
<tr>
	<td>{$id|capitalize}</td>
	<td>{$name.date|date_format:$config.Appearance.datetime_format}
{if $fdx_import_updated eq "true" and $name.updated eq 1}
<b><font color="green"> - {$lng.lbl_updated}</font></b>
{/if}
	</td>
</tr>
{/if}

{/foreach}

<tr>
	<td colspan="2">&nbsp;</td>
</tr>

{if $fdx_import_ok eq "true"}
<tr>
	<td colspan="2"><b><font color="green">{$lng.txt_fdx_files_imported}</font></b></td>
</tr>
{else}
<tr>
	<td colspan="2"><b><font color="red">{$fdx_import_ok}</font></b></td>
</tr>
{/if}

<tr>
	<td><b>{$lng.lbl_server_path_to_files}</b></td>
	<td><input size="40" name="fdx_import_files_path" value="{$fdx_files_path}" /></td>
</tr>

<tr>
	<td colspan="2" class="SubmitBox"><input type="submit" value="{$lng.lbl_import|strip_tags:false|escape}" /></td>
</tr>

<tr>
	<td colspan="2">
	<a href="javascript: void(0);" onclick="javascript: window.open('popup_info.php?action=FDX','FDX_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');"><b>{$lng.lbl_read_more_about_importing}</b></a>
	</td>
</tr>

</table>
</form>

{********* end of uploading code **********}

{else}

{if $config.Shipping.FEDEX_account_number ne ''}

{if $config.FEDEX_meter_number eq ""}

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="FDX" />

{$lng.txt_fedex_get_meter_number_note}

<br />
<br />

<table cellpadding="3" cellspacing="1">

<tr>
	<td width="30%" class="FormButton">{$lng.lbl_fedex_person_name}:</td>
	<td width="10" class="Star">*</td>
	<td><input type="text" size="35" maxlength="35" name="posted_data[person_name]" value="{$prepared_user_data.person_name}" /></td>
	<td width="20" class="Star">{if $fill_error ne "" and $prepared_user_data.person_name eq ""}&lt;&lt;{/if}</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_company_name}:</td>
	<td></td>
	<td><input type="text" size="35" maxlength="35" name="posted_data[company_name]" value="{$prepared_user_data.company_name}" /></td>
	<td></td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_phone}:</td>
	<td class="Star">*</td>
	<td><input type="text" size="35" maxlength="16" name="posted_data[phone_number]" value="{$prepared_user_data.phone_number}" /></td>
	<td class="Star">{if $fill_error ne "" and $prepared_user_data.phone_number eq ""}&lt;&lt;{/if}</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_pager_number}:</td>
	<td></td>
	<td><input type="text" size="35" maxlength="16" name="posted_data[pager_number]" value="{$prepared_user_data.pager_number}" /></td>
	<td></td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_fax}:</td>
	<td></td>
	<td><input type="text" size="35" maxlength="16" name="posted_data[fax_number]" value="{$prepared_user_data.fax_number}" /></td>
	<td></td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_email}:</td>
	<td></td>
	<td><input type="text" size="35" maxlength="120" name="posted_data[email]" value="{$prepared_user_data.email}" onchange="javascript: checkEmailAddress(this);" /></td>
	<td></td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_address}:</td>
	<td class="Star">*</td>
	<td><input type="text" size="35" maxlength="35" name="posted_data[address_1]" value="{$prepared_user_data.address_1}" /></td>
	<td class="Star">{if $fill_error ne "" and $prepared_user_data.address_1 eq ""}&lt;&lt;{/if}</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_address_2}:</td>
	<td></td>
	<td><input type="text" size="35" maxlength="35" name="posted_data[address_2]" value="{$prepared_user_data.address_2}" /></td>
	<td></td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_city}:</td>
	<td class="Star">*</td>
	<td><input type="text" size="35" maxlength="35" name="posted_data[city]" value="{$prepared_user_data.city}" /></td>
	<td class="Star">{if $fill_error ne "" and $prepared_user_data.city eq ""}&lt;&lt;{/if}</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_state}:</td>
	<td class="Star">*</td>
	<td>{include file="main/states.tpl" states=$states name="posted_data[state]" default=$prepared_user_data.state default_country=$prepared_user_data.country country_name="posted_data[country]"}</td>
	<td class="Star">{if $fill_error ne "" and $prepared_user_data.state eq ""}&lt;&lt;{/if}</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_zipcode}:</td>
	<td class="Star">*</td>
	<td><input type="text" size="35" maxlength="16" name="posted_data[zipcode]" value="{$prepared_user_data.zipcode}" onchange="javascript: check_zip_code_field(this.form['posted_data[country]'], this);" /></td>
	<td class="Star">{if $fill_error ne "" and $prepared_user_data.zipcode eq ""}&lt;&lt;{/if}</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_fedex_country}:</td>
	<td class="Star">*</td>
	<td>
	<select name="posted_data[country]" id="posted_data[country]" onchange="javascript: check_zip_code_field(this, this.form['posted_data[zipcode]']);">
	{section name=country_idx loop=$countries}
	<option value="{$countries[country_idx].country_code}"{if $prepared_user_data.country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $prepared_user_data.country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
	{/section}
	</select>
	</td>
	<td class="Star">{if $fill_error ne "" and $prepared_user_data.country eq ""}&lt;&lt;{/if}</td>
</tr>

</table>

<br />
<br />

<input type="submit" value="{$lng.lbl_fedex_get_meter_number}" name="get_meter_number" onclick="javascript: checkEmailAddress(this.form['posted_data[email]']);" />

</form>

{if $config.General.use_js_states eq 'Y' && $js_enabled eq 'Y'}
{include file="change_states_js.tpl"}
{include file="main/register_states.tpl" state_name="posted_data[state]" country_name="posted_data[country]" state_value=$prepared_user_data.state}
{/if}

{else}

{$lng.txt_fedex_clear_meter_number_note}

<br />
<br />

<b>{$lng.lbl_fedex_meter_number}:</b> {$config.FEDEX_meter_number|default:"n/a"}

<br />
<br />

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="FDX" />

<input type="submit" value="{$lng.lbl_fedex_clear_meter_number}" name="clear_meter_number" />

</form>

<br />
<br />

{$lng.txt_fedex_options_note}

<br />
<br />

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="FDX" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td width="30%"><b>{$lng.lbl_fedex_carrier_type}:</b></td>
	<td width="70%">
	<select name="carrier_code">
		<option value="FDXE"{if $shipping_options.fdx.carrier_code eq "FDXE"} selected="selected"{/if}>FedEx Express</option>
		<option value="FDXG"{if $shipping_options.fdx.carrier_code eq "FDXG"} selected="selected"{/if}>FedEx Ground</option>
		<option value="Both"{if $shipping_options.fdx.carrier_code eq "Both"} selected="selected"{/if}>FedEx Ground & FedEx Express</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_packaging}:</b></td>
	<td>
	<select name="packaging">
		<option value="FEDEXENVELOPE"{if $shipping_options.fdx.packaging eq "FEDEXENVELOPE"} selected="selected"{/if}>FedEx Envelope</option>
		<option value="FEDEXPAK"{if $shipping_options.fdx.packaging eq "FEDEXPAK"} selected="selected"{/if}>FedEx Pak</option>
		<option value="FEDEXBOX"{if $shipping_options.fdx.packaging eq "FEDEXBOX"} selected="selected"{/if}>FedEx Box</option>
		<option value="FEDEXTUBE"{if $shipping_options.fdx.packaging eq "FEDEXTUBE"} selected="selected"{/if}>FedEx Tube</option>
		<option value="FEDEX10KGBOX"{if $shipping_options.fdx.packaging eq "FEDEX10KGBOX"} selected="selected"{/if}>FedEx 10Kg Box</option>
		<option value="FEDEX25KGBOX"{if $shipping_options.fdx.packaging eq "FEDEX25KGBOX"} selected="selected"{/if}>FedEx 25Kg Box</option>
		<option value="YOURPACKAGING"{if $shipping_options.fdx.packaging eq "YOURPACKAGING"} selected="selected"{/if}>My packaging</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_fedex_dropoff_type}:</b></td>
	<td>
	<select name="dropoff_type">
		<option value="REGULARPICKUP"{if $shipping_options.fdx.dropoff_type eq "REGULARPICKUP"} selected="selected"{/if}>Regular pickup</option>
		<option value="REQUESTCOURIER"{if $shipping_options.fdx.dropoff_type eq "REQUESTCOURIER"} selected="selected"{/if}>Request courier</option>
		<option value="DROPBOX"{if $shipping_options.fdx.dropoff_type eq "DROPBOX"} selected="selected"{/if}>Drop box</option>
		<option value="BUSINESSSERVICECENTER"{if $shipping_options.fdx.dropoff_type eq "BUSINESSSERVICECENTER"} selected="selected"{/if}>Business Service Center</option>
		<option value="STATION"{if $shipping_options.fdx.dropoff_type eq "STATION"} selected="selected"{/if}>Station</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_fedex_ship_date}:</b></td>
	<td>
	<select name="ship_date">
		{section name=num loop=11 start=0}
		<option value="{$smarty.section.num.index}"{if $smarty.section.num.index eq $shipping_options.fdx.ship_date} selected="selected"{/if}>{$smarty.section.num.index}</option>
		{/section}
	</select>
	</td>
</tr>

<tr>
    <td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_fedex_dimensions class="grey"}</td>
</tr>

<tr>
	<td><b>{$lng.lbl_length} x {$lng.lbl_width} x {$lng.lbl_height} ({$lng.lbl_fedex_inches}):</b></td>
	<td nowrap="nowrap">
<input type="text" name="dim_length" value="{$shipping_options.fdx.dim_length}" size="7" />
x
<input type="text" name="dim_width" value="{$shipping_options.fdx.dim_width}" size="7" />
x
<input type="text" name="dim_height" value="{$shipping_options.fdx.dim_height}" size="7" />
	</td>
</tr>

<tr>
    <td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_fedex_cod class="grey"}</td>
</tr>

<tr>
    <td><b>{$lng.lbl_fedex_cod_value} (USD):</b></td>
    <td>
        <input type="text" name="cod_value" value="{$shipping_options.fdx.cod_value|default:"0.00"}" />
    </td>
</tr>

<tr>
    <td><b>{$lng.lbl_fedex_cod_type}:</b></td>
    <td>
        <select name="cod_type">
			<option value="ANY"{if $shipping_options.fdx.cod_type eq "ANY"} selected="selected"{/if}>{$lng.lbl_fedex_any}</option>
			<option value="GUARANTEEDFUNDS"{if $shipping_options.fdx.cod_type eq "GUARANTEEDFUNDS"} selected="selected"{/if}>{$lng.lbl_fedex_guaranteed_funds}</option>
			<option value="CASH"{if $shipping_options.fdx.cod_type eq "CASH"} selected="selected"{/if}>{$lng.lbl_fedex_cash}</option>
        </select>
    </td>
</tr>

<tr>
    <td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_fedex_special_services class="grey"}</td>
</tr>

<tr>
	<td><b>{$lng.lbl_fedex_dangerous_goods}:</b></td>
	<td>
	<select name="dg_accessibility">
		<option value=""{if $shipping_options.fdx.dg_accessibility eq ""} selected="selected"{/if}></option>
		<option value="ACCESSIBLE"{if $shipping_options.fdx.dg_accessibility eq "ACCESSIBLE"} selected="selected"{/if}>{$lng.lbl_fedex_accessible}</option>
		<option value="INACCESSIBLE"{if $shipping_options.fdx.dg_accessibility eq "INACCESSIBLE"} selected="selected"{/if}>{$lng.lbl_fedex_inaccessible}</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_fedex_signature_option}:</b></td>
	<td>
	<select name="signature">
		<option value=""{if $shipping_options.fdx.signature eq ""} selected="selected"{/if}></option>
		<option value="DELIVERWITHOUTSIGNATURE"{if $shipping_options.fdx.signature eq "DELIVERWITHOUTSIGNATURE"} selected="selected"{/if}>{$lng.lbl_fedex_no_signature}</option>
		<option value="INDIRECT"{if $shipping_options.fdx.signature eq "INDIRECT"} selected="selected"{/if}>{$lng.lbl_fedex_signature_indirect}</option>
		<option value="DIRECT"{if $shipping_options.fdx.signature eq "DIRECT"} selected="selected"{/if}>{$lng.lbl_fedex_signature_direct}</option>
		<option value="ADULT"{if $shipping_options.fdx.signature eq "ADULT"} selected="selected"{/if}>{$lng.lbl_fedex_signature_adult}</option>
	</select>
	</td>
</tr>

<tr>
	<td colspan="2">

	<table cellpadding="3" cellspacing="1">

	<tr>
		<td width="10"><input type="checkbox" name="alcohol" id="alcohol" value="Y"{if $shipping_options.fdx.alcohol eq "Y"} checked="checked"{/if}></td>
		<td width="50%"><b><label for="alcohol">{$lng.lbl_fedex_alcohol}</label></b></td>
		<td width="20">&nbsp;</td>
		<td width="10"><input type="checkbox" name="hold_at_location" id="hold_at_location" value="Y"{if $shipping_options.fdx.hold_at_location eq "Y"} checked="checked"{/if}></td>
		<td width="50%"><b><label for="hold_at_location">{$lng.lbl_fedex_hold_at_location}</label></b></td>
	</tr>

	<tr>
		<td><input type="checkbox" name="dry_ice" id="dry_ice" value="Y"{if $shipping_options.fdx.dry_ice eq "Y"} checked="checked"{/if}></td>
		<td><b><label for="dry_ice">{$lng.lbl_fedex_dry_ice}</label></b></td>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="nonstandard_container" id="nonstandard_container" value="Y"{if $shipping_options.fdx.nonstandard_container eq "Y"} checked="checked"{/if}></td>
		<td><b><label for="nonstandard_container">{$lng.lbl_fedex_nonstandard_container}</label></b></td>
	</tr>

	<tr>
		<td><input type="checkbox" name="inside_pickup" id="inside_pickup" value="Y"{if $shipping_options.fdx.inside_pickup eq "Y"} checked="checked"{/if}></td>
		<td><b><label for="inside_pickup">{$lng.lbl_fedex_inside_pickup}</label></b></td>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="inside_delivery" id="inside_delivery" value="Y"{if $shipping_options.fdx.inside_delivery eq "Y"} checked="checked"{/if}></td>
		<td><b><label for="inside_delivery">{$lng.lbl_fedex_inside_delivery}</label></b></td>
	</tr>

	<tr>
		<td><input type="checkbox" name="saturday_pickup" id="saturday_pickup" value="Y"{if $shipping_options.fdx.saturday_pickup eq "Y"} checked="checked"{/if}></td>
		<td><b><label for="saturday_pickup">{$lng.lbl_fedex_saturday_pickup}</label></b></td>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="saturday_delivery" id="saturday_delivery" value="Y"{if $shipping_options.fdx.saturday_delivery eq "Y"} checked="checked"{/if}></td>
		<td><b><label for="saturday_delivery">{$lng.lbl_fedex_saturday_delivery}</label></b></td>
	</tr>

	<tr>
		<td valign="top"><input type="checkbox" name="residential_delivery" id="residential_delivery" value="Y"{if $shipping_options.fdx.residential_delivery eq "Y"} checked="checked"{/if}></td>
		<td><b><label for="residential_delivery">{$lng.lbl_fedex_residential_delivery}</label></b>
		<br />
		{$lng.lbl_fedex_residential_delivery_note}
		</td>
		<td colspan="3">&nbsp;</td>
	</tr>

	</table>

	</td>
</tr>

<tr>
    <td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_fedex_handling class="grey"}</td>
</tr>

<tr>
	<td><b>{$lng.lbl_fedex_handling_amount}:</b></td>
	<td>
	<input type="text" size="10" maxlentgh="10" name="handling_charges_amount" value="{$shipping_options.fdx.handling_charges_amount|default:"0.00"}">
	<select name="handling_charges_type">
		<option value="FIXED_AMOUNT"{if $shipping_options.fdx.handling_charges_type eq "FIXED_AMOUNT"} selected="selected"{/if}>USD</option>
		<option value="PERCENTAGE_OF_BASE"{if $shipping_options.fdx.handling_charges_type eq "PERCENTAGE_OF_BASE"} selected="selected"{/if}>% of base</option>
		<option value="PERCENTAGE_OF_NET"{if $shipping_options.fdx.handling_charges_type eq "PERCENTAGE_OF_NET"} selected="selected"{/if}>% of net</option>
		<option value="PERCENTAGE_OF_NET_EXCL_TAXES"{if $shipping_options.fdx.handling_charges_type eq "PERCENTAGE_OF_NET_EXCL_TAXES"} selected="selected"{/if}>% of net (excluding taxes)</option>
	</select>
	</td>
</tr>

</table>

<br />
<br />

<input type="submit" value="{$lng.lbl_apply}" name="update_options" />

{/if}

</form>

{else}

{$lng.txt_fedex_disabled_note}

<br />
<br />

{/if}


{/if}

{/if}

{/capture}
{assign var="section_title" value=$lng.lbl_X_shipping_options|substitute:"service":"FedEx"}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$section_title extra='width="100%"'}

{/if}

{if $carrier eq "USPS"}

{capture name=dialog}

<div align="right"><a href="shipping.php?carrier=USPS#rt">{$lng.lbl_X_shipping_methods|substitute:"service":"U.S.P.S."}</a></div>

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="USPS" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td colspan="2"><h3>{$lng.lbl_international_usps}</h3></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_type_of_mail}:</b></td>
	<td>
	<select name="mailtype">
		<option value="Package"{if $shipping_options.usps.param00 eq "Package"} selected="selected"{/if}>Package</option>
		<option value="Postcards or Aerogrammes"{if $shipping_options.usps.param00 eq "Postcards or Aerogrammes"} selected="selected"{/if}>Postcards or Aerogrammes</option>
		<option value="Matter for the Blind"{if $shipping_options.usps.param00 eq "Matter for the Blind"} selected="selected"{/if}>Matter for the Blind</option>
		<option value="Envelope"{if $shipping_options.usps.param00 eq "Envelope"} selected="selected"{/if}>Envelope</option>
	</select>
	</td>
</tr>

<tr>
	<td colspan="2"><hr /></td>
</tr>

<tr>
	<td colspan="2"><h3>{$lng.lbl_domestic_usps}</h3></td>
</tr>

<tr>
	<td><b>{$lng.lbl_package_size} {$lng.lbl_package_size_note}:</b></td>
	<td>
	<select name="package_size">
		<option value="Regular"{if $shipping_options.usps.param01 eq "Regular"} selected="selected"{/if}>Regular (0 &lt; size &lt;= 84)</option>
		<option value="Large"{if $shipping_options.usps.param01 eq "Large"} selected="selected"{/if}>Large (84 &lt; size &lt;= 108)</option>
		<option value="Oversize"{if $shipping_options.usps.param01 eq "Oversize"} selected="selected"{/if}>Oversize (108 &lt; size &lt;= 130)</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_machinable}:</b></td>
	<td>
	<select name="machinable">
		<option value="FALSE"{if $shipping_options.usps.param02 eq "FALSE"} selected="selected"{/if}>{$lng.lbl_no}</option>
		<option value="TRUE"{if $shipping_options.usps.param02 eq "TRUE"} selected="selected"{/if}>{$lng.lbl_yes}</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_usps_container}:</b></td>
	<td>
	<select name="container_express">
		<option>{$lng.lbl_none}</option>
		<option value="Flat Rate Envelope"{if $shipping_options.usps.param03 eq "Flat Rate Envelope"} selected="selected"{/if}>Express Mail Flat Rate Envelope, 12.5 x 9.5</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_usps_container2}:</b></td>
	<td>
	<select name="container_priority">
		<option>{$lng.lbl_none}</option>
		<option value="Flat Rate Envelope"{if $shipping_options.usps.param04 eq "Flat Rate Envelope"} selected="selected"{/if}>Priority Mail Flat Rate Envelope, 12.5 x 9.5</option>
		<option value="Flat Rate Box"{if $shipping_options.usps.param04 eq "Flat Rate Box"} selected="selected"{/if}>Priority Mail Flat Rate Box, 14" x 12" x 3.5", 11.25" x 8.75" x 6"</option>
	</select>
	</td>
</tr>

<tr>
	<td colspan="2" class="SubmitBox"><input type="submit" value="{$lng.lbl_apply|strip_tags:false|escape}" /></td>
</tr>

</table>
</form>

{/capture}
{assign var="section_title" value=$lng.lbl_X_shipping_options|substitute:"service":"U.S.P.S."}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$section_title extra='width="100%"'}

{/if}

{if $carrier eq "Intershipper"}

{capture name=dialog}

<div align="right"><a href="shipping.php#rt">{$lng.lbl_manage_shipping_methods}</a></div>

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="Intershipper" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td width="50%"><b>{$lng.lbl_type_of_delivery}:</b></td>
	<td>
	<select name="delivery">
		<option value="COM"{if $shipping_options.intershipper.param00 eq "COM"} selected="selected"{/if}>Commercial delivery</option>
		<option value="RES"{if $shipping_options.intershipper.param00 eq "RES"} selected="selected"{/if}>Residential delivery</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_type_of_pickup}:</b></td>
	<td>
	<select name="pickup[]" size="3">
		<option value="DRP"{if $shipping_options.intershipper.param01 eq "DRP"} selected="selected" {/if}>Drop of at carrier location</option>
		<option value="SCD"{if $shipping_options.intershipper.param01 eq "SCD"} selected="selected" {/if}>Regularly Scheduled Pickup</option>
		<option value="PCK"{if $shipping_options.intershipper.param01 eq "PCK"} selected="selected" {/if}>Schedule A Special Pickup</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_length}:</b></td>
	<td><input type="text" name="length" size="10" value="{$shipping_options.intershipper.param02}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_width}:</b></td>
	<td><input type="text" name="width" size="10" value="{$shipping_options.intershipper.param03}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_height}:</b></td>
	<td><input type="text" name="height" size="10" value="{$shipping_options.intershipper.param04}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_dimensional_unit}:</b></td>
	<td>
	<select name="dunit">
		<option value="IN"{if $shipping_options.intershipper.param05 eq "IN"} selected="selected"{/if}>Inches</option>
		<option value="CM"{if $shipping_options.intershipper.param05 eq "CM"} selected="selected"{/if}>Centimeters</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_package_type}:</b></td>
	<td>
	<select name="packaging">
		<option value="BOX"{if $shipping_options.intershipper.param06 eq "BOX"} selected="selected"{/if}>Box</option>
		<option value="ENV"{if $shipping_options.intershipper.param06 eq "ENV"} selected="selected"{/if}>Envelope</option>
		<option value="ltr"{if $shipping_options.intershipper.param06 eq "ltr"} selected="selected"{/if}>Letter</option>
		<option value="TUB"{if $shipping_options.intershipper.param06 eq "TUB"} selected="selected"{/if}>Tube</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_nature_of_shipment_contents}:</b></td>
	<td>
	<select name="contents">
		<option value="OTR"{if $shipping_options.intershipper.param07 eq "OTR"} selected="selected"{/if}>Other: Most shipments will use this code</option>
		<option value="LQD"{if $shipping_options.intershipper.param07 eq "LQD"} selected="selected"{/if}>Liquid</option>
		<option value="AHM"{if $shipping_options.intershipper.param07 eq "AHM"} selected="selected"{/if}>Accessible HazMat</option>
		<option value="IHM"{if $shipping_options.intershipper.param07 eq "IHM"} selected="selected"{/if}>Inaccessible HazMat</option>
	</select>
	</td>
</tr>

<tr>
	<td><b>{$lng.lbl_package_cod_value}:</b></td>
	<td><input type="text" name="codvalue" size="10" value="{$shipping_options.intershipper.param08}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_package_insured_value}:</b></td>
	<td><input type="text" name="insvalue" size="10" value="{$shipping_options.intershipper.param09}" /></td>
</tr>

<tr>
	<td colspan="2" class="SubmitBox"><input type="submit" value="{$lng.lbl_apply|strip_tags:false|escape}" /></td>
</tr>

</table>
</form>

{/capture}
{assign var="section_title" value=$lng.lbl_X_shipping_options|substitute:"service":"InterShipper"}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$section_title extra='width="100%"'}

{/if}

{if $carrier eq "CPC"}

{capture name=dialog}

<div align="right"><a href="shipping.php?carrier=CPC#rt">{$lng.lbl_X_shipping_methods|substitute:"service":"Canada Post"}</a></div>

<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="CPC" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td width="50%"><b>{$lng.lbl_item_description}:</b></td>
	<td><input type="text" name="descr" size="50" value="{$shipping_options.cpc.param00}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_item_length}:</b></td>
	<td><input type="text" name="length" size="10" value="{$shipping_options.cpc.param01}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_item_width}:</b></td>
	<td><input type="text" name="width" size="10" value="{$shipping_options.cpc.param02}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_item_height}:</b></td>
	<td><input type="text" name="height" size="10" value="{$shipping_options.cpc.param03}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_cpc_package_insured_value}:</b></td>
	<td><input type="text" name="insvalue" size="10" value="{$shipping_options.cpc.param04}" /></td>
</tr>

<tr>
	<td><b>{$lng.lbl_shipping_cost_convertion_rate}:</b><br />
	<font class="SmallText">{$lng.txt_shipping_cost_convertion_rate}</font>
	</td>
	<td valign="top"><input type="text" name="currency_rate" size="10" value="{$shipping_options.cpc.param05}" /></td>
</tr>

<tr>
	<td colspan="2" class="SubmitBox"><input type="submit" value="{$lng.lbl_apply|strip_tags:false|escape}" /></td>
</tr>

</table>
</form>

{/capture}
{assign var="section_title" value=$lng.lbl_X_shipping_options|substitute:"service":"Canada Post"}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$section_title extra='width="100%"'}

{/if}

{if $carrier eq "ARB"}
{capture name=dialog}
<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="ARB" />

<table width="100%">

<tr>
	<td width="50%"><b>{$lng.lbl_arb_pkgtype}:</b></td>
	<td width="50%">
	<select name="param00">
		<option value="P"{if $shipping_options.arb.param00 eq "P"} selected="selected"{/if}>Package</option>
		<option value="L"{if $shipping_options.arb.param00 eq "L"} selected="selected"{/if}>Letter</option>
	</select>
	</td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_shipdays}:</b></td>
	<td><input type="text" name="param01" size="10" value="{$shipping_options.arb.param01}" /></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_pkg_len}:</b></td>
	<td><input type="text" name="param02" size="10" value="{$shipping_options.arb.param02}" /></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_pkg_width}:</b></td>
	<td><input type="text" name="param03" size="10" value="{$shipping_options.arb.param03}" /></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_pkg_height}:</b></td>
	<td><input type="text" name="param04" size="10" value="{$shipping_options.arb.param04}" /></td>
</tr>

<tr valign="top">
	<td width="50%"><b>{$lng.lbl_arb_ap_type}:</b></td>
	<td width="50%">
	<select name="param05">
		<option value="NR" {if $shipping_options.arb.param05 eq "NR"} selected="selected"{/if}>Not required</option>
		<option value="AP" {if $shipping_options.arb.param05 eq "AP"} selected="selected"{/if}>Asset Protection</option>
	</select>
	</td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_ap_value}:</b></td>
	<td><input type="text" name="param06" size="10" value="{$shipping_options.arb.param06}" /></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_haz}:</b></td>
	<td><input type="checkbox" name="opt_haz" value="Y"{if $shipping_options.arb.opt_haz eq "Y"} checked="checked"{/if} /></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_codpmt}:</b></td>
	<td>
	<select name="param08">
		<option value="M"{if $shipping_options.arb.param08 eq "M"} selected="selected"{/if}>Cashier's Check or Money Order</option>
		<option value="P"{if $shipping_options.arb.param08 eq "P"} selected="selected"{/if}>Personal or Company Check</option>
	</select>
	</td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_codval}:</b></td>
	<td><input type="text" name="param09" size="10" value="{$shipping_options.arb.param09}" /></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_arb_opt_own_account}:</b></td>
	<td><input type="checkbox" name="opt_own_account" value="Y"{if $shipping_options.arb.opt_own_account eq "Y"} checked="checked"{/if} /></td>
</tr>

<tr>
	<td colspan="2" class="SubmitBox"><input type="submit" value="{$lng.lbl_apply|strip_tags:false|escape}" /></td>
</tr>

</table>
</form>
{/capture}
{assign var="section_title" value=$lng.lbl_X_shipping_options|substitute:"service":"Airborne / DHL"}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$section_title extra='width="100%"'}

{/if}

{if $carrier eq "APOST"}
{capture name=dialog}
<form method="post" action="shipping_options.php">
<input type="hidden" name="carrier" value="APOST" />

<table width="100%">

<tr>
	<td width="50%"><b>{$lng.lbl_apost_pkg_len}:</b></td>
	<td><input type="text" name="param00" size="10" value="{$shipping_options.apost.param00}" /></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_apost_pkg_width}:</b></td>
	<td><input type="text" name="param01" size="10" value="{$shipping_options.apost.param01}" /></td>
</tr>

<tr>
	<td width="50%"><b>{$lng.lbl_apost_pkg_height}:</b></td>
	<td><input type="text" name="param02" size="10" value="{$shipping_options.apost.param02}" /></td>
</tr>

<tr>
	<td colspan="2" class="SubmitBox"><input type="submit" value="{$lng.lbl_apply|strip_tags:false|escape}" /></td>
</tr>
</table>
</form>

{/capture}
{assign var="section_title" value=$lng.lbl_X_shipping_options|substitute:"service":"Australia Post"}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$section_title extra='width="100%"'}

{/if}
