
{if $usertype eq "A" or ($active_modules.Simple_Mode ne "" and $usertype eq "P")}
{assign var="administrate" value="Y"}
{/if}

{if $mode ne "manufacturer_info"}
{capture name=dialogsearch}
<form action="manufacturers.php" method="get" name="search_manufacturer">
<table cellpadding="3" cellspacing="1" width="100%">
    <tr>
        <td width="100">
            <b>Dx name</b>
        </td>
        <td>
            <input style="min-width: 290px;" name="search" type="text" {if $search}value="{$search}"{/if}/>
        </td>
    </tr>
    <tr>
        <td width="100">
            <b>Main SF</b>
        </td>
        <td>
            <select name="search_site[]" id="o_site" class="big select2" multiple>
                {foreach from=$sites item=s}
                    <option value="{$s->storefrontid}" {if in_array($s->storefrontid, $search_site)}selected{/if}>
                        {$s}
                    </option>
                {/foreach}
            </select>
        </td>
    </tr>
    <tr>
        <td width="100">
            <b>VRS</b>
        </td>
        <td>
            <select style="min-width: 290px;" name="search_vrs[]" id="o_vrs" class="big select2" multiple>
                {foreach from=$vrs item=s}
                    <option value="{$s->login}" {if in_array($s->login, $search_vrs)}selected{/if}>
                        {$s} ({$s->login})
                    </option>
                {/foreach}
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="submit" value="Search"/>
        </td>
    </tr>
</table>
    <script type="text/javascript">
        {literal}
        $('#o_site').select2({
            allowClear: true,
            closeOnSelect: false,
            placeholder: 'Click to select SF'
        });
        $('#o_vrs').select2({
            allowClear: true,
            closeOnSelect: false,
            placeholder: 'Click to select VRS'
        });
        {/literal}
    </script>
</form>
{/capture}

{capture name=dialog}

<table>
    <tr>
        <td style="font-size: 12px;">{$lng.lbl_alphabetic}</td>
        <td style="font-size: 12px;">
            {if $word eq "num"}
                <span class="alp_selected">#</span>
            {else}
                <a href="manufacturers.php?word=num">#</a>
            {/if}
            {foreach from=$words item=w}
                {if $word eq $w}
                    &nbsp;<span class="alp_selected">{$w|strtoupper}</span>&nbsp;
                {else}
                    &nbsp;<a href="manufacturers.php?word={$w}">{$w|strtoupper}</a>&nbsp;
                {/if}
            {/foreach}
        </td>
    </tr>
</table>

<br />

{$pager}

{if $manufacturers ne ""}

<script type="text/javascript" language="JavaScript 1.2">
<!--
checkboxes_form = 'manufform';
checkboxes = new Array({foreach from=$manufacturers item=v key=k}{if $k > 0},{/if}'{if !($administrate eq "" and ($v.provider ne $login or $v.used_by_others gt 0))}to_delete[{$v.manufacturerid}]{/if}'{/foreach});
 
-->
</script>
{include file="main/include_js.tpl" src="change_all_checkboxes.js"}

<div style="line-height:170%"><a href="javascript:change_all(true);">{$lng.lbl_check_all}</a> / <a href="javascript:change_all(false);">{$lng.lbl_uncheck_all}</a></div>

{/if}

<form action="manufacturers.php" method="post" name="manufform">
<input type="hidden" name="mode" value="update" />
<input type="hidden" name="page" value="{$page}" />
<input type="hidden" name="word" value="{$word}" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
	{if $manufacturers ne ""}<td width="10">&nbsp;</td>{/if}
    <td width="30" align="center">{$lng.lbl_orderby}</td>
	<td width="35%">DX Company Name</td>
	<td width="10%">DX Prefix</td>
	<td width="25%">Main SF</td>
	<td width="20%" align="center">All SKUs</td>
	<td width="20%" align="center">Active SKUs</td>
	<td width="30" align="center">Feed</td>
	<td width="30" align="center">Feed source</td>
	<td width="30" align="center">Parent DX</td>
    <td width="30" align="center">Child DX</td>

	<td width="30" align="center">{$lng.lbl_active}</td>
</tr>

{if $manufacturers ne ""}
{foreach from=$manufacturers item=v}
{assign var=products_count value=$v->products->count()}
{assign var=active_products_count value=$v->products_active->count()}
<tr{cycle values=", class='TableSubHead'"}>
	<td align="center">
        <input type="checkbox" name="to_delete[{$v->manufacturerid}]"{if !$administrate && ($v->provider != $login or $v->used_by_others gt 0)} disabled="disabled"{/if} />
    </td>
    <td align="center">
        <input type="text" name="records[{$v->manufacturerid}][orderby]" size="5" value="{$v->orderby}"{if !$administrate} disabled="disabled"{/if} />
    </td>
	<td style="white-space: nowrap;"><b><a href="{$v->getAdminUrl()}">{$v}</a></b></td>
	<td style="white-space: nowrap;" align="center">{$v->code}</td>
    <td style="white-space: nowrap;">
        {foreach from=$v->sites item=site}
        <a target="_blank" href="{$site->getAbsoluteUrl()}">{$site}</a><br/>
        {/foreach}
    </td>
	<td align="center">{if $products_count}{$products_count}{else}{$lng.txt_not_available}{/if}</td>
    <td align="center">{if $active_products_count}{$active_products_count}{else}{$lng.txt_not_available}{/if}</td>
	<td align="center">{if $v->feed_I_E->count()}I({$v->feed_I_E->count()}){/if}{if $v->feed_P_E->count()}P({$v->feed_P_E->count()}){/if}</td>
	<td align="center">
        {foreach from=$v->feeds item=feed}
            {assign value=$feed->getField('feed_source') var=field_source}
            {$field_source->toText()}<br/>
            {$feed->feed_source_date}
        {/foreach}
    </td>
	<td style="white-space: nowrap;" align="center">
        {foreach from=$v->parents item=parent}
            <a target="_blank" href="{$parent->getAdminUrl()}">{$parent->code}</a><br/>
        {/foreach}
    </td>
	<td style="white-space: nowrap;" align="center">
        {foreach from=$v->childs item=child}
            <a target="_blank" href="{$child->getAdminUrl()}">{$child->code}</a><br/>
        {/foreach}
    </td>

	<td align="center">
        <input type="checkbox" name="records[{$v->manufacturerid}][avail]" value="Y"{if $v->avail eq "Y"} checked="checked"{/if}{if !$administrate} disabled="disabled"{/if} />
    </td>
</tr>

{/foreach}

{if $show_note eq "Y"}
<tr>
	<td colspan="6"><br />{$lng.txt_manufacturers_special_note}</td>
</tr>
{/if}

<tr>
	<td colspan="6" class="SubmitBox">
	<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('^to_delete\\[.+\\]', 'gi'))) if (confirm('{$lng.txt_manufacturers_delete_msg|strip_tags}')) {ldelim} document.manufform.mode.value='delete'; document.manufform.submit(); {rdelim}" />
	<input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" />
	</td>
</tr>

{else}

<tr>
	<td colspan="6" align="center"><br />{$lng.txt_no_manufacturers}</td>
</tr>

{/if}

<tr>
<td colspan="6"><br /><input type="button" value="{$lng.lbl_add_new_|strip_tags:false|escape}" onclick="javascript: self.location = '/admin/distributor/add';" /></td>
</tr>

</table>

</form>


{if $word eq "" || $word eq "num"}
<br />
<br />
<hr />
<form action="manufacturers.php" method="post" name="manufform">
<input type="hidden" name="mode" value="export_emails" />
<input type="submit" value="Export emails">
</form>

	{if $distributor_contacts_file ne ""}
		<br />
		<a href="getfile.php?file=%2F{$distributor_contacts_file_name}">{$distributor_contacts_file}</a>
	{/if}
{/if}


{$pager}

{/capture}

    {include file="dialog.tpl" title="Search distributor" content=$smarty.capture.dialogsearch extra='width="100%"'}
    <br/>
    <br/>
    {include file="dialog.tpl" title=$lng.lbl_manufacturers_list content=$smarty.capture.dialog extra='width="100%"'}

{else}

{include file="main/include_js.tpl" src="main/popup_image_selection.js"}

{$sectionMenu}

{capture name=dialog}

{if $administrate eq "" and $manufacturer.used_by_others gt 0}
<br />
<span class="ErrorMessage">{$lng.txt_manufacturers_warning}</span>
<br />
{/if}

{if $administrate eq "" and $login ne $manufacturer.provider and $smarty.get.mode ne "add"}
{assign var="disabled" value=" disabled"}
{/if}

{if $manufacturer.manufacturerid ne ''}
{include file="main/language_selector.tpl" script="manufacturers.php?manufacturerid=`$manufacturer.manufacturerid`&"}
{/if}

{if $smarty.get.distributor_section ne "19" && $smarty.get.distributor_section ne "21" && $smarty.get.distributor_section ne "22"}
<form action="manufacturers.php" method="post" enctype="multipart/form-data" name="manufacturer" {if $smarty.get.distributor_section eq "30" && $manufacturer.parent_manufacturer_id eq -1}target="_blank"{/if}>
<input type="hidden" name="mode" value="details" id="mode" />
<input type="hidden" name="manufacturerid" value="{$manufacturer.manufacturerid}" />
<input type="hidden" name="page" value="{$page}" />
<input type="hidden" name="distributor_section" value="{$distributor_section}" />
{/if}


{foreach from=$distributor_sections item=d_section key=k_section}

