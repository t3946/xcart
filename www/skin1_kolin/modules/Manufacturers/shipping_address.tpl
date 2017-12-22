{* $Id: shipping_address.tpl,v 1 2008/04/01 06:39:34 zrr Exp $ *}
{include file="modules/Manufacturers/check_zipcode.tpl"}
{include file="generate_required_fields_js.tpl"}
{include file="check_required_fields_js.tpl"}
{include file="change_states_js.tpl"}

<tr>
<td colspan="3" class="RegSectionTitle">{$lng.lbl_shipping_address}</td>
</tr>


<tr>
<td>{$lng.lbl_address}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="b_address" name="b_address" size="32" maxlength="64" value="{$manufacturer.m_address}" />
</td>
</tr>

<tr>
<td>{$lng.lbl_address_2}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="b_address_2" name="b_address_2" size="32" maxlength="64" value="{$manufacturer.m_address_2}" />
</td>
</tr>

<tr>
<td>{$lng.lbl_city}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="b_city" name="b_city" size="32" maxlength="64" value="{$manufacturer.m_city}" />
</td>
</tr>

<tr>
<td>{$lng.lbl_country}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<select name="b_country" id="b_country" onchange="check_zip_code()">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $manufacturer.m_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $manufacturer.m_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>{$lng.lbl_state}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
{include file="main/states.tpl" states=$states name="b_state" default=$manufacturer.m_state default_country=$manufacturer.m_country country_name="b_country"}
</td>
</tr>

<tr style="display: none;">
<td>
{include file="main/register_states.tpl" state_name="b_state" country_name="b_country" county_name="b_county" state_value=$manufacturer.m_state county_value=$manufacturer.m_county}
</td>
</tr>

<tr>
<td>{$lng.lbl_zip_code}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="b_zipcode" name="b_zipcode" size="32" maxlength="32" value="{$manufacturer.m_zipcode}" onchange="check_zip_code()"  />
</td>
</tr>

<tr>
<td>&nbsp;</td>
</tr>

<tr>
<td>{$lng.lbl_email}:</td>
<td>&nbsp;</td>
<td><input type="text"  name="email" value="{$manufacturer.email}" size="50" maxlength="128" style="width: 80%;" /></td>
</tr>

<tr>
<td>{$lng.lbl_message_body}:</td>
<td>&nbsp;</td>
<td><textarea name="mess_body" rows="20" cols="60" style="width: 80%;">{$manufacturer.mess_body}</textarea></td>
</tr>

<tr>
<td>{$lng.lbl_submit_order_entry_operator}:</td>
<td>&nbsp;</td>
<td>
{*
	<input type="checkbox" name="submit_to_operator" value="Y"{if $manufacturer.submit_to_operator eq 'Y'}checked="checked"{/if} />
*}

<select name="submit_to_operator" id="submit_to_operator"> 
<option value="through_distributor_website"{if $manufacturer.submit_to_operator eq "through_distributor_website"} selected="selected"{/if}>through distributor website</option>
<option value="by_email_or_and_fax"{if $manufacturer.submit_to_operator eq "by_email_or_and_fax"} selected="selected"{/if}>by email or/and fax</option>
</select>


</td>
</tr>