{if $d_section.distributor_section == 1}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_1" {if $distributor_section ne "1"}style="display: none;" {/if}>
    <tr>
        <td width="20%" class="FormButton">Added by </td>
        <td></td>
        <td width="80%">{$distributorModel->provider_model} ({$distributorModel->provider})
            <a title="{$lng.help_dx_provider_text|htmlspecialchars|default:help_dx_provider_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Distributor company name</td>
        <td><span class="Star">*</span></td>
        <td width="80%"><input style="width:50%" type="text" name="manufacturer" size="50" value="{$distributorModel->manufacturer}" style="width:80%"{$disabled} />
            <a title="{$lng.help_dx_comapny_name_text|htmlspecialchars|default:help_dx_comapny_name_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Distributor prefix</td>
        <td><span class="Star">*</span></td>
        <td width="80%"><input type="text" name="code" size="10" maxlength="5" value="{$distributorModel->code}"
                               style="width:12%"{$disabled} />
            <a title="{$lng.help_dx_prefix_text|htmlspecialchars|default:help_dx_prefix_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td class="FormButton">Distributor website URL (main page)</td>
        <td>&nbsp;</td>
        <td><input type="text" size="47" name="url" value="{$distributorModel->url}" style="width:50%" {$disabled} />
            {if $distributorModel->url ne ""}<a href="{$distributorModel->url}" target="blank">Website</a>{/if}
            <a title="{$lng.help_dx_website_text|htmlspecialchars|default:help_dx_website_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td class="FormButton">Logo</td>
        <td></td>
        <td>
            {if $distributorModel && $distributorModel->images->count()}{assign var="no_delete" value=""}{else}{assign var="no_delete" value="Y"}{/if}
            <table>
            <tr>
                <td>
                    {include file="main/edit_image.tpl" type="M" id=$distributorModel->manufacturerid delete_url="manufacturers.php?mode=delete_image&manufacturerid=`$distributorModel->manufacturerid`" button_name=$lng.lbl_save no_delete=$no_delete}
                </td>
                <td>
                    <a title="{$lng.help_dx_logo_text|htmlspecialchars|default:help_dx_logo_text}" class="tooltip">
                        <i class="fa fa-question-circle pointer"></i>
                    </a>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="FormButton">Main SF</td>
        <td></td>
        <td>
            {assign var=dx_sites value=$distributorModel->sites}
            {if $dx_sites}
                {assign var=dx_sss value=$dx_sites->valuesList("storefrontid", true)}
            {/if}
            <table>
                <tr>
                    <td>
                        <select multiple class="select2" name="d_main_sf[]">
                            {foreach from=$sd_selects key=key item=sf}
                                <option value="{$key}" {if $distributorModel && in_array($key, $dx_sss)} selected="selected" {/if}>{$sf}</option>
                            {/foreach}
                        </select>
                    </td>
                    {if $distributorModel}
                        <td>
                            {foreach from=$dx_sites item=dx_site}
                                <a href="{$dx_site->getAbsoluteUrl()}" target="_blank">SF website</a>
                                <br/>
                            {/foreach}
                        </td>
                    {/if}
                    <td><a title="{$lng.help_dx_site_text|htmlspecialchars|default:help_dx_site_text}" class="tooltip">
                            <i class="fa fa-question-circle pointer"></i>
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="FormButton">Distributor notes for dispatcher (Dx notes)</td>
        <td>&nbsp;</td>
        <td>
            <textarea {if $distributor_section eq "1"}class="new_editor"{/if} name="d_specific_instructions" rows="20"
                      cols="60" style="width: 80%;">{$distributorModel->d_specific_instructions}</textarea>
            <a title="{$lng.help_dx_instructions_text|htmlspecialchars|default:help_dx_instructions_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td class="FormButton">Dx warehouse is closed until</td>
        <td></td>
        <td>
            <input value="{$distributorModel->dx_eta_date|date_format:"%m/%d/%Y"}" type="text" name="dx_eta_date" data-language="en" data-clear-button="1" class="datepicker-here big" />
        </td>
    </tr>
    {if $administrate eq "Y"}
        <tr>
            <td class="FormButton">Activate distributor products</td>
            <td>&nbsp;</td>
            <td>
                <input type="checkbox" name="avail" value="Y"{if $distributorModel->avail === 'Y' || !$distributorModel} checked="checked"{/if} />
                <a title="{$lng.help_dx_activate_text|htmlspecialchars|default:help_dx_activate_text}" class="tooltip">
                    <i class="fa fa-question-circle pointer"></i>
                </a>
            </td>
        </tr>
    {/if}

    {if $smarty.get.manufacturerid eq "32"}
        <tr>
            <td class="FormButton">Reverse SKU</td>
            <td>&nbsp;</td>
            <td>
                <input type="checkbox" name="reverse_sku" value="Y"{if $manufacturer.reverse_sku eq 'Y'} checked="checked"{/if} />
            </td>
        </tr>
        <tr>
            <td class="FormButton">Remove dashes</td>
            <td>&nbsp;</td>
            <td>
                <input type="checkbox" name="remove_dashes" value="Y"{if $manufacturer.remove_dashes eq 'Y'} checked="checked"{/if} /></td>
        </tr>
    {/if}

</table>

{elseif $d_section.distributor_section == 2}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_2" {if $distributor_section ne "2"}style="display: none;" {/if}>
    <tr>
        <td class="FormButton">Front-end product page tabs</td>
        <td>&nbsp;</td>
        <td>
            <textarea name="cart_manufact_text_displayed" rows="5" cols="60" style="width:80%">{$manufacturer.cart_manufact_text_displayed}</textarea>
            <a title="{$lng.help_dx_front_page_tabs_text|htmlspecialchars|default:help_dx_front_page_tabs_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td class="FormButton" nowrap="nowrap">"Add to cart" pop-up message</td>
        <td>&nbsp;</td>
        <td>
            <input type="text" size="50" name="lead_time_message" value="{$manufacturer.lead_time_message|escape}" style="width:80%"/>
            <a title="{$lng.help_dx_add_to_cart_popup_text|htmlspecialchars|default:help_dx_add_to_cart_popup_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    {*<tr>
        <td width="25%" class="FormButton">{$lng.lbl_catalog_sku}</td>
        <td>&nbsp;</td>
        <td width="75%">
            <input type="text" size="50" name="catalog_sku" value="{$manufacturer.catalog_sku}" style="width:80%"/>
        </td>
    </tr>
    <tr>
        <td class="FormButton">{$lng.lbl_catalog_price}</td>
        <td>&nbsp;</td>
        <td><input type="text" size="18" name="catalog_price" value="{$manufacturer.catalog_price}"/></td>
    </tr>
    <tr>
        <td class="FormButton">{$lng.lbl_catalog_text}</td>
        <td>&nbsp;</td>
        <td><input type="text" size="50" name="catalog_text" value="{$manufacturer.catalog_text}" style="width:80%"/>
        </td>
    </tr>*}
</table>

{elseif $d_section.distributor_section == 3}
<table cellpadding="3" cellspacing="1" {* width="100%" *} id="distributor_section_id_3" {if $distributor_section ne "3"}style="display: none;" {/if}>
{if $manufacturer.distributor_contacts ne ""}
<tr>
<td style="font-weight: bold;">Position</td>
<td style="font-weight: bold;"><a style="color: blue;" href="manufacturers.php?manufacturerid={$manufacturer.manufacturerid}&distributor_section=16">PQ</a></td>
<td style="font-weight: bold;">Contact name</td>
<td style="font-weight: bold;">Email</td>
<td style="font-weight: bold;">Phone<br /><span style="font-weight: normal;">(start with area code)</span></td>
<td style="font-weight: bold;">Ext</td>
<td style="font-weight: bold;">Call</td>
<td style="font-weight: bold;">Fax</td>
<td style="font-weight: bold;">Delete</td>
</tr>

<input type="hidden" id="delete_line_number" name="delete_line_number" value="" />

{foreach from=$distributorModel->contacts_model item=item key=key}
<tr>
{* <td>{$item.distributor_field_name}</td> *}
<td><input type="text" name="distributor_contacts[{$key}][distributor_field_name]" value="{$item->distributor_field_name}" size="30" /></td>
<td>

<input type="radio" name="pq" value="{$key}" {if $item->pq eq "Y"}checked="checked"{/if} />

</td>
<td><input type="text" name="distributor_contacts[{$key}][contact_name]" value="{$item->contact_name|escape:"html"}" size="30" /></td>
<td><input type="text" name="distributor_contacts[{$key}][email]" value="{$item->email}" size="30" /></td>

<td>

{if $key == 0}<div style="border: green 1px solid;">{/if}
<input type="text" name="distributor_contacts[{$key}][phone]" value="{$item->phone}" size="17" />
{if $key == 0}</div>{/if}

</td>
<td><input type="text" name="distributor_contacts[{$key}][ext]" value="{$item->ext}" size="7" /></td>

<td>
{if $item->phone ne ""}
<a target="_blank" style="color: blue;" href="tel:{$item->getPhoneNormalized()}">Call</a>
{/if}
</td>

<td><input type="text" name="distributor_contacts[{$key}][fax]" value="{$item->fax}" size="17" /></td>
<td>
 <input type="button" value="Delete" onclick="javascript: {literal}$('#mode').val('delete_line'); $('#delete_line_number').val('{/literal}{$key}{literal}'); document.manufacturer.submit();"{/literal} />
</td>
</tr>
{/foreach}
{/if}
</table>

{elseif $d_section.distributor_section == 6}
    <script>
        //<![CDATA[
        {literal}
        var geo_litecity_location_city = "";
        var geo_litecity_location_region = "";
        var geo_litecity_location_country = "";
        var geo_litecity_location_region_name = "";
        {/literal}
        //]]>
    </script>

{include file="modules/Manufacturers/check_zipcode.tpl"}
{include file="generate_required_fields_js.tpl"}
{include file="check_required_fields_js.tpl"}
{include file="change_states_js.tpl"}
    <table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_6" {if $distributor_section != 6}style="display: none;" {/if}>
        <tr>
            <td colspan="3">
                <b>Here indicate the address of the main distributor warehouse.</b>
            </td>
        </tr>
        <tr>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td class="FormButton" width="20%">{$lng.lbl_address}</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap" width="80%">
                <input type="text" id="b_address" name="m_address" size="32" maxlength="64" value="{$manufacturer.m_address}"/>
            </td>
        </tr>
        <tr>
            <td class="FormButton">{$lng.lbl_address_2}</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">
                <input type="text" id="b_address_2" name="m_address_2" size="32" maxlength="64" value="{$manufacturer.m_address_2}"/>
            </td>
        </tr>
        <tr>
            <td class="FormButton">{$lng.lbl_city}</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">
                <input type="text" id="b_city" name="m_city" size="32" maxlength="64" value="{$manufacturer.m_city}"/>
            </td>
        </tr>
        <tr>
            <td class="FormButton">{$lng.lbl_country}</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">
                <select name="m_country" id="b_country" onchange="check_zip_code()">
                    {section name=country_idx loop=$countries}
                        <option value="{$countries[country_idx].country_code}"
                                {if $manufacturer.m_country eq $countries[country_idx].country_code ||
                                    $countries[country_idx].country_code eq $config.General.default_country and $manufacturer.m_country eq ""} selected="selected"
                                {/if}>{$countries[country_idx].country|amp}</option>
                    {/section}
                </select>
            </td>
        </tr>
        <tr>
            <td class="FormButton">{$lng.lbl_state}</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">
                {include file="main/states.tpl" states=$states name="m_state" default=$manufacturer.m_state default_country=$manufacturer.m_country country_name="m_country"}
            </td>
        </tr>
        <tr style="display: none;">
            <td>
                {include file="main/register_states.tpl" state_name="m_state" country_name="m_country" county_name="m_county" state_value=$manufacturer.m_state county_value=$manufacturer.m_county}
            </td>
        </tr>
        <tr>
            <td class="FormButton">{$lng.lbl_zip_code}</td>
            <td>&nbsp;</td>
            <td nowrap="nowrap">
                <input type="text" id="b_zipcode" name="m_zipcode" size="32" maxlength="32" value="{$manufacturer.m_zipcode}" onchange="check_zip_code()"/>
            </td>
        </tr>
    </table>

{elseif $d_section.distributor_section eq "8"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_8" {if $distributor_section ne "8"}style="display: none;" {/if}>

<tr>
<td class="FormButton" width="20%">Our dealer account #</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_our_dealer_account_n" value="{$manufacturer.d_our_dealer_account_n}" size="50" maxlength="128" style="width: 80%;" /></td>
</tr>

<tr>
<td class="FormButton" width="20%">Contact name for templates</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_contact_name_for_templates" value="{$manufacturer.d_contact_name_for_templates}" size="50" maxlength="128" style="width: 40%;" /></td>
</tr>

<tr>
<td class="FormButton" width="20%">'Send to' email for templates</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_send_to_email_for_templates" value="{$manufacturer.d_send_to_email_for_templates}" size="50" maxlength="128" style="width: 40%;" /></td>
</tr>


<tr><td colspan="3" align="center"><B>Login to distributor website</B></td></tr>

<tr>
<td class="FormButton" width="20%">URL to login to distributor website</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_url_to_login_to_distributor_website" value="{$manufacturer.d_url_to_login_to_distributor_website}" size="50" maxlength="128" style="width: 40%;" /> {if $manufacturer.d_url_to_login_to_distributor_website ne ""}<a href="{$manufacturer.d_url_to_login_to_distributor_website}" target="_blank">Login URL</a>{/if}</td>
</tr>

<tr>
<td class="FormButton" width="20%">Login/username</td>
<td>&nbsp;</td>
<td width="80%">

<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
{literal}
function func_show_login_password_info(manufacturerid) {

    cidev_xmlHttp = cidev_createHttpRequestObject();
    if (cidev_xmlHttp.readyState == 4 || cidev_xmlHttp.readyState == 0) {

        var cidev_parameters = 'manufacturerid=' + manufacturerid

        cidev_xmlHttp.onreadystatechange = function () {
            if (cidev_xmlHttp.readyState == 4) {
                if (cidev_xmlHttp.status == 200) {
                    $('#div_d_login').show();
                    $('#div_d_password').show();
                    $('#link_unhide').hide();
                } else {
                }
            }
        };

        var tmp_rand = Math.random();

        cidev_xmlHttp.open('POST', 'unhide_manufacturer_login.php?rand=' + tmp_rand, true);
        cidev_xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        cidev_xmlHttp.setRequestHeader('Content-length', cidev_parameters.length);
        cidev_xmlHttp.setRequestHeader('Cache-Control', 'no-cache');
        cidev_xmlHttp.setRequestHeader('Cache-Control', 'no-store');
        cidev_xmlHttp.setRequestHeader('Connection', 'close');
        cidev_xmlHttp.send(cidev_parameters);
    } else {
        setTimeout('func_show_login_password_info()', 1000);
    }
}
{/literal}
//]]>
</script>

<a id="link_unhide" style="color: blue; border-bottom: 1px dotted blue; text-decoration: none;" href="javascript: void(0);" onclick="javascript: func_show_login_password_info({$manufacturer.manufacturerid});">Unhide</a>

  <div id="div_d_login" style="display: none;">
	<input type="text" id="d_login" name="d_login" value="{$manufacturer.d_login}" size="50" maxlength="128" style="width: 40%;" />
  </div>
</td>
</tr>

<tr>
<td class="FormButton" width="20%">Password</td>
<td>&nbsp;</td>
<td width="80%">
  <div id="div_d_password" style="display: none;">
	<input type="text" id="d_password" name="d_password" value="{$manufacturer.d_password}" size="50" maxlength="128" style="width: 40%;" />
  </div>
</td>
</tr>

<tr>
        <td width="20%" class="FormButton">Preferred way to submit orders is</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="submit_to_operator" id="submit_to_operator" onchange="javasript:{literal} if ($('#submit_to_operator').val()=='through_distributor_website'){ $('#tr_email_to_order_entry_operator').show(); $('#order_submission_by_email_or_and_fax1').hide(); $('#order_submission_by_email_or_and_fax2').hide(); $('#order_submission_by_email_or_and_fax22').hide(); $('#order_submission_by_email_or_and_fax33').hide(); $('#order_submission_by_email_or_and_fax3').hide(); $('#order_submission_by_email_or_and_fax4').hide(); $('#order_submission_by_email_or_and_fax5').hide(); $('#order_submission_by_email_or_and_fax9').hide(); $('#order_submission_by_email_or_and_fax7').show(); $('#order_submission_by_email_or_and_fax8').show(); $('#order_submission_by_email_or_and_fax6').hide(); $('#tr_d_order_entry_operator_email').show(); $('#tr_d_instructions_to_order_entry_operator').show();}else{$('#tr_d_order_entry_operator_email').hide(); $('#tr_d_instructions_to_order_entry_operator').hide(); $('#order_submission_by_email_or_and_fax1').show(); $('#order_submission_by_email_or_and_fax2').show(); $('#order_submission_by_email_or_and_fax22').show(); $('#order_submission_by_email_or_and_fax33').show(); $('#order_submission_by_email_or_and_fax3').show(); $('#order_submission_by_email_or_and_fax4').show(); $('#order_submission_by_email_or_and_fax5').show(); $('#order_submission_by_email_or_and_fax9').show(); $('#order_submission_by_email_or_and_fax7').hide(); $('#order_submission_by_email_or_and_fax8').hide(); $('#order_submission_by_email_or_and_fax6').show(); $('#tr_email_to_order_entry_operator').hide();}{/literal}">
<option value="through_distributor_website"{if $manufacturer.submit_to_operator eq "through_distributor_website"} selected="selected"{/if}>through distributor website</option>
<option value="by_email_or_and_fax"{if $manufacturer.submit_to_operator eq "by_email_or_and_fax"} selected="selected"{/if}>by email or/and fax</option>
</select>
        </td>
</tr>

<tr {if $manufacturer.submit_to_operator ne 'through_distributor_website'}style="display: none;"{/if} id="tr_email_to_order_entry_operator"><td colspan="3" align="center"><B>Email to order entry operator</B></td></tr>

<tr {if $manufacturer.submit_to_operator eq 'by_email_or_and_fax'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax7">
        <td colspan="3">{$lng.txt_distributor_section_82}</td>
</tr>

<tr id="tr_d_order_entry_operator_email" {if $manufacturer.submit_to_operator ne 'through_distributor_website'}style="display: none;"{/if}>
<td width="20%" class="FormButton">Order entry operator email</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_order_entry_operator_email" value="{$manufacturer.d_order_entry_operator_email}" size="50" maxlength="128" style="width: 80%;" /></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'by_email_or_and_fax'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax8">
<td class="FormButton" width="20%">Order entry operator subject line</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_order_entry_operator_subject_line_8" value="{$manufacturer.d_order_entry_operator_subject_line_8}" size="50"style="width: 80%;" /></td>
</tr>

<tr id="tr_d_instructions_to_order_entry_operator" {if $manufacturer.submit_to_operator ne 'through_distributor_website'}style="display: none;"{/if}>
<td class="FormButton" width="20%">Instructions to order entry operator</td>
<td>&nbsp;</td>
<td width="80%"><textarea {if $distributor_section eq "8"}class="new_editor"{/if} name="d_instructions_to_order_entry_operator" rows="15" cols="60" style="width: 80%;">{$manufacturer.d_instructions_to_order_entry_operator}</textarea></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax1"><td colspan="3" align="center"><B>Order submission by e-mail or/and fax</B></td></tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax3">
        <td colspan="3">{$lng.txt_distributor_section_8}</td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax22">
        <td width="20%" class="FormButton">Allow dispatch off working hours</td>
        <td>&nbsp;</td>
        <td width="80%">
        <input type="checkbox" name="allow_dispatch_off_working_hours" value="Y"{if $manufacturer.allow_dispatch_off_working_hours eq 'Y'} checked="checked"{/if} />
        </td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax33">
        <td class="FormButton" width="20%">Add 'Cost to us' column to dispatch message</td>
        <td>&nbsp;</td>
        <td width="80%">
        <input type="checkbox" name="add_cost_to_us_column_to_dispatch_message" value="Y"{if $manufacturer.add_cost_to_us_column_to_dispatch_message eq 'Y'} checked="checked"{/if} />
        </td>
</tr>


<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax2">
<td class="FormButton" width="20%">Distributor email</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="email" value="{$manufacturer.email}" size="50" style="width: 80%;" /></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax6">
<td class="FormButton" width="20%">Distributor subject line</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_subject_line_8" value="{$manufacturer.d_subject_line_8}" size="50" style="width: 80%;" /></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax4">
<td class="FormButton">{* {$lng.lbl_message_body}*} Message to distributor</td>
<td>&nbsp;</td>
<td><textarea {if $distributor_section eq "8"}class="new_editor"{/if} name="mess_body" rows="20" cols="60" style="width: 80%;">{$manufacturer.mess_body}</textarea></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax9">
<td class="FormButton">Dispatch instructions</td>
<td>&nbsp;</td>
<td><textarea {if $distributor_section eq "8"}class="new_editor"{/if} name="d_dispatch_instructions" rows="10" cols="60" style="width: 80%;">{$manufacturer.d_dispatch_instructions}</textarea></td>
</tr>


<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax5">
<td class="FormButton">Shipping options (use comma to separate)</td>
<td>&nbsp;</td>
<td><input type="text"  name="d_shipping_options" value="{$manufacturer.d_shipping_options}" size="50" style="width: 80%;" /></td>
</tr>


</table>


{elseif $d_section.distributor_section == 5}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_5" {if $distributor_section ne "5"}style="display: none;" {/if}>
    <tr>
        <td width="20%" class="FormButton">Product catalog URL</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" size="50" name="d_product_catalog" value="{$distributorModel->d_product_catalog}" style="width:40%"/>
            {if $distributorModel->d_product_catalog !== ''}<a href="{$distributorModel->d_product_catalog}" target="_blank">Open</a>{/if}
            <a title="{$lng.help_dx_catalog_url_text|htmlspecialchars|default:help_dx_catalog_url_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Price-list URL</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" size="50" name="d_price_list" value="{$distributorModel->d_price_list}" style="width:40%"/>
            {if $distributorModel->d_price_list !== ''}<a href="{$distributorModel->d_price_list}" target="_blank">Open</a>{/if}
            <a title="{$lng.help_dx_price_list_text|htmlspecialchars|default:help_dx_price_list_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td nowrap="nowrap" class="FormButton">Distributor currency</td>
        <td>&nbsp;</td>
        <td>
            <select name="d_currency">
                {foreach from=$currencies item=currency}
                    <option {if $distributorModel->d_currency === $currency->currency_id}selected="selected"{/if} value="{$currency->currency_id}">{$currency->currency_code}</option>
                {/foreach}
            </select>
            <a title="{$lng.help_dx_currency_text|htmlspecialchars|default:help_dx_currency_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">{$lng.lbl_cost_to_us}&nbsp;=</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" size="9" name="cost_to_us_coef_x" value="{$distributorModel->cost_to_us_coef_x}"/>&nbsp;*&nbsp;{$lng.lbl_list_price}
            <a title="{$lng.help_dx_cost_to_us_text|htmlspecialchars|default:help_dx_cost_to_us_text}" class="tooltip">
            <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td class="FormButton">{$lng.lbl_price}&nbsp;=</td>
        <td>&nbsp;</td>
        <td>{*(&nbsp;<input type="text" pattern="^[1-9][0-9\.]*$" title="Value must be greater than or equal to 1" size="9" name="price_coef_x" value="{$distributorModel->price_coef_x}"/>
            &nbsp;*&nbsp;{$lng.lbl_cost_to_us}&nbsp;+&nbsp;
            <input type="text" size="9" name="price_coef_y" value="{$distributorModel->price_coef_y}"/>&nbsp;)&nbsp;/&nbsp;
            <input type="text" size="9" name="price_coef_z" value="{$distributorModel->price_coef_z}"/>*}
            calculated by our algorithm
            <a title="{$lng.help_dx_price_text|htmlspecialchars|default:help_dx_price_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">MAP policy</td>
        <td>&nbsp;</td>
        <td width="80%">
            <select name="d_map_policy" id="d_map_policy"
                    onchange="{literal} if (this.value !=''){$('#tr_d_map_prices').show();}else{$('#tr_d_map_prices').hide();}{/literal}">
                <option value="">N/A</option>
                <option value="applies_to_selected_products"{if $distributorModel->d_map_policy === "applies_to_selected_products"} selected="selected"{/if}>
                    applies to selected products
                </option>
                <option value="applies_to_all_products"{if $distributorModel->d_map_policy === "applies_to_all_products"} selected="selected"{/if}>
                    applies to all products
                </option>
            </select>
            <a title="{$lng.help_dx_map_text|htmlspecialchars|default:help_dx_map_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr id="tr_d_map_prices" {if !$distributorModel->d_map_policy}style="display: none;"{/if}>
        <td width="20%" class="FormButton">MAP prices URL</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" size="50" name="d_map_prices" value="{$distributorModel->d_map_prices}" style="width:40%"/>
            {if $distributorModel->d_map_prices}<a href="{$distributorModel->d_map_prices}" target="_blank">Open</a>{/if}
            <a title="{$lng.help_dx_map_price_url_text|htmlspecialchars|default:help_dx_map_price_url_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td class="FormButton">MAP price&nbsp;=</td>
        <td>&nbsp;</td>
        <td>
            <input type="text" size="9" name="new_map_price_coef_x" value="{$distributorModel->new_map_price_coef_x}"/>&nbsp;*&nbsp;{$lng.lbl_list_price}
            <a title="{$lng.help_dx_map_price_text|htmlspecialchars|default:help_dx_map_price_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td nowrap="nowrap" class="FormButton">Distributor product price multiplier</td>
        <td>&nbsp;</td>
        <td>
            <input type="text" size="9" name="supplier_products_price_multiplier" value="{$distributorModel->supplier_products_price_multiplier}"/>
            <a title="{$lng.help_dx_price_multiplier_text|htmlspecialchars|default:help_dx_price_multiplier_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>

</table>
{elseif $d_section.distributor_section == 7}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_7" {if $distributor_section ne "7"}style="display: none;" {/if}>
    <tr>
        <td width="20%" class="FormButton">Distributor ships to/within</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" size="50" name="d_ships_to_within" value="{$distributorModel->d_ships_to_within}" style="width:80%"/>
            <a title="{$lng.help_dx_ships_to_text|htmlspecialchars|default:help_dx_ships_to_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td class="FormButton">Shipping methods used by distributor</td>
        <td>&nbsp;</td>
        <td width="80%">
            {if $distributorModel}
                {assign var=carriers value=$distributorModel->carriers->order("orderby")}
                {assign var=ccc value=$carriers->valuesList("carrier_id", true)}
            {/if}
            <select name="distributor_carrier[]" multiple class="select2" style="width: 80%">
                {foreach from=$trackingLinksCarriers item=carrier}
                    <option value="{$carrier->carrier_id}" {if in_array($carrier->carrier_id, $ccc)}selected="selected"{/if}>{$carrier->carrier}</option>
                {/foreach}
            </select>
            <a title="{$lng.help_dx_shipping_methods_text|htmlspecialchars|default:help_dx_shipping_methods_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Dx to Cx lead time (business days):</td>
        <td>&nbsp;</td>
        <td width="80%">
            from <input type="text" size="3" name="dx_leadtime" value="{$manufacturer.dx_leadtime}"/>
            to <input type="text" size="3" name="dx_leadtime_to" value="{$manufacturer.dx_leadtime_to}"/>
            <a title="{$lng.help_dx_to_cx_lead_text|htmlspecialchars|default:help_dx_to_cx_lead_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Amazon to Cx lead time to ship for MFN orders (business days):</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" size="3" name="amazon_leadtime_to_ship" value="{$manufacturer.amazon_leadtime_to_ship}"/>
            <a title="{$lng.help_amazon_to_cx_lead_text|htmlspecialchars|default:help_amazon_to_cx_lead_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Dx to Amazon lead time (DLT) for FBA loads (days):</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" size="3" name="amazon_leadtime_for_fba_loads" value="{$manufacturer.amazon_leadtime_for_fba_loads}"/>
            <a title="{$lng.help_dx_to_amazon_lead_text|htmlspecialchars|default:help_dx_to_amazon_lead_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <hr/>
        <td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Distributor offers free shipping</td>
        <td>&nbsp;</td>
        <td width="80%">
            <div style="float: left;">
                <select name="distributor_offers_free_shipping" id="distributor_offers_free_shipping"
                        onchange="javasript:{literal} if (this.value =='on_orders_over'){$('#free_shipping_on_orders_over_value').show();}else{$('#free_shipping_on_orders_over_value').hide();}{/literal}">
                    <option value="never"{if $manufacturer.distributor_offers_free_shipping eq "never"} selected="selected"{/if}>
                        never
                    </option>
                    <option value="on_orders_over"{if $manufacturer.distributor_offers_free_shipping eq "on_orders_over"} selected="selected"{/if}>
                        on orders over
                    </option>
                </select>
            </div>

            <div style="float: left; {if $distributorModel->distributor_offers_free_shipping !== "on_orders_over"}display: none;{/if}"
                 id="free_shipping_on_orders_over_value">
                &nbsp; {$dCurrency->symbol_prefix}{$dCurrency} <input type="text" name="free_shipping_on_orders_over_value" value="{$distributorModel->free_shipping_on_orders_over_value}" size="7"/>
            </div>
            <a title="{$lng.help_dx_offers_free_text|htmlspecialchars|default:help_dx_offers_free_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <hr/>
        <td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Warehouse pickups are allowed?</td>
        <td>&nbsp;</td>
        <td width="80%">
            <select name="warehouse_pickups_are_allowed" id="warehouse_pickups_are_allowed">
                <option value="N"{if $manufacturer.warehouse_pickups_are_allowed eq "N"} selected="selected"{/if}>No
                </option>
                <option value="Y"{if $manufacturer.warehouse_pickups_are_allowed eq "Y"} selected="selected"{/if}>Yes
                </option>
            </select>
            <a title="{$lng.help_dx_warehouse_pickups_text|htmlspecialchars|default:help_dx_warehouse_pickups_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Drop-ship fee</td>
        <td>&nbsp;</td>
        <td width="80%">
            <select name="d_drop_ship_fee_select" id="d_drop_ship_fee_select"
                    onchange="{literal} if (this.value !==''){$('#tr_d_drop_ship_fee_in_us').show();}else{$('#tr_d_drop_ship_fee_in_us').hide();}{/literal}"
            >
                <option value="">N/A</option>
                <option value="applies_to_all_orders"{if $manufacturer.d_drop_ship_fee_select eq "applies_to_all_orders"} selected="selected"{/if}>
                    applies to all orders
                </option>
                <option value="applies_to_orders_below_minimum_order_amount_only"{if $manufacturer.d_drop_ship_fee_select eq "applies_to_orders_below_minimum_order_amount_only"} selected="selected"{/if}>
                    applies to orders below minimum order amount only
                </option>
            </select>
            <a title="{$lng.help_dx_dropship_fee_text|htmlspecialchars|default:help_dx_dropship_fee_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr id="tr_d_drop_ship_fee_in_us" {if $manufacturer.d_drop_ship_fee_select eq ""}style="display: none;"{/if}>
        <td width="20%" class="FormButton">Drop-ship fee in {$dCurrency->symbol_prefix}{$dCurrency}</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" name="d_drop_ship_fee_in_us" value="{$manufacturer.d_drop_ship_fee_in_us}" size="7"/>
            <a title="{$lng.help_dx_dropship_fee_price_text|htmlspecialchars|default:help_dx_dropship_fee_price_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Minimum order amount</td>
        <td>&nbsp;</td>
        <td width="80%">
            <select name="d_minimum_order_amount" id="d_minimum_order_amount"
                    onchange="{literal} if (this.value !==''){$('#tr_d_minimum_order_amount_in_us').show(); $('#tr_d_for_orders_below_min_order_amount').show();}else{$('#tr_d_minimum_order_amount_in_us').hide(); $('#tr_d_for_orders_below_min_order_amount').hide(); $('#tr_d_dealer_discount_reduced_from').hide(); $('#d_for_orders_below_min_order_amount').val('are_rejected')}{/literal}">
                <option value="">N/A</option>
                <option value="applies_to_all_orders"{if $manufacturer.d_minimum_order_amount eq "applies_to_all_orders"} selected="selected"{/if}>
                    applies to all orders
                </option>
            </select>
            <a title="{$lng.help_dx_minimum_order_amount_text|htmlspecialchars|default:help_dx_minimum_order_amount_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>

    <tr id="tr_d_minimum_order_amount_in_us" {if $manufacturer.d_minimum_order_amount eq ""}style="display: none;"{/if}>
        <td width="20%" class="FormButton">Minimum order amount in {$dCurrency->symbol_prefix}{$dCurrency}</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="text" name="d_minimum_order_amount_in_us" value="{$manufacturer.d_minimum_order_amount_in_us}" size="7"/>
            <a title="{$lng.help_dx_minimum_order_amount_price_text|htmlspecialchars|default:help_dx_minimum_order_amount_price_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>

    <tr id="tr_d_for_orders_below_min_order_amount"
        {if $manufacturer.d_minimum_order_amount eq ""}style="display: none;"{/if}>
        <td width="20%" class="FormButton">(For) orders below minimum order amount</td>
        <td>&nbsp;</td>
        <td width="80%">
            <select name="d_for_orders_below_min_order_amount" id="d_for_orders_below_min_order_amount"
                    onchange="javasript:{literal} if (this.value == 'dealer_discount_is_reduced'){$('#tr_d_dealer_discount_reduced_from').show();}else{$('#tr_d_dealer_discount_reduced_from').hide();}{/literal}">
                <option value="are_rejected"{if $manufacturer.d_for_orders_below_min_order_amount eq "are_rejected"} selected="selected"{/if}>
                    are rejected
                </option>
                <option value="drop_ship_fee_is_applied"{if $manufacturer.d_for_orders_below_min_order_amount eq "drop_ship_fee_is_applied"} selected="selected"{/if}>
                    drop-ship fee is applied
                </option>
                <option value="dealer_discount_is_reduced"{if $manufacturer.d_for_orders_below_min_order_amount eq "dealer_discount_is_reduced"} selected="selected"{/if}>
                    dealer discount is reduced
                </option>
            </select>
            <a title="{$lng.help_dx_below_minimum_order_text|htmlspecialchars|default:help_dx_below_minimum_order_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr id="tr_d_dealer_discount_reduced_from"
        {if $manufacturer.d_for_orders_below_min_order_amount === "dealer_discount_is_reduced" && $manufacturer.d_minimum_order_amount != ''}{else}style="display: none;"
        {/if}>
        <td width="20%" class="FormButton"></td>
        <td>&nbsp;</td>
        <td width="80%">
            from <input type="text" name="d_dealer_discount_reduced_from" value="{$manufacturer.d_dealer_discount_reduced_from}" size="7"/>%
            <a title="{$lng.help_dx_discount_reduced_text_from|htmlspecialchars|default:help_dx_discount_reduced_text_from}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i></a>to
            <input type="text" name="d_dealer_discount_reduced_to" value="{$manufacturer.d_dealer_discount_reduced_to}" size="7"/>%
            <a title="{$lng.help_dx_discount_reduced_text_to|htmlspecialchars|default:help_dx_discount_reduced_text_to}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <hr/>
        <td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Force ASR (approximate shipping rates) update</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input type="checkbox" name="update_approximation_shipping_rates" value="Y"{if $manufacturer.update_approximation_shipping_rates eq 'Y'} checked="checked"{/if} />
            <a title="{$lng.help_dx_update_approximate_shipping_rates_text|htmlspecialchars|default:help_dx_update_approximate_shipping_rates_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    <tr>
        <td width="20%" class="FormButton">Date and time of the last ASR update</td>
        <td>&nbsp;</td>
        <td width="80%">
            <input readonly="readonly" type="text" size="50" name="shipping_rates_last_update_date"
                   value="{if $manufacturer.shipping_rates_last_update_date gt "0"}{$manufacturer.shipping_rates_last_update_date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'}{/if}"/>
            <a title="{$lng.help_dx_date_approximate_shippings_text|htmlspecialchars|default:help_dx_date_approximate_shippings_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
</table>
<script>
    {literal}
    $('.select2').select2({
        allowClear: true,
        closeOnSelect: false,
        placeholder: 'Click to select'
    });
    {/literal}
</script>
{elseif $d_section.distributor_section == 9}
    <table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_9"
           {if $distributor_section != 9}style="display: none;" {/if}>
        <tr>
            <td width="20%" class="FormButton">
                Distributor charges sales/VAT taxes in the following states/provinces
            </td>
            <td>&nbsp;</td>
            <td width="80%">
                <input type="text" size="50" name="d_tax_policy_in_states" value="{$manufacturer.d_tax_policy_in_states}" style="width:80%"/>
            </td>
        </tr>
    </table>
{elseif $d_section.distributor_section eq "10"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_10" {if $distributor_section ne "10"}style="display: none;" {/if}>
    <tr>
        <td width="20%" class="FormButton">Warranty period starts when the order is</td>
        <td>&nbsp;</td>
        <td width="80%">
            <select name="d_warranty_starts_when_order_is" id="d_warranty_starts_when_order_is">
                <option value="shipped"{if $manufacturer.d_warranty_starts_when_order_is eq "shipped"} selected="selected"{/if}>
                    shipped
                </option>
                <option value="received_by_customer"{if $manufacturer.d_warranty_starts_when_order_is eq "received_by_customer"} selected="selected"{/if}>
                    received by the customer
                </option>
            </select>

            and lasts <input type="text" name="d_warranty_last_day" value="{$manufacturer.d_warranty_last_day}"
                             size="5"/> days
        </td>
    </tr>

    <tr>
        <td width="20%" class="FormButton">Re-stocking fee for authorized returns</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" name="d_re_stocking_fee_for_authorized_returns"
                               value="{$manufacturer.d_re_stocking_fee_for_authorized_returns}" size="7"/>%
        </td>
    </tr>

    <tr>
        <td width="20%" class="FormButton">Re-stocking fee for unauthorized returns</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" name="d_re_stocking_fee_for_unauthorized_returns"
                               value="{$manufacturer.d_re_stocking_fee_for_unauthorized_returns}" size="7"/>%
        </td>
    </tr>

    <tr>
        <td width="20%" class="FormButton">Distributor return policy</td>
        <td>&nbsp;</td>
        <td width="80%">
            <textarea {if $distributor_section eq "10"}class="new_editor"{/if} name="d_distributor_return_policy"
                      rows="20" cols="60" style="width: 80%;">{$manufacturer.d_distributor_return_policy}</textarea>
        </td>
    </tr>


<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
        var geo_litecity_location_city = "";
        var geo_litecity_location_region = "";
        var geo_litecity_location_country = "";
        var geo_litecity_location_region_name = "";
{/literal}
//]]>
</script>

{include file="modules/Manufacturers/check_zipcode.tpl"}
{include file="generate_required_fields_js.tpl"}
{include file="check_required_fields_js.tpl"}
{include file="change_states_js.tpl"}

{if $manufacturer.distributor_return_addresses ne ""}

<input type="hidden" id="delete_distributor_return_address_number" name="delete_distributor_return_address_number" value="" />

{foreach from=$manufacturer.distributor_return_addresses item=v_a key=k_a}
<tr><td colspan="3"><hr /></td></tr>
<tr>
<td class="FormButton" width="20%">Warehouse name</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="warehouse_name_{$v_a.id}" name="warehouse_name_{$v_a.id}" size="32" value="{$v_a.warehouse_name}" />
&nbsp;&nbsp;&nbsp;
<input type="button" value="Delete" onclick="javascript: {literal}$('#mode').val('delete_distributor_return_address'); $('#delete_distributor_return_address_number').val('{/literal}{$v_a.id}{literal}'); document.manufacturer.submit();"{/literal} />
</td>
</tr>

<tr>
<td class="FormButton" width="20%">Full name</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="full_name_{$v_a.id}" name="full_name_{$v_a.id}" size="32" value="{$v_a.full_name}" />
</td>
</tr>

<tr>
<td class="FormButton" width="20%">Company</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="company_{$v_a.id}" name="company_{$v_a.id}" size="32" value="{$v_a.company}" />
</td>
</tr>

<tr>
<td class="FormButton" width="20%">{$lng.lbl_address}</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="address_{$v_a.id}" name="address_{$v_a.id}" size="32" maxlength="64" value="{$v_a.address}" />
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_address_2}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="address_2_{$v_a.id}" name="address_2_{$v_a.id}" size="32" maxlength="64" value="{$v_a.address_2}" />
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_city}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="city_{$v_a.id}" name="city_{$v_a.id}" size="32" maxlength="64" value="{$v_a.city}" />
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_country}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<select name="country_{$v_a.id}" id="country_{$v_a.id}" onchange="check_zip_code()">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $v_a.country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $v_a.country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_state}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
{include file="main/states.tpl" states=$states name="state_`$v_a.id`" default=$v_a.state default_country=$v_a.country country_name="country_`$v_a.id`"}
</td>
</tr>

<tr style="display: none;">
<td>
{include file="main/register_states.tpl" state_name="state_`$v_a.id`" country_name="country_`$v_a.id`" county_name="county_`$v_a.id`" state_value=$v_a.state county_value=$v_a.county}
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_zip_code}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="zipcode_{$v_a.id}" name="zipcode_{$v_a.id}" size="32" maxlength="32" value="{$v_a.zipcode}" onchange="check_zip_code()"  />
</td>
</tr>

<tr>
<td class="FormButton" width="20%">Phone</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="phone_{$v_a.id}" name="phone_{$v_a.id}" size="32" maxlength="64" value="{$v_a.phone}" />
<b>ext</b>
<input type="text" id="ext_{$v_a.id}" name="ext_{$v_a.id}" size="3" value="{$v_a.ext}" />
</td>
</tr>


{/foreach}
<tr><td colspan="3"><hr /></td></tr>
{/if}
</table>


{elseif $d_section.distributor_section eq "11"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_11" {if $distributor_section ne "11"}style="display: none;" {/if}>
    <tr><td colspan="3"><br />{include file="main/subheader.tpl" title="Payment to distributor arrangements"}</td></tr>
<tr>
        <td width="20%" class="FormButton">We pay to distributor by</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="d_we_pay_to_distributor_by" id="d_we_pay_to_distributor_by">
<option value="credit_card"{if $manufacturer.d_we_pay_to_distributor_by eq "credit_card"} selected="selected"{/if}>credit card</option>
<option value="paypal_balance"{if $manufacturer.d_we_pay_to_distributor_by eq "paypal_balance"} selected="selected"{/if}>PayPal balance</option>
<option value="check"{if $manufacturer.d_we_pay_to_distributor_by eq "check"} selected="selected"{/if}>check</option>
</select>
        </td>
</tr>

<tr>
        <td width="20%" class="FormButton">If we pay to distributor by</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton">

<select name="d_pay_to_distributor_by" id="d_pay_to_distributor_by">
<option value=""></option>
<option value="check"{if $manufacturer.d_pay_to_distributor_by eq "check"} selected="selected"{/if}>check</option>
<option value="EFT"{if $manufacturer.d_pay_to_distributor_by eq "EFT"} selected="selected"{/if}>electronic funds transfer (EFT)</option>
</select>
,

<select name="d_pay_to_distributor_save_text" id="d_pay_to_distributor_save_text">
<option value="">we didn't check if we can save</option>
<option value="we_can_save"{if $manufacturer.d_pay_to_distributor_save_text eq "we_can_save"} selected="selected"{/if}>we can save</option>
<option value="we_cannot_save"{if $manufacturer.d_pay_to_distributor_save_text eq "we_cannot_save"} selected="selected"{/if}>we can't save</option>
</select>

<input type="text" name="d_we_can_save" value="{$manufacturer.d_we_can_save}" size="4" />%
        </td>
</tr>

    <tr>
        <td width="20%" class="FormButton">NET payment terms in days (put 0 if N/A)</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton">NET<input type="text" name="d_net_payment_terms_in_days" value="{$manufacturer.d_net_payment_terms_in_days}" /></td>
    </tr>


<tr><td colspan="3"><br />{include file="main/subheader.tpl" title="Distributor checking account details"}</td></tr>

<tr>
<td class="FormButton" width="20%">Bank name:</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="dcad_bank_name" name="dcad_bank_name" size="32" maxlength="64" value="{$manufacturer.dcad_bank_name}" />
</td>
</tr>

<tr>
<td class="FormButton" width="20%">{$lng.lbl_address}</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">&nbsp;</td>
</tr>


{*
<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
        var geo_litecity_location_city = "";
        var geo_litecity_location_region = "";
        var geo_litecity_location_country = "";
        var geo_litecity_location_region_name = "";
{/literal}
//]]>
</script>

{include file="modules/Manufacturers/check_zipcode.tpl"}
{include file="generate_required_fields_js.tpl"}
{include file="check_required_fields_js.tpl"}
{include file="change_states_js.tpl"}
*}

<tr>
<td class="FormButton" width="20%">{$lng.lbl_address}</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="dcad_address" name="dcad_address" size="32" maxlength="64" value="{$manufacturer.dcad_address}" />
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_address_2}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="dcad_address_2" name="dcad_address_2" size="32" maxlength="64" value="{$manufacturer.dcad_address_2}" />
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_city}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="dcad_city" name="dcad_city" size="32" maxlength="64" value="{$manufacturer.dcad_city}" />
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_country}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<select name="dcad_country" id="dcad_country" onchange="check_zip_code()">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $manufacturer.dcad_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $manufacturer.dcad_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_state}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
{include file="main/states.tpl" states=$states name="dcad_state" default=$manufacturer.dcad_state default_country=$manufacturer.dcad_country country_name="dcad_country"}
</td>
</tr>

<tr style="display: none;">
<td>
{include file="main/register_states.tpl" state_name="dcad_state" country_name="dcad_country" county_name="dcad_county" state_value=$manufacturer.dcad_state county_value=$manufacturer.dcad_county}
</td>
</tr>

<tr>
<td class="FormButton">{$lng.lbl_zip_code}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="dcad_zipcode" name="dcad_zipcode" size="32" maxlength="32" value="{$manufacturer.dcad_zipcode}" onchange="check_zip_code()"  />
</td>
</tr>

<tr>
<td class="FormButton" width="20%">Company name:</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="dcad_company_name" name="dcad_company_name" size="32" maxlength="64" value="{$manufacturer.dcad_company_name}" />
</td>
</tr>

<tr>
<td class="FormButton" width="20%">Routing number:</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="dcad_routing_number" name="dcad_routing_number" size="32" maxlength="64" value="{$manufacturer.dcad_routing_number}" />
</td>
</tr>

<tr>
<td class="FormButton" width="20%">Account number:</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="dcad_account_number" name="dcad_account_number" size="32" maxlength="64" value="{$manufacturer.dcad_account_number}" />
</td>
</tr>
    <tr><td colspan="3"><br />{include file="main/subheader.tpl" title="Reconciliation settings"}</td></tr>
<tr>
        <td width="20%" class="FormButton">Bulk or individual order payments</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="d_bulk_or_individual_order_payments" id="d_bulk_or_individual_order_payments">
<option value="distributor_charges_for_each_order_separately"{if $manufacturer.d_bulk_or_individual_order_payments eq "distributor_charges_for_each_order_separately"} selected="selected"{/if}>distributor charges for each order separately</option>
<option value="distributor_may_charge_for_several_orders_at_once"{if $manufacturer.d_bulk_or_individual_order_payments eq "distributor_may_charge_for_several_orders_at_once"} selected="selected"{/if}>distributor may charge for several orders at once</option>
<option value="distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping"{if $manufacturer.d_bulk_or_individual_order_payments eq "distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping"} selected="selected"{/if}>distributor charges for each order twice: one charge for products and one charge for shipping</option>
</select>
        </td>
</tr>
{if $manufacturer.d_bulk_or_individual_order_payments eq "distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping"}
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="FormButton" width="80%">
            <label style="position: relative; bottom: 3px;" for="distributor_charges_for_each_order_twice_and_split_invoices" >Split invoices (by Cost + Tax and Shipping)</label>
            <input id="distributor_charges_for_each_order_twice_and_split_invoices" type="checkbox" name="distributor_charges_for_each_order_twice_and_split_invoices" {if $manufacturer.distributor_charges_for_each_order_twice_and_split_invoices == 'Y'}checked="checked"{/if} value="Y"/>
        </td>
    </tr>
{/if}

<tr>
        <td width="20%" class="FormButton">Search keyphrase for reconciliation</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton"><input type="text" name="d_search_keyphrase_for_reconciliation" value="{$manufacturer.d_search_keyphrase_for_reconciliation}" style="width: 80%;" />
<br />
use &lt;OR&gt; separator if distributor charges under more than one keyphrase
	</td>
</tr>

</table>


{elseif $d_section.distributor_section == 12}
    <table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_12"
           {if $distributor_section != 12}style="display: none;" {/if}>
        <tr>
            <td width="20%" class="FormButton">Tracking number is</td>
            <td>&nbsp;</td>
            <td width="80%">
                <input type="checkbox" name="d_available_on_distributor_site_checkbox" value="Y"{if $manufacturer.d_available_on_distributor_site_checkbox eq 'Y'} checked="checked"{/if} />
                available on distributor website
                <input style="width:40%" type="text" name="d_available_on_distributor_site_url" value="{$manufacturer.d_available_on_distributor_site_url}"/>
                {if $manufacturer.d_available_on_distributor_site_url ne ""}
                    <a target="_blank" href="{$manufacturer.d_available_on_distributor_site_url}">link</a>
                {/if}
                <br/>
                <input type="checkbox" name="d_sent_by_email_to" value="Y"{if $manufacturer.d_sent_by_email_to eq 'Y'} checked="checked"{/if} />
                sent by email to
                <input style="width:40%" type="text" name="d_sent_by_email_to_email_address" value="{$manufacturer.d_sent_by_email_to_email_address}"/>
                <br/>
                <input type="checkbox" name="d_put_on_the_invoices" value="Y"{if $manufacturer.d_put_on_the_invoices eq 'Y'} checked="checked"{/if} />
                put on the invoice
            </td>
        </tr>
    </table>

{elseif $d_section.distributor_section eq "13"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_13" {if $distributor_section ne "13"}style="display: none;" {/if}>
<tr>
        <td width="20%" class="FormButton">Distributor invoices are</td>
        <td>&nbsp;</td>
        <td width="80%">
<input type="checkbox" name="d_invoices_sent_by_email_to" value="Y"{if $manufacturer.d_invoices_sent_by_email_to eq 'Y'} checked="checked"{/if} />sent by email to
<input style="width:40%" type="text" name="d_invoices_sent_to" value="{$manufacturer.d_invoices_sent_to}" />
<br/>

<input type="checkbox" name="d_invoices_sent_by_fax_to" value="Y"{if $manufacturer.d_invoices_sent_by_fax_to eq 'Y'} checked="checked"{/if} />sent by fax to
<input type="text" name="d_invoices_by_fax_sent_to" value="{$manufacturer.d_invoices_by_fax_sent_to}" />
<br/>

<input type="checkbox" name="d_invoices_mailed_to_our_checkbox" value="Y"{if $manufacturer.d_invoices_mailed_to_our_checkbox eq 'Y'} checked="checked"{/if} />mailed to our
<select name="d_invoices_mailed_to_our" id="d_invoices_mailed_to_our">
<option value="canada"{if $manufacturer.d_invoices_mailed_to_our eq "canada"} selected="selected"{/if}>Canadian address</option>
<option value="usa"{if $manufacturer.d_invoices_mailed_to_our eq "usa"} selected="selected"{/if}>US address</option>
</select>

        </td>
</tr>
</table>

{elseif $d_section.distributor_section eq "14"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_14" {if $distributor_section ne "14"}style="display: none;" {/if}>
<tr>
        <td width="20%" class="FormButton">Availability must be checked before order is dispatched for fulfillment</td>
        <td>&nbsp;</td>
        <td width="80%">
<input type="checkbox" name="d_availability_must_be_checked" value="Y"{if $manufacturer.d_availability_must_be_checked eq 'Y'} checked="checked"{/if}
onclick="javasript:{literal} if (this.checked){$('#tr_d_send_to_email_14').show(); $('#tr_d_webpage_properties').show(); $('#tr_d_message_body_14').show(); $('#tr_d_email_subject_14').show(); $('#tr_info_14').show();}else{$('#tr_d_send_to_email_14').hide(); $('#tr_d_message_body_14').hide(); $('#tr_d_email_subject_14').hide(); $('#tr_info_14').hide(); $('#tr_d_webpage_properties').hide();}{/literal}"
/>
	</td>
</tr>

<tr id="tr_info_14" {if $manufacturer.d_availability_must_be_checked ne 'Y'}style="display: none;"{/if}>
        <td colspan="3">{$lng.txt_distributor_section_14}</td>
</tr>

<tr id="tr_d_send_to_email_14" {if $manufacturer.d_availability_must_be_checked ne 'Y'}style="display: none;"{/if}>
        <td width="20%" class="FormButton">'Send to' email</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton"><input type="text" name="d_send_to_email_14" value="{$manufacturer.d_send_to_email_14|default:'essv50@gmail.com'}" style="width:80%" /></td>
</tr>

<tr id="tr_d_email_subject_14" {if $manufacturer.d_availability_must_be_checked ne 'Y'}style="display: none;"{/if}>
        <td width="20%" class="FormButton">Subject line</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton"><input type="text" name="d_email_subject_14" value="{$manufacturer.d_email_subject_14}" style="width:80%" /></td>
</tr>

<tr id="tr_d_message_body_14" {if $manufacturer.d_availability_must_be_checked ne 'Y'}style="display: none;"{/if}>
	<td class="FormButton">Message body</td>
	<td>&nbsp;</td>
	<td>
	<textarea {if $distributor_section eq "14"}class="new_editor"{/if} name="d_message_body_14" rows="20" cols="60" style="width:80%">{$manufacturer.d_message_body_14|default:$lng.txt_distributor_section_14_email_body}</textarea>

<br />Add the following Attention tag

                <select name="add_ca_status_id">
                <option value="0">add nothing</option>
                        {foreach from=$ca_statuses item=item_v key=key_k}
                            <option value="{$item_v.status_id}"
                              {if $item_v.status_id eq $manufacturer.add_ca_status_id}
                                selected="selected"
                              {/if}
                            >{$item_v.status}</option>
                        {/foreach}
                </select>

	</td>
</tr>

<tr id="tr_d_webpage_properties" {if $manufacturer.d_availability_must_be_checked ne 'Y'}style="display: none;"{/if}>
	<td colspan="3">
	<table cellpadding="0" cellspacing="0" width="100%">

	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3" style="color: #000000;"><B>{literal}{{webpagebutton}}{/literal} webpage properties</B></td></tr>
	<tr><td colspan="3" class="SubHeaderBlackLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="2">&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<B>Corresponding template names<B></td></tr>

	<tr>
	<td width="21%" class="FormButton">Show header</td>
	<td width="5"><input type="checkbox" name="d_sec14_show_header" value="Y" {if $manufacturer.d_sec14_show_header eq 'Y'} checked="checked"{/if} /></td>
	<td width="*" style="font-size: 8px;">&nbsp;&nbsp;
	http://www.artistsupplysource.com/admin/file_edit.php?dir=%2Fcustomer%2Fmain&file=%2Fcustomer%2Fmain%2Fstock_availability_header.tpl
	</td>
	</tr>

        <tr>
        <td class="FormButton">Show {literal}{{items-stock}}{/literal}</td>
        <td><input type="checkbox" name="d_sec14_show_items_stock" value="Y" {if $manufacturer.d_sec14_show_items_stock eq 'Y'} checked="checked"{/if} /></td>
        <td style="font-size: 8px;">&nbsp;&nbsp;
	http://www.artistsupplysource.com/admin/file_edit.php?dir=%2Fcustomer%2Fmain&file=%2Fcustomer%2Fmain%2Fstock_availability_items_stock.tpl
        </td>
        </tr>

        <tr>
        <td class="FormButton">Show {literal}{{shipto}}{/literal}</td>
        <td><input type="checkbox" name="d_sec14_show_shipto" value="Y" {if $manufacturer.d_sec14_show_shipto eq 'Y'} checked="checked"{/if} /></td>
        <td style="font-size: 8px;">&nbsp;&nbsp;
	http://www.artistsupplysource.com/admin/file_edit.php?dir=%2Fcustomer%2Fmain&file=%2Fcustomer%2Fmain%2Fstock_availability_shipto.tpl
        </td>
        </tr>

        <tr>
        <td class="FormButton">Show {literal}{{items-cost}}{/literal}</td>
        <td><input type="checkbox" name="d_sec14_show_items_cost" value="Y" {if $manufacturer.d_sec14_show_items_cost eq 'Y'} checked="checked"{/if} /></td>
        <td style="font-size: 8px;">&nbsp;&nbsp;
	http://www.artistsupplysource.com/admin/file_edit.php?dir=%2Fcustomer%2Fmain&file=%2Fcustomer%2Fmain%2Fstock_availability_items_cost.tpl
        </td>
        </tr>

        <tr>
        <td class="FormButton">Show footer</td>
        <td><input type="checkbox" name="d_sec14_show_footer" value="Y" {if $manufacturer.d_sec14_show_footer eq 'Y'} checked="checked"{/if} /></td>
        <td style="font-size: 8px;">&nbsp;&nbsp;
	http://www.artistsupplysource.com/admin/file_edit.php?dir=%2Fcustomer%2Fmain&file=%2Fcustomer%2Fmain%2Fstock_availability_footer.tpl
        </td>
        </tr>

	</table>
	</td>
</tr>
    <tr>
        <td colspan="3">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="color: #000000;"><B>Availability request schedule</B></td>
                </tr>
                <tr>
                    <td colspan="3" class="SubHeaderBlackLine">
                        <img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">{$lng.lbl_server_min_distributor_time}</td>
                </tr>

                <tr>
                    <td width="20%" class="FormButton">Server time - Distributor time</td>
                    <td>&nbsp;</td>
                    <td width="80%" class="FormButton">
                        <input type="text" name="d_server_min_distributor_time" value="{$distributorModel->d_server_min_distributor_time}" style="width:10%"/>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

{elseif $d_section.distributor_section == 15}
    <table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_15"
        {if $distributor_section ne "15"}style="display: none;" {/if}>
        <tr>
            <td width="40%" class="FormButton">{$lng.lbl_quick_links_1}</td>
            <td>&nbsp;</td>
            <td width="60%">
                <input type="text" size="50" name="d_website_search_for_sku_url" value="{$distributorModel->d_website_search_for_sku_url}" style="width:80%"/>
                <a title="{$lng.help_dx_search_for_sku_url_text|htmlspecialchars|default:help_dx_search_for_sku_url_text}" class="tooltip">
                    <i class="fa fa-question-circle pointer"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td width="40%" class="FormButton">{$lng.lbl_quick_links_2}</td>
            <td>&nbsp;</td>
            <td width="60%">
                <input type="text" size="50" name="d_link_to_order_distributors_website" value="{$distributorModel->d_link_to_order_distributors_website}" style="width:80%"/>
                <a title="{$lng.help_dx_link_to_order_text|htmlspecialchars|default:help_dx_link_to_order_text}" class="tooltip">
                    <i class="fa fa-question-circle pointer"></i>
                </a>
            </td>
        </tr>
    </table>

{elseif $d_section.distributor_section == 16}
{if $manufacturer.distributor_contacts ne ""}
{foreach from=$manufacturer.distributor_contacts item=item key=key}
{if $item.pq eq "Y"}
    <table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_16"
           {if $distributor_section ne "16"}style="display: none;" {/if}>
        <tr>
            <td width="20%" class="FormButton">Product question name:</td>
            <td>&nbsp;</td>
            <td width="80%">
                <input type="text" size="50" name="d_product_questions_send_to_name" value="{$item.contact_name|escape:"html"}" readonly="readonly" style="width:80%"/>
            </td>
        </tr>
        <tr>
            <td width="20%" class="FormButton">Product question phone:</td>
            <td>&nbsp;</td>
            <td width="80%">
                <input type="text" size="50" name="d_product_questions_send_to_phone" value="{$item.phone}" readonly="readonly" style="width:80%"/>
            </td>
        </tr>
        <tr>
            <td width="20%" class="FormButton">Product question email:</td>
            <td>&nbsp;</td>
            <td width="80%">
                <input type="text" size="50" name="d_product_questions_send_to_email" value="{$item.email}" readonly="readonly" style="width:80%"/>
            </td>
        </tr>
    </table>
{/if}
{/foreach}
{/if}
    <div {if $distributor_section ne "16"}style="display: none;" {/if}>
        <br/>
        <a style="color: blue;" href="manufacturers.php?manufacturerid={$manufacturer.manufacturerid}&distributor_section=3">
            Select product question contact person here</a>
    </div>

{elseif $d_section.distributor_section eq "17"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_17" {if $distributor_section ne "17"}style="display: none;" {/if}>
    <tr>
        <td colspan="3">
            <B>Inventory feeds info:</B>
            <br/>
            {if $supplier_feeds_info_I}
                {foreach from=$supplier_feeds_info_I item=v_s key=k_s}
                    <B>feed_name:</B>
                    {$v_s->feed_name} ({if $v_s->enabled eq "Y"}Enabled{else}Disabled{/if})
                    <br/>
                    <B>storefront_id:</B>
                    {$v_s->storefront_id}
                    <br/>
                    <B>last_update_time:</B>
                    {$v_s->last_update_time|date_format:'%d-%b-%Y&nbsp; %H:%M'}
                    <br/>
                    <B>average_update_period:</B>
                    {$v_s->getAverageUpdatePeriod()}
                    <br/>
                    <B>last_update_items_count:</B>
                    {$v_s->last_update_items_count}
                    <br/>
                    {if $v_s->last_feed_fields}
                        <br>
                        <B>Feed fields last time processed:</B>
                        <br/>
                        <table>
                            <tr>
                                <td><B>Feed fields</B></td>
                                <td><B>Sample value</B></td>
                            </tr>
                            {foreach from=$v_s->last_feed_fields item=vs key=ks}
                                <tr>
                                    <td><B>{$ks}:</B></td>
                                    <td>{$vs}</td>
                                </tr>
                            {/foreach}
                        </table>
                    {/if}
                    <br/>
                    <br/>
                {/foreach}
            {else}
                <B>No inventory feed</B>
            {/if}
            <hr/>
            <br/>
            <B>Product feeds info:</B>
            <br/>
            {if $supplier_feeds_info_P}
                {foreach from=$supplier_feeds_info_P item=v_s key=k_s}
                    <B>feed_name:</B>
                    {$v_s->feed_name} ({if $v_s->enabled eq "Y"}Enabled{else}Disabled{/if})
                    <br/>
                    <B>storefront_id:</B>
                    {$v_s->storefront_id}
                    <br/>
                    <B>base_category_id:</B>
                    {$v_s->base_category_id}
                    <br/>
                    <B>last_update_time:</B>
                    {$v_s->last_update_time|date_format:'%d-%b-%Y&nbsp; %H:%M'}
                    <br/>
                    <B>average_update_period:</B>
                    {$v_s->getAverageUpdatePeriod()}
                    <br/>
                    <B>last_update_items_count:</B>
                    {$v_s->last_update_items_count}
                    <br/>
                    {if $v_s->last_feed_fields}
                        <br>
                        <B>Feed fields last time processed:</B>
                        <br/>
                        <table>
                            <tr>
                                <td><B>Feed fields</B></td>
                                <td><B>Sample value</B></td>
                            </tr>
                            {foreach from=$v_s->last_feed_fields item=vs key=ks}
                                <tr>
                                    <td><B>{$ks}:</B></td>
                                    <td>{$vs}</td>
                                </tr>
                            {/foreach}
                        </table>
                    {/if}
                    <br/>
                    <br/>
                {/foreach}
            {else}
                <B>No product feed</B>
            {/if}
        </td>
    </tr>
</table>
{elseif $d_section.distributor_section eq "19" && ($smarty.get.distributor_section eq "19" || $smarty.get.distributor_section eq "21")}

    {include file="provider/main/shipping_rates_new.tpl"}

{elseif $d_section.distributor_section eq "20"}
    <table cellpadding="3" cellspacing="1" id="distributor_section_id_20"
           {if $distributor_section ne "20"}style="display: none;" {/if}>
        <tr>
            <td colspan="2" class="FormButton">Quantity in stock behavior on the SF product page:</td>
            <td>
                <input type="radio" name="products_quantity_behavior"
                       value="R"{if $manufacturer.products_quantity_behavior eq "R"} checked="checked"{/if} /> display
                real quantity
                <br/>
                <input type="radio" name="products_quantity_behavior"
                       value="D"{if $manufacturer.products_quantity_behavior eq "D"} checked="checked"{/if} /> display
                quantity of
                <input type="text" size="5" name="display_quantity_of" value="{$manufacturer.display_quantity_of}"/> if
                product is in stock
            </td>
        </tr>
        <tr>
            <td colspan="3" class="FormButton">
                Allow pre-orders <input type="checkbox" name="allow_pre_orders"
                                        value="Y"{if $manufacturer.allow_pre_orders eq 'Y'} checked="checked"{/if} />
            </td>
        </tr>
        <tr>
            <td colspan="3" class="FormButton">
                Show shipping cost on the product page <input type="checkbox" name="calculate_shipping"
                                                              value="Y"{if $manufacturer.calculate_shipping eq 'Y'} checked="checked"{/if} />
            </td>
        </tr>

    </table>
{elseif $d_section.distributor_section eq "40"}
    <div {if $distributor_section ne "40"}style="display: none;" {/if}>
        {if $distributor_section eq "40"}
            <input type="hidden" name="mode" value="excluded_marketplace" />
        {/if}
    {include file="modules/External_Marketplaces/excluded_marketplaces_admin.tpl"}
    </div>
{elseif $d_section.distributor_section eq "31"}
    <table width="100%" cellpadding="3" cellspacing="1" id="distributor_section_id_31" {if $distributor_section ne "31"}style="display: none;" {/if}>
        <tr>
            <td style="width:50%" colspan="2" class="FormButton">Tick the checkbox if product verification is NOT required:</td>
            <td><input style="margin:0;" type="checkbox" {if ($manufacturer.products_always_verify=='Y')}checked="checked"{/if} value="Y" name="products_always_verify"></td>
        </tr>
        <tr>
            <td style="width:50%" colspan="2" class="FormButton">How long (in days) product verification remains valid?</td>
            <td><input type="text" value="{$manufacturer.days_before_verify}" name="products_days_before_verify"></td>
        </tr>
    </table>
{elseif $d_section.distributor_section eq "30"}
    <table class="SubHeader" width="100%" cellspacing="0" {if $distributor_section ne "30"}style="display: none;" {/if}>
        <tbody>
        <tr>
            <td class="Green2">
                Relationships
            </td>
        </tr>
        <tr>
            <td class="SubHeaderLine">
                <img class="Spc" alt="" src="/skin1_kolin/images/spacer.gif">
                <br>
            </td>
        </tr>
        </tbody>
    </table>
    <p></p>
    <table width="100%" cellpadding="3" cellspacing="1" id="distributor_section_id_30" {if $distributor_section ne "30"}style="display: none;" {/if}>

        <tr>
            <td style="vertical-align: top; padding-top: 5px;" colspan="2" class="FormButton">This distributor is parent to:</td>
            <td >
                {if ($aParentManufacturer)}
                <table width="100%" style="text-align:center;">
                    <tr>
                        <th>Prefix</th>
                        <th>Distributor name</th>
                        <th>Main SF id</th>
                        <th>Main SF name</th>
                        <th>Destination category id</th>
                    </tr>
                    {foreach from=$aParentManufacturer item=parentmf}
                    <tr>
                        <td>{$parentmf.code}</td>
                        <td><b><a href="manufacturers.php?manufacturerid={$parentmf.manufacturerid}">{$parentmf.manufacturer}</a></b></td>
                        <td>{$parentmf.d_main_sf}</td>
                        <td>{$parentmf.domain}</td>
                        <td>{$parentmf.root_categoryid_for_cloned_products}</td>
                    </tr>
                    {/foreach}
                </table>
                {/if}
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top; padding-top: 5px;" colspan="2" class="FormButton">
                This distributor is child to:
            </td>
            <td>
                {if ($aChildManufacturers)}
                    <table width="100%" style="text-align:center;">
                        <tr>
                            <th>Prefix</th>
                            <th>Distributor name</th>
                            <th>Main SF id</th>
                            <th>Main SF name</th>
                        </tr>
                        {foreach from=$aChildManufacturers item=childmf}
                            <tr>
                                <td>{$childmf.code}</td>
                                <td><b><a href="manufacturers.php?manufacturerid={$childmf.manufacturerid}">{$childmf.manufacturer}</a></b></td>
                                <td>{$childmf.d_main_sf}</td>
                                <td>{$childmf.domain}</td>
                            </tr>
                        {/foreach}
                    </table>
                {/if}
            </td>
        </tr>

    </table>
    <p></p>
    <p></p>
    {if $manufacturer.parent_manufacturer_id eq -1}
    <table class="SubHeader" width="100%" cellspacing="0" {if $distributor_section ne "30"}style="display: none;" {/if}>
        <tbody>
        <tr>
            <td class="Green2">
                Create copy of this distributor on another storefront
            </td>
        </tr>
        <tr>
            <td class="SubHeaderLine">
                <img class="Spc" alt="" src="/skin1_kolin/images/spacer.gif">
                <br>
            </td>
        </tr>
        </tbody>
    </table>
    <p></p>
    <table cellpadding="3" cellspacing="1" id="distributor_section_id_30" {if $distributor_section ne "30"}style="display: none;" {/if}>

        <tr>
            <td colspan="2" class="FormButton">Choose target storefront:</td>
            <td>
                <select name="storefront_to_copy_manufacturer">
                    <option value="0"{if $manufacturer.d_main_sf eq '0'} selected="selected"{/if}>{$main_storefront}</option>
                    {foreach from=$storefronts item=sf}
                        {if $sf.storefrontid ne "0" && $manufacturer.d_main_sf ne $sf.storefrontid}}
                            <option value="{$sf.storefrontid}"{if $manufacturer.d_main_sf eq $sf.storefrontid} selected="selected" {assign var="main_sf_site" value=$sf.domain}{/if}>{if $sf.storefront_name ne ""}{$sf.storefront_name}{else}{$sf.domain}{/if}</option>
                        {/if}
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="FormButton">Enter root categoryid for new products on target SF:</td>
            <td>
                <input name="root_categoryid_for_cloned_products" type="text" value="{$manufacturer.root_categoryid_for_cloned_products}" />
            </td>
        </tr>

    </table>

    <table align="center" cellpadding="3" cellspacing="1" width="100%"  {if $distributor_section ne "30"}style="display: none;" {/if}>
        <tr>
            <td width="48%">
            </td>
                <td width="*">
                    <input type="submit" onclick="javascript: {literal}$('#mode').val('copy_distributor');{/literal}" value="Copy now" />
                </td>
        </tr>
    </table>
{if ($aParentManufacturer)}
    <table class="SubHeader" width="100%" cellspacing="0" {if $distributor_section ne "30"}style="display: none;" {/if}>
        <tbody>
        <tr>
            <td class="Green2">
                Clone distributor products to child distributor
            </td>
        </tr>
        <tr>
            <td class="SubHeaderLine">
                <img class="Spc" alt="" src="/skin1_kolin/images/spacer.gif">
                <br>
            </td>
        </tr>
        </tbody>
    </table>
    <p></p>
    <table cellpadding="3" cellspacing="1" id="distributor_section_id_30" {if $distributor_section ne "30"}style="display: none;" {/if}>
        <tr><td colspan="2">
                {$lng.lb_clone_products_note_before_cloning}
            </td></tr>
        <tr>
            <td colspan="2" class="FormButton">Choose distributor:</td>
            <td>
                <select name="product_to_copy_manufacturer">
                    <option value="0">Please select</option>
                    {foreach from=$aParentManufacturer item=pm}
                            <option value="{$pm.manufacturerid}">{$pm.manufacturer}</option>
                    {/foreach}
                </select>
            </td>
        </tr>

    </table>
    <table align="center" cellpadding="3" cellspacing="1" width="100%"  {if $distributor_section ne "30"}style="display: none;" {/if}>
        <tr>
            <td width="48%">
            </td>
            <td width="*">
                <input type="submit" onclick="javascript: {literal}$('#mode').val('copy_products');{/literal}" value="Copy now" />
            </td>
        </tr>
    </table>
{/if}
    {else}
    <table cellpadding="3" cellspacing="1" id="distributor_section_id_30" {if $distributor_section ne "30"}style="display: none;" {/if}>
        <tr>
            <td colspan="2" class="FormButton">Root category for new cloned products:</td>
            <td>
                <input name="root_categoryid_for_cloned_products" type="text" value="{$manufacturer.root_categoryid_for_cloned_products}" />
            </td>
        </tr>
    </table>
    <table align="center" cellpadding="3" cellspacing="1" width="100%"  {if $distributor_section ne "30"}style="display: none;" {/if}>
        <tr>
            <td width="48%">
            </td>
            <td width="*">
                <input type="submit" onclick="javascript: {literal}$('#mode').val('update_root_category');{/literal}" value="Update" />
            </td>
        </tr>
    </table>
    {/if}

{elseif $d_section.distributor_section eq "22"}
	<table cellpadding="3" cellspacing="1" id="distributor_section_id_22" {if $distributor_section ne "22"}style="display: none;" {/if}>
	<tr>
	<td>

	{include file="admin/main/product_page_locked_fields.tpl"}

	</td>
	</tr>
	</table>
{/if}

{/foreach}


<table align="center" cellpadding="3" cellspacing="1" width="100%" >
<tr>
	<td width="48%">
	{if $smarty.get.distributor_section eq "3"}
		<input type="button" value=" Add new line " onclick="javascript: {literal}$('#mode').val('add_new_line'); document.manufacturer.submit();"{/literal} />
	{elseif $smarty.get.distributor_section eq "10"}
		<input type="button" value=" Add distributor return address  " onclick="javascript: {literal}$('#mode').val('add_distributor_return_address'); document.manufacturer.submit();"{/literal} />
	{/if}
	</td>
	{if $smarty.get.distributor_section ne "19" && $smarty.get.distributor_section ne "21" && $smarty.get.distributor_section ne "16" && $smarty.get.distributor_section ne "22" && $smarty.get.distributor_section ne "30"}
	<td width="*">

		{if ($smarty.get.distributor_section eq "8" && !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_CUSTOMER_SERVICE_AND_PRODUCT_MANAGER" || $membership_code eq "ADMIN_PRODUCT_MANAGER" || $membership_code eq "ADMIN_TRACKING_NUMBER_ENTRY_OPERATOR"))
		  ||
		     $smarty.get.distributor_section ne "8"
		}
		<input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} "{$disabled} />
		{/if}
	</td>
	{/if}
</tr>
</table>

{if $smarty.get.distributor_section ne "19" && $smarty.get.distributor_section ne "21" && $smarty.get.distributor_section ne "22"}
</form>
{/if}

{/capture}

{foreach from=$distributor_sections item=d_section key=k_section}
{if $d_section.distributor_section eq $distributor_section}
{include file="dialog.tpl" title=$d_section.title content=$smarty.capture.dialog extra='width="100%"'}
{/if}
{/foreach}
{/if}

<script type="text/javascript">
    $( document ).ready(function() {ldelim}
        var curTitle = document.title;
        document.title = "{$manufacturer.manufacturer}: (Distributor) " + curTitle;
    {rdelim});
    {literal}
    $(function () {
        var t= $('.tooltip').tooltip({
            position: {
                using: function (position, feedback) {
                    $(this).css(position);
                    $("<div>")
                        .addClass("tooltip__s3")
                        .appendTo(this);
                }
            },
            content: function(){
                return $(this).attr('title');
            },
            open: function (event, ui) {
                ui.tooltip.css("max-width", "400px");
            }
        });
    });
    {/literal}
</script>
