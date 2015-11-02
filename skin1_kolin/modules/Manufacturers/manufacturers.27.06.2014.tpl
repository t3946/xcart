{* $Id: manufacturers.tpl,v 1.32.2.3 2006/07/19 06:38:47 max Exp $ *}

<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
{literal}

tinymce.init({
    selector: "textarea.new_editor",
    resize: "both",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    forced_root_block : false,
    force_br_newlines : true,
    force_p_newlines : false
});

{/literal}
//]]>
</script>

{include file="page_title.tpl" title=$lng.lbl_manufacturers link='manufacturers.php?word=num'}

{*
{if $active_modules.HTML_Editor}
{include file="modules/HTML_Editor/editor.tpl"}
{/if}
*}

{if $usertype eq "A" or ($active_modules.Simple_Mode ne "" and $usertype eq "P")}
{assign var="administrate" value="Y"}
{/if}

{$lng.txt_manufacturers_top_text}

{if $active_modules.Simple_Mode eq ""}
<br /><br />

{$lng.txt_manufacturers_note_pro}
{/if}

{if $single_mode eq ""}
<br /><br />

{$lng.txt_manufacturers_notes}

{if $active_modules.Simple_Mode eq "" and $usertype eq "P"}
{$lng.txt_manufacturers_note_pro_provider}
{/if}

{/if}

<br /><br />

{if $mode ne "manufacturer_info"}

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

{include file="customer/main/navigation.tpl"}

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
	<td width="35%">{$lng.lbl_manufacturer}</td>
	<td width="10%">{$lng.lbl_distr_code}</td>
	<td width="25%">{$lng.lbl_provider}</td>
	<td width="20%" align="center">{$lng.lbl_products}</td>
	<td width="30" align="center">{$lng.lbl_orderby}</td>
	<td width="30" align="center">{$lng.lbl_active}</td>
</tr>

{if $manufacturers ne ""}

{foreach from=$manufacturers item=v}

<tr{cycle values=", class='TableSubHead'"}>
	<td align="center"><input type="checkbox" name="to_delete[{$v.manufacturerid}]"{if $administrate eq "" and ($v.provider ne $login or $v.used_by_others gt 0)} disabled="disabled"{/if} /></td>
	<td><b><a href="manufacturers.php?manufacturerid={$v.manufacturerid}{if $page}&amp;page={$page}{/if}">{$v.manufacturer}</a></b></td>
	<td align="center">{$v.code}</td>
	<td>{if $v.is_provider eq 'Y'}{$v.provider_name}{else}{$lng.lbl_manuf_owner_lost}{/if}{if $administrate} ({$v.provider}){/if}</td>
	<td align="center">{$v.products_count|default:$lng.txt_not_available}{if $v.used_by_others gt 0}*{assign var="show_note" value="Y"}{/if}</td>
	<td align="center"><input type="text" name="records[{$v.manufacturerid}][orderby]" size="5" value="{$v.orderby}"{if $administrate eq ""} disabled="disabled"{/if} /></td>
	<td align="center"><input type="checkbox" name="records[{$v.manufacturerid}][avail]" value="Y"{if $v.avail eq "Y"} checked="checked"{/if}{if $administrate eq ""} disabled="disabled"{/if} /></td>
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
<td colspan="6"><br /><input type="button" value="{$lng.lbl_add_new_|strip_tags:false|escape}" onclick="javascript: self.location = 'manufacturers.php?mode=add';" /></td>
</tr>

</table>

</form>

{include file="customer/main/navigation.tpl"}

{/capture}
{include file="dialog.tpl" title=$lng.lbl_manufacturers_list content=$smarty.capture.dialog extra='width="100%"'}

{else}

{include file="main/include_js.tpl" src="main/popup_image_selection.js"}

{* --- *}

{include file="page_title.tpl" title=$location.2.0}

<table cellspacing="0" cellpadding="0" width="100%" class="NavDialogBox" style="BORDER: #FFCC33 1px solid;">
 <tr>
	<td class="NavDialogBorder" height="15"><B>Distributor sections:</B></td>
	<td class="NavDialogBorder" height="15" align="right">
<a href="
{if $manufacturer.d_main_sf eq '0'}http://www.artistsupplysource.com{/if}
{foreach from=$storefronts item=sf}
{if $sf.storefrontid ne "0"}
{if $manufacturer.d_main_sf eq $sf.storefrontid}http://{$sf.domain}{/if}
{/if}
{/foreach}
" target="_blank" style="color: #0101F7">
{if $manufacturer.d_main_sf eq '0'}www.artistsupplysource.com{/if}
{foreach from=$storefronts item=sf}
{if $sf.storefrontid ne "0"}
{if $manufacturer.d_main_sf eq $sf.storefrontid}{$sf.domain}{/if}
{/if}
{/foreach}
</a>
	</td>
 </tr>
 <tr>
  <td width="50%" valign="top">

   {if $distributor_sections ne ""}
        <table cellspacing="1" cellpadding="1">

    {assign var=cell_counter value=0}
     {foreach from=$distributor_sections item=item key=key}
	{assign var=cell_counter value=$cell_counter+1}
        <tr>
         <td class="NavDialogCell"><a href="manufacturers.php?manufacturerid={$manufacturer.manufacturerid}&distributor_section={$item.distributor_section}&page={$page}" class="VertMenuItems"><img alt="" src="{$SkinDir}/images/rarrow.gif"></a></td>
         <td class="NavDialogCell">
	 {if $smarty.get.distributor_section eq $item.distributor_section}
	 <B>
	 {else}
	 <a href="manufacturers.php?manufacturerid={$manufacturer.manufacturerid}&distributor_section={$item.distributor_section}&page={$page}">
	 {/if}
	{$item.title}
         {if $smarty.get.distributor_section eq $item.distributor_section}
         </B>
         {else}
	 </a>
	 {/if}
	 </td>
	</tr>
	
	{if $cell_counter eq $count_rows_in_cell}
	</table>
	</td>
	<td width="*" valign="top">
	<table cellspacing="1" cellpadding="1">
	{/if}
     {/foreach}
   {/if}
        </table>
  </td>
 </tr>
</table>
<br />
{* --- *}

<div align="right">
<table cellspacing="0" cellpadding="0">
<tr>
        <td>{include file="buttons/button.tpl" button_title=$lng.lbl_manufacturers_list href="manufacturers.php?page=`$page`&word=num"}</td>
{if $manufacturer.manufacturerid}
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>{include file="buttons/button.tpl" button_title=$lng.lbl_add_manufacturer href="manufacturers.php?mode=add&page=`$page`"}</td>
{/if}
</tr>
</table>
</div>


{capture name=dialog}

{if $administrate eq "" and $manufacturer.used_by_others gt 0}
<br />
<font class="ErrorMessage">{$lng.txt_manufacturers_warning}</font>
<br />
{/if}

{if $administrate eq "" and $login ne $manufacturer.provider and $smarty.get.mode ne "add"}
{assign var="disabled" value=" disabled"}
{/if}

{if $manufacturer.manufacturerid ne ''}
{include file="main/language_selector.tpl" script="manufacturers.php?manufacturerid=`$manufacturer.manufacturerid`&"}
{/if}
<form action="manufacturers.php" method="post" enctype="multipart/form-data" name="manufacturer">
<input type="hidden" name="mode" value="details" id="mode" />
<input type="hidden" name="manufacturerid" value="{$manufacturer.manufacturerid}" />
<input type="hidden" name="page" value="{$page}" />
<input type="hidden" name="distributor_section" value="{$distributor_section}" />


{foreach from=$distributor_sections item=d_section key=k_section}

{if $d_section.distributor_section eq "1"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_1" {if $distributor_section ne "1"}style="display: none;" {/if}>
<tr>
	<td width="20%" class="FormButton">Distributor company name</td>
	<td><font class="Star">*</font></td>
	<td width="80%"><input type="text" name="manufacturer" size="50" value="{$manufacturer.manufacturer}" style="width:80%"{$disabled} /></td>
</tr>

<tr>
	<td width="20%" class="FormButton">Distributor prefix</td>
	<td><font class="Star">*</font></td>
	<td width="80%"><input type="text" name="code" size="10" maxlength="5" value="{$manufacturer.code}" style="width:25%"{$disabled} /></td>
</tr>

<tr>
        <td class="FormButton">Distributor website URL (main page)</td>
        <td>&nbsp;</td>
        <td><input type="text" size="47" name="url" value="{$manufacturer.url}" style="width:50%" {$disabled} />
{if $manufacturer.url ne ""}<a href="{$manufacturer.url}" target="blank">Website</a>{/if}
        </td>
</tr>

<tr>
	<td class="FormButton">Logo</td>
	<td>&nbsp;</td>
	{if $manufacturer.is_image eq 'Y'}{assign var="no_delete" value=""}{else}{assign var="no_delete" value="Y"}{/if}
	<td>{include file="main/edit_image.tpl" type="M" id=$manufacturer.manufacturerid delete_url="manufacturers.php?mode=delete_image&manufacturerid=`$manufacturer.manufacturerid`" button_name=$lng.lbl_save no_delete=$no_delete}</td>
</tr>

<tr>
        <td class="FormButton">Main SF</td>
        <td>&nbsp;</td>
        <td>
                <select name="d_main_sf">
                        <option value="0"{if $manufacturer.d_main_sf eq '0'} selected="selected"{/if}>{$cidev_main_storefront_name}</option>
                        {foreach from=$storefronts item=sf}
                                {if $sf.storefrontid ne "0"}
                                <option value="{$sf.storefrontid}"{if $manufacturer.d_main_sf eq $sf.storefrontid} selected="selected"{/if}>{if $sf.storefront_name ne ""}{$sf.storefront_name}{else}{$sf.domain}{/if}</option>
                                {/if}
                        {/foreach}
                </select>
        </td>
</tr>


{*
<tr>
        <td class="FormButton">{$lng.lbl_description}</td>
        <td>&nbsp;</td>
        <td>
{include file="main/textarea.tpl" name="descr" cols=55 rows=10 class="InputWidth" data=$manufacturer.descr width="80%" btn_rows=3}
        </td>
</tr>
*}

<tr>
        <td class="FormButton">Distributor specific instructions</td>
        <td>&nbsp;</td>
        <td>
{*
{include file="main/textarea.tpl" name="d_specific_instructions" cols=55 rows=10 class="InputWidth" data=$manufacturer.d_specific_instructions width="80%" btn_rows=3}
*}
<textarea class="new_editor" name="d_specific_instructions" rows="20" cols="60" style="width: 80%;">{$manufacturer.d_specific_instructions}</textarea>
        </td>
</tr>


{if $administrate eq "Y"}
<tr>
        <td width="20%" class="FormButton">{$lng.lbl_orderby}</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" name="orderby" size="5" value="{$manufacturer.orderby|default:"10"}" /></td>
</tr>

<tr>
        <td class="FormButton">{$lng.lbl_availability}</td>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="avail" value="Y"{if $manufacturer.avail eq 'Y' || $manufacturer.manufacturerid eq ''} checked="checked"{/if} /></td>
</tr>
{/if}

{if $operators ne ""}
<tr>
    <td colspan="3">{$lng.txt_operate_for_distributers}</td>
</tr>

<tr>
    <td colspan="3">
        <table cellpadding="3" cellspacing="1" width="100%">
            {foreach from=$operators item=op}
            <tr>
                <td width="30"><input type="checkbox" name="operators[]" checked="checked" value="{$op.login}"/></td>
                <td>{if $usertype eq "A"}<a href="user_modify.php?user={$op.login}&usertype=P&page=1" target="_blank">{/if}{$op.b_firstname} {$op.b_lastname} ({$op.login}){if $usertype eq "A"}</a>{/if}</td>
            </tr>
            {/foreach}
        </table>
    </td>
</tr>
{/if}

{* --------------------- *}
{if $smarty.get.manufacturerid eq "32"}
<tr>
        <td class="FormButton">Reverse SKU</td>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="reverse_sku" value="Y"{if $manufacturer.reverse_sku eq 'Y'} checked="checked"{/if} /></td>
</tr>
<tr>
        <td class="FormButton">Remove dashes</td>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="remove_dashes" value="Y"{if $manufacturer.remove_dashes eq 'Y'} checked="checked"{/if} /></td>
</tr>
{/if}
{* --------------------- *}

</table>

{elseif $d_section.distributor_section eq "2"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_2" {if $distributor_section ne "2"}style="display: none;" {/if}>

<tr>
<td class="FormButton">Product Page Text</td>
<td>&nbsp;</td>
<td><textarea name="cart_manufact_text_displayed" rows="5" cols="60" style="width:80%">{$manufacturer.cart_manufact_text_displayed}</textarea></td>
</tr>

<tr>
        <td class="FormButton" nowrap="nowrap">"Add to cart" pop-up message</td>
        <td>&nbsp;</td>
        <td><input type="text" size="50" name="lead_time_message" value="{$manufacturer.lead_time_message}" style="width:80%" /></td>
</tr>

<tr>
<td class="FormButton" width="20%">Cart Page Text</td>
<td>&nbsp;</td>
<td width="80%"><textarea name="manufact_text_displayed" rows="5" cols="60" style="width:80%">{$manufacturer.manufact_text_displayed}</textarea></td>
</tr>

<tr>
        <td width="25%" class="FormButton">{$lng.lbl_catalog_sku}</td>
        <td>&nbsp;</td>
        <td width="75%"><input type="text" size="50" name="catalog_sku" value="{$manufacturer.catalog_sku}" style="width:80%" /></td>
</tr>

<tr>
        <td class="FormButton">{$lng.lbl_catalog_price}</td>
        <td>&nbsp;</td>
        <td><input type="text" size="18" name="catalog_price" value="{$manufacturer.catalog_price}" /></td>
</tr>

<tr>
        <td class="FormButton">{$lng.lbl_catalog_text}</td>
        <td>&nbsp;</td>
        <td><input type="text" size="50" name="catalog_text" value="{$manufacturer.catalog_text}" style="width:80%" /></td>
</tr>

</table>

{elseif $d_section.distributor_section eq "3"}
<table cellpadding="3" cellspacing="1" {* width="100%" *} id="distributor_section_id_3" {if $distributor_section ne "3"}style="display: none;" {/if}>
{if $manufacturer.distributor_contacts ne ""}
<tr>
<td style="font-weight: bold;">Position</td>
<td style="font-weight: bold;">Contact name</td>
<td style="font-weight: bold;">Email</td>
<td style="font-weight: bold;">Phone</td>
<td style="font-weight: bold;">Ext</td>
<td style="font-weight: bold;">Fax</td>
<td style="font-weight: bold;">Delete</td>
</tr>

<input type="hidden" id="delete_line_number" name="delete_line_number" value="" />

{foreach from=$manufacturer.distributor_contacts item=item key=key}
<tr>
{* <td>{$item.distributor_field_name}</td> *}
<td><input type="text" name="distributor_contacts[{$key}][distributor_field_name]" value="{$item.distributor_field_name}" size="30" /></td>
<td><input type="text" name="distributor_contacts[{$key}][contact_name]" value="{$item.contact_name|escape:"html"}" size="30" /></td>
<td><input type="text" name="distributor_contacts[{$key}][email]" value="{$item.email}" size="30" /></td>
<td><input type="text" name="distributor_contacts[{$key}][phone]" value="{$item.phone}" size="17" /></td>
<td><input type="text" name="distributor_contacts[{$key}][ext]" value="{$item.ext}" size="7" /></td>
<td><input type="text" name="distributor_contacts[{$key}][fax]" value="{$item.fax}" size="17" /></td>
<td>
 <input type="button" value="Delete" onclick="javascript: {literal}$('#mode').val('delete_line'); $('#delete_line_number').val('{/literal}{$key}{literal}'); document.manufacturer.submit();"{/literal} />
</td>
</tr>
{/foreach}
{/if}
</table>

{elseif $d_section.distributor_section eq "6"}
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

<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_6" {if $distributor_section ne "6"}style="display: none;" {/if}>
<tr>
<td width="20%">{$lng.lbl_address}</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
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
</table>


{elseif $d_section.distributor_section eq "8"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_8" {if $distributor_section ne "8"}style="display: none;" {/if}>

<tr>
<td width="20%">Our dealer account #</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_our_dealer_account_n" value="{$manufacturer.d_our_dealer_account_n}" size="50" maxlength="128" style="width: 80%;" /></td>
</tr>

<tr>
<td width="20%">Contact name for templates</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_contact_name_for_templates" value="{$manufacturer.d_contact_name_for_templates}" size="50" maxlength="128" style="width: 40%;" /></td>
</tr>

<tr>
<td width="20%">'Send to' email for templates</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_send_to_email_for_templates" value="{$manufacturer.d_send_to_email_for_templates}" size="50" maxlength="128" style="width: 40%;" /></td>
</tr>


<tr><td colspan="3" align="center"><B>Login to distributor website</B></td></tr>

<tr>
<td width="20%">URL to login to distributor website</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_url_to_login_to_distributor_website" value="{$manufacturer.d_url_to_login_to_distributor_website}" size="50" maxlength="128" style="width: 40%;" /> {if $manufacturer.d_url_to_login_to_distributor_website ne ""}<a href="{$manufacturer.d_url_to_login_to_distributor_website}" target="_blank">Login URL</a>{/if}</td>
</tr>

<tr>
<td width="20%">Login/username</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_login" value="{$manufacturer.d_login}" size="50" maxlength="128" style="width: 40%;" /></td>
</tr>

<tr>
<td width="20%">Password</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_password" value="{$manufacturer.d_password}" size="50" maxlength="128" style="width: 40%;" /></td>
</tr>

<tr>
        <td width="20%">Preferred way to submit orders is</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="submit_to_operator" id="submit_to_operator" onchange="javasript:{literal} if ($('#submit_to_operator').val()=='through_distributor_website'){ $('#tr_email_to_order_entry_operator').show(); $('#order_submission_by_email_or_and_fax1').hide(); $('#order_submission_by_email_or_and_fax2').hide(); $('#order_submission_by_email_or_and_fax3').hide(); $('#order_submission_by_email_or_and_fax4').hide(); $('#order_submission_by_email_or_and_fax5').hide(); $('#order_submission_by_email_or_and_fax7').show(); $('#order_submission_by_email_or_and_fax8').show(); $('#order_submission_by_email_or_and_fax6').hide(); $('#tr_d_order_entry_operator_email').show(); $('#tr_d_instructions_to_order_entry_operator').show();}else{$('#tr_d_order_entry_operator_email').hide(); $('#tr_d_instructions_to_order_entry_operator').hide(); $('#order_submission_by_email_or_and_fax1').show(); $('#order_submission_by_email_or_and_fax2').show(); $('#order_submission_by_email_or_and_fax3').show(); $('#order_submission_by_email_or_and_fax4').show(); $('#order_submission_by_email_or_and_fax5').show(); $('#order_submission_by_email_or_and_fax7').hide(); $('#order_submission_by_email_or_and_fax8').hide(); $('#order_submission_by_email_or_and_fax6').show(); $('#tr_email_to_order_entry_operator').hide();}{/literal}">
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
<td width="20%">Order entry operator email</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_order_entry_operator_email" value="{$manufacturer.d_order_entry_operator_email}" size="50" maxlength="128" style="width: 80%;" /></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'by_email_or_and_fax'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax8">
<td width="20%">Order entry operator subject line</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_order_entry_operator_subject_line_8" value="{$manufacturer.d_order_entry_operator_subject_line_8}" size="50"style="width: 80%;" /></td>
</tr>

<tr id="tr_d_instructions_to_order_entry_operator" {if $manufacturer.submit_to_operator ne 'through_distributor_website'}style="display: none;"{/if}>
<td width="20%">Instructions to order entry operator</td>
<td>&nbsp;</td>
<td width="80%"><textarea class="new_editor" name="d_instructions_to_order_entry_operator" rows="15" cols="60" style="width: 80%;">{$manufacturer.d_instructions_to_order_entry_operator}</textarea></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax1"><td colspan="3" align="center"><B>Order submission by e-mail or/and fax</B></td></tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax3">
        <td colspan="3">{$lng.txt_distributor_section_8}</td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax2">
<td width="20%">Distributor email</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="email" value="{$manufacturer.email}" size="50" style="width: 80%;" /></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax6">
<td width="20%">Distributor subject line</td>
<td>&nbsp;</td>
<td width="80%"><input type="text"  name="d_subject_line_8" value="{$manufacturer.d_subject_line_8}" size="50" style="width: 80%;" /></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax4">
<td>{* {$lng.lbl_message_body}*} Message to distributor</td>
<td>&nbsp;</td>
<td><textarea class="new_editor" name="mess_body" rows="20" cols="60" style="width: 80%;">{$manufacturer.mess_body}</textarea></td>
</tr>

<tr {if $manufacturer.submit_to_operator eq 'through_distributor_website'}style="display: none;"{/if} id="order_submission_by_email_or_and_fax5">
<td>Shipping options</td>
<td>&nbsp;</td>
<td><input type="text"  name="d_shipping_options" value="{$manufacturer.d_shipping_options}" size="50" style="width: 80%;" /></td>
</tr>


</table>


{elseif $d_section.distributor_section eq "5"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_5" {if $distributor_section ne "5"}style="display: none;" {/if}>
<tr>
        <td width="20%" class="FormButton">{$lng.lbl_cost_to_us}&nbsp;=</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="9" name="cost_to_us_coef_x" value="{$manufacturer.cost_to_us_coef_x}" />&nbsp;*&nbsp;{$lng.lbl_list_price}</td>
</tr>

<tr>
        <td class="FormButton">{$lng.lbl_price}&nbsp;=</td>
        <td>&nbsp;</td>
        <td>(&nbsp;<input type="text" size="9" name="price_coef_x" value="{$manufacturer.price_coef_x}" />&nbsp;*&nbsp;{$lng.lbl_cost_to_us}&nbsp;+&nbsp;<input type="text" size="9" name="price_coef_y" value="{$manufacturer.price_coef_y}" />&nbsp;)&nbsp;/&nbsp;<input type="text" size="9" name="price_coef_z" value="{$manufacturer.price_coef_z}" /></td>
</tr>

<tr>
        <td class="FormButton">MAP price&nbsp;=</td>
        <td>&nbsp;</td>
        <td><input type="text" size="9" name="new_map_price_coef_x" value="{$manufacturer.new_map_price_coef_x}" />&nbsp;*&nbsp;{$lng.lbl_list_price}</td>
</tr>

<tr>
        <td class="FormButton">Bridge price&nbsp;=</td>
        <td>&nbsp;</td>
        <td><input type="text" size="9" name="map_price_coef_x" value="{$manufacturer.map_price_coef_x}" />&nbsp;*&nbsp;{$lng.lbl_list_price}</td>
</tr>

<tr>
        <td nowrap="nowrap" class="FormButton">Distributor product price multiplier</td>
        <td>&nbsp;</td>
        <td><input type="text" size="9" name="supplier_products_price_multiplier" value="{$manufacturer.supplier_products_price_multiplier}" /></td>
</tr>

</table>


{elseif $d_section.distributor_section eq "7"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_7" {if $distributor_section ne "7"}style="display: none;" {/if}>

<tr>
        <td width="20%" class="FormButton">Distributor ships to/within</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_ships_to_within" value="{$manufacturer.d_ships_to_within}" style="width:80%" /></td>
</tr>

<tr>
	<td class="FormButton">Shipping methods used by distributor</td>
	<td>&nbsp;</td>
	<td width="80%">

 <table width="100%" cellpadding="0" cellspacing="0" border="0">
 <tr>
 <td colspan="5"><input type="checkbox" name="d_shipping_methods_usps" value="Y"{if $manufacturer.d_shipping_methods_usps eq 'Y'} checked="checked"{/if} />USPS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="d_shipping_methods_ups" value="Y"{if $manufacturer.d_shipping_methods_ups eq 'Y'} checked="checked"{/if} />UPS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="d_shipping_methods_fedex" value="Y"{if $manufacturer.d_shipping_methods_fedex eq 'Y'} checked="checked"{/if} />FedEx &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="d_shipping_methods_trucking_company" value="Y"{if $manufacturer.d_shipping_methods_trucking_company eq 'Y'} checked="checked"{/if} />Trucking company</td>
 </tr>
 <tr>
 <td width="17%">other methods used</td>
 <td colspan="3"><input type="text" size="50" name="d_shipping_methods_other" value="{$manufacturer.d_shipping_methods_other}" style="width:76%" /></td>
 </tr>
 </table>

	</td>
</tr>

<tr>
        <td width="20%" class="FormButton">Drop-ship fee</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="d_drop_ship_fee_select" id="d_drop_ship_fee_select"
onchange="javasript:{literal} if (this.value !=''){$('#tr_d_drop_ship_fee_in_us').show();}else{$('#tr_d_drop_ship_fee_in_us').hide();}{/literal}"
>
<option value="">N/A</option>
<option value="applies_to_all_orders"{if $manufacturer.d_drop_ship_fee_select eq "applies_to_all_orders"} selected="selected"{/if}>applies to all orders</option>
<option value="applies_to_orders_below_minimum_order_amount_only"{if $manufacturer.d_drop_ship_fee_select eq "applies_to_orders_below_minimum_order_amount_only"} selected="selected"{/if}>applies to orders below minimum order amount only</option>
</select>
        </td>
</tr>

<tr id="tr_d_drop_ship_fee_in_us" {if $manufacturer.d_drop_ship_fee_select eq ""}style="display: none;"{/if}>
        <td width="20%" class="FormButton">Drop-ship fee in US$</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" name="d_drop_ship_fee_in_us" value="{$manufacturer.d_drop_ship_fee_in_us}" size="7" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Minimum order amount in US$</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="d_minimum_order_amount" id="d_minimum_order_amount"
onchange="javasript:{literal} if (this.value !=''){$('#tr_d_minimum_order_amount_in_us').show(); $('#tr_d_for_orders_below_min_order_amount').show();}else{$('#tr_d_minimum_order_amount_in_us').hide(); $('#tr_d_for_orders_below_min_order_amount').hide(); $('#tr_d_dealer_discount_reduced_from').hide(); $('#d_for_orders_below_min_order_amount').val('are_rejected')}{/literal}"
>
<option value="">N/A</option>
<option value="applies_to_all_orders"{if $manufacturer.d_minimum_order_amount eq "applies_to_all_orders"} selected="selected"{/if}>applies to all orders</option>
</select>
        </td>
</tr>

<tr id="tr_d_minimum_order_amount_in_us" {if $manufacturer.d_minimum_order_amount eq ""}style="display: none;"{/if}>
        <td width="20%" class="FormButton">Minimum order amount in US$</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" name="d_minimum_order_amount_in_us" value="{$manufacturer.d_minimum_order_amount_in_us}" size="7" /></td>
</tr>

<tr id="tr_d_for_orders_below_min_order_amount" {if $manufacturer.d_minimum_order_amount eq ""}style="display: none;"{/if}>
        <td width="20%" class="FormButton">(For) orders below minimum order amount</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="d_for_orders_below_min_order_amount" id="d_for_orders_below_min_order_amount"
onchange="javasript:{literal} if (this.value == 'dealer_discount_is_reduced'){$('#tr_d_dealer_discount_reduced_from').show();}else{$('#tr_d_dealer_discount_reduced_from').hide();}{/literal}"
>
<option value="are_rejected"{if $manufacturer.d_for_orders_below_min_order_amount eq "are_rejected"} selected="selected"{/if}>are rejected</option>
<option value="drop_ship_fee_is_applied"{if $manufacturer.d_for_orders_below_min_order_amount eq "drop_ship_fee_is_applied"} selected="selected"{/if}>drop-ship fee is applied</option>
<option value="dealer_discount_is_reduced"{if $manufacturer.d_for_orders_below_min_order_amount eq "dealer_discount_is_reduced"} selected="selected"{/if}>dealer discount is reduced</option>
</select>
        </td>
</tr>

<tr id="tr_d_dealer_discount_reduced_from" {if $manufacturer.d_for_orders_below_min_order_amount eq "dealer_discount_is_reduced" && $manufacturer.d_minimum_order_amount ne ""}{else}style="display: none;";{/if}>
        <td width="20%" class="FormButton"></td>
        <td>&nbsp;</td>
        <td width="80%">
	from <input type="text" name="d_dealer_discount_reduced_from" value="{$manufacturer.d_dealer_discount_reduced_from}" size="7" />%
	to <input type="text" name="d_dealer_discount_reduced_to" value="{$manufacturer.d_dealer_discount_reduced_to}" size="7" />%
	</td>
</tr>
</table>


{elseif $d_section.distributor_section eq "4"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_4" {if $distributor_section ne "4"}style="display: none;" {/if}>

<tr>
        <td width="20%" class="FormButton">Product catalog URL</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_product_catalog" value="{$manufacturer.d_product_catalog}" style="width:40%" />
	{if $manufacturer.d_product_catalog ne ""}<a href="{$manufacturer.d_product_catalog}" target="_blank">Open</a>{/if}
	</td>
</tr>

<tr>
        <td width="20%" class="FormButton">Price-list URL</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_price_list" value="{$manufacturer.d_price_list}" style="width:40%" />
	{if $manufacturer.d_price_list ne ""}<a href="{$manufacturer.d_price_list}" target="_blank">Open</a>{/if}
	</td>
</tr>

<tr>
        <td width="20%" class="FormButton">MAP policy</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="d_map_policy" id="d_map_policy"
onchange="javasript:{literal} if (this.value !=''){$('#tr_d_map_prices').show();}else{$('#tr_d_map_prices').hide();}{/literal}"
>
<option value="">N/A</option>
<option value="applies_to_selected_products"{if $manufacturer.d_map_policy eq "applies_to_selected_products"} selected="selected"{/if}>applies to selected products</option>
<option value="applies_to_all_products"{if $manufacturer.d_map_policy eq "applies_to_all_products"} selected="selected"{/if}>applies to all products</option>
</select>
	</td>
</tr>

<tr id="tr_d_map_prices" {if $manufacturer.d_map_policy eq ""}style="display: none;"{/if}>
        <td width="20%" class="FormButton">MAP prices URL</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_map_prices" value="{$manufacturer.d_map_prices}" style="width:40%" />
	{if $manufacturer.d_map_prices ne ""}<a href="{$manufacturer.d_map_prices}" target="_blank">Open</a>{/if}
	</td>
</tr>

<tr>
        <td width="20%" class="FormButton">Shipping weights / dimensions</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="d_shipping_weights_dimensions" id="d_shipping_weights_dimensions">
<option value="">not available</option>
<option value="can_be_found_on_the_distributor_website"{if $manufacturer.d_shipping_weights_dimensions eq "can_be_found_on_the_distributor_website"} selected="selected"{/if}>can be found on the distributor website</option>
<option value="can_be_found_in_the_catalog"{if $manufacturer.d_shipping_weights_dimensions eq "can_be_found_in_the_catalog"} selected="selected"{/if}>can be found in the catalog</option>
<option value="can_be_found_in_the_price_list"{if $manufacturer.d_shipping_weights_dimensions eq "can_be_found_in_the_price_list"} selected="selected"{/if}>can be found in the price-list</option>
</select>
        </td>
</tr>
</table>


{elseif $d_section.distributor_section eq "9"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_9" {if $distributor_section ne "9"}style="display: none;" {/if}>
<tr>
        <td width="20%" class="FormButton">Distributor charges sales/VAT  taxes in the following states/provinces</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_tax_policy_in_states" value="{$manufacturer.d_tax_policy_in_states}" style="width:80%" /></td>
</tr>
</table>


{elseif $d_section.distributor_section eq "10"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_10" {if $distributor_section ne "10"}style="display: none;" {/if}>
<tr>
        <td width="20%" class="FormButton">Warranty period starts when the order is</td>
        <td>&nbsp;</td>
        <td width="80%">
<select name="d_warranty_starts_when_order_is" id="d_warranty_starts_when_order_is">
<option value="shipped"{if $manufacturer.d_warranty_starts_when_order_is eq "shipped"} selected="selected"{/if}>shipped</option>
<option value="received_by_customer"{if $manufacturer.d_warranty_starts_when_order_is eq "received_by_customer"} selected="selected"{/if}>received by the customer</option>
</select>

and lasts <input type="text" name="d_warranty_last_day" value="{$manufacturer.d_warranty_last_day}" size="5" /> days
        </td>
</tr>

<tr>
        <td width="20%" class="FormButton">Re-stocking fee for authorized returns</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" name="d_re_stocking_fee_for_authorized_returns" value="{$manufacturer.d_re_stocking_fee_for_authorized_returns}" size="7" />%</td>
</tr>

<tr>
        <td width="20%" class="FormButton">Re-stocking fee for unauthorized returns</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" name="d_re_stocking_fee_for_unauthorized_returns" value="{$manufacturer.d_re_stocking_fee_for_unauthorized_returns}" size="7" />%</td>
</tr>

<tr>
        <td width="20%" class="FormButton">Distributor return policy</td>
        <td>&nbsp;</td>
        <td width="80%">
{*
{include file="main/textarea.tpl" name="d_distributor_return_policy" cols=55 rows=10 class="InputWidth" data=$manufacturer.d_distributor_return_policy width="80%" btn_rows=3}
*}
<textarea class="new_editor" name="d_distributor_return_policy" rows="20" cols="60" style="width: 80%;">{$manufacturer.d_distributor_return_policy}</textarea>
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
<td width="20%">Warehouse name</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="warehouse_name_{$v_a.id}" name="warehouse_name_{$v_a.id}" size="32" value="{$v_a.warehouse_name}" />
&nbsp;&nbsp;&nbsp;
<input type="button" value="Delete" onclick="javascript: {literal}$('#mode').val('delete_distributor_return_address'); $('#delete_distributor_return_address_number').val('{/literal}{$v_a.id}{literal}'); document.manufacturer.submit();"{/literal} />
</td>
</tr>

<tr>
<td width="20%">Full name</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="full_name_{$v_a.id}" name="full_name_{$v_a.id}" size="32" value="{$v_a.full_name}" />
</td>
</tr>

<tr>
<td width="20%">Company</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="company_{$v_a.id}" name="company_{$v_a.id}" size="32" value="{$v_a.company}" />
</td>
</tr>

<tr>
<td width="20%">{$lng.lbl_address}</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="address_{$v_a.id}" name="address_{$v_a.id}" size="32" maxlength="64" value="{$v_a.address}" />
</td>
</tr>

<tr>
<td>{$lng.lbl_address_2}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="address_2_{$v_a.id}" name="address_2_{$v_a.id}" size="32" maxlength="64" value="{$v_a.address_2}" />
</td>
</tr>

<tr>
<td>{$lng.lbl_city}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="city_{$v_a.id}" name="city_{$v_a.id}" size="32" maxlength="64" value="{$v_a.city}" />
</td>
</tr>

<tr>
<td>{$lng.lbl_country}</td>
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
<td>{$lng.lbl_state}</td>
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
<td>{$lng.lbl_zip_code}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="zipcode_{$v_a.id}" name="zipcode_{$v_a.id}" size="32" maxlength="32" value="{$v_a.zipcode}" onchange="check_zip_code()"  />
</td>
</tr>

<tr>
<td width="20%">Phone</td>
<td>&nbsp;</td>
<td nowrap="nowrap" width="80%">
<input type="text" id="phone_{$v_a.id}" name="phone_{$v_a.id}" size="32" maxlength="64" value="{$v_a.phone}" />
ext
<input type="text" id="ext_{$v_a.id}" name="ext_{$v_a.id}" size="3" value="{$v_a.ext}" />
</td>
</tr>


{/foreach}
<tr><td colspan="3"><hr /></td></tr>
{/if}
</table>
<br />

{elseif $d_section.distributor_section eq "11"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_11" {if $distributor_section ne "11"}style="display: none;" {/if}>
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
        <td width="20%" class="FormButton">We can save</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton"><input type="text" name="d_we_can_save" value="{$manufacturer.d_we_can_save}" />%
         if we pay to distributor by
<select name="d_pay_to_distributor_by" id="d_pay_to_distributor_by">
<option value=""></option>
<option value="check"{if $manufacturer.d_pay_to_distributor_by eq "check"} selected="selected"{/if}>check</option>
<option value="EFT"{if $manufacturer.d_pay_to_distributor_by eq "EFT"} selected="selected"{/if}>electronic funds transfer (EFT)</option>
</select>
        </td>
</tr>

<tr>
        <td width="20%" class="FormButton">NET payment terms in days (put 0 if N/A)</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton">NET<input type="text" name="d_net_payment_terms_in_days" value="{$manufacturer.d_net_payment_terms_in_days}" /></td>
</tr>

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

<tr>
        <td width="20%" class="FormButton">Search keyphrase for reconciliation</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton"><input type="text" name="d_search_keyphrase_for_reconciliation" value="{$manufacturer.d_search_keyphrase_for_reconciliation}" style="width: 80%;" /></td>
</tr>

</table>


{elseif $d_section.distributor_section eq "12"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_12" {if $distributor_section ne "12"}style="display: none;" {/if}>
<tr>
        <td width="20%" class="FormButton">Tracking numbers are</td>
        <td>&nbsp;</td>
        <td width="80%">
<input type="checkbox" name="d_available_on_distributor_site_checkbox" value="Y"{if $manufacturer.d_available_on_distributor_site_checkbox eq 'Y'} checked="checked"{/if} />available on the distributor website's URL
<input style="width:40%" type="text" name="d_available_on_distributor_site_url" value="{$manufacturer.d_available_on_distributor_site_url}" /> {if $manufacturer.d_available_on_distributor_site_url ne ""}<a target="_blank" href="{$manufacturer.d_available_on_distributor_site_url}">link</a>{/if}
<br/>

<input type="checkbox" name="d_sent_by_email_to" value="Y"{if $manufacturer.d_sent_by_email_to eq 'Y'} checked="checked"{/if} />sent by email to
<input style="width:40%" type="text" name="d_sent_by_email_to_email_address" value="{$manufacturer.d_sent_by_email_to_email_address}" />
<br/>
<input type="checkbox" name="d_put_on_the_invoices" value="Y"{if $manufacturer.d_put_on_the_invoices eq 'Y'} checked="checked"{/if} />put on the invoices
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
	<td><textarea class="new_editor" name="d_message_body_14" rows="20" cols="60" style="width:80%">{$manufacturer.d_message_body_14|default:$lng.txt_distributor_section_14_email_body}</textarea></td>
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

        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3" style="color: #000000;"><B>Availability request schedule</B></td></tr>
        <tr><td colspan="3" class="SubHeaderBlackLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>

        <tr><td colspan="3">{$lng.lbl_server_min_distributor_time}</td></tr>

	<tr>
        <td width="20%" class="FormButton">Server time - Distributor time</td>
        <td>&nbsp;</td>
        <td width="80%" class="FormButton"><input type="text" name="d_server_min_distributor_time" value="{$manufacturer.d_server_min_distributor_time}" style="width:10%" /></td>
	</tr>

        </table>
        </td>

</tr>


</table>


{elseif $d_section.distributor_section eq "15"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_15" {if $distributor_section ne "15"}style="display: none;" {/if}>

<tr>
        <td width="20%" class="FormButton">{$lng.lbl_quick_links_1}</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_website_search_for_sku_url" value="{$manufacturer.d_website_search_for_sku_url}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">{$lng.lbl_quick_links_2}</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_link_to_order_distributors_website" value="{$manufacturer.d_link_to_order_distributors_website}" style="width:80%" /></td>
</tr>

</table>


{elseif $d_section.distributor_section eq "16"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_16" {if $distributor_section ne "16"}style="display: none;" {/if}>
<tr>
        <td width="20%" class="FormButton">Product questions 'send to' email</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_product_questions_send_to_email" value="{$manufacturer.d_product_questions_send_to_email}" style="width:80%" /></td>
</tr>
</table>

{elseif $d_section.distributor_section eq "17"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_17" {if $distributor_section ne "17"}style="display: none;" {/if}>

<tr>
        <td width="20%" class="FormButton">Enable feed</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="checkbox" name="d_enable_feed" value="Y"{if $manufacturer.d_enable_feed eq 'Y'} checked="checked"{/if} /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Feed updation frequency (in hours)</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_feed_updation_frequency" value="{$manufacturer.d_feed_updation_frequency}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">FTP host</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_ftp_host" value="{$manufacturer.d_ftp_host}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">FTP login</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_ftp_login" value="{$manufacturer.d_ftp_login}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">FTP password</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_ftp_password" value="{$manufacturer.d_ftp_password}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">FTP folder</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_ftp_folder" value="{$manufacturer.d_ftp_folder}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Feed procedure ID</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_feed_procedure_id" value="{$manufacturer.d_feed_procedure_id}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Product management team email</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_product_management_team_email" value="{$manufacturer.d_product_management_team_email}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Most recent feed updation date and time</td>
        <td>&nbsp;</td>
        <td width="80%"><input readonly="readonly" type="text" size="50" name="d_most_recent_feed_updation_date" value="{$manufacturer.d_most_recent_feed_updation_date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Last feed rows processed</td>
        <td>&nbsp;</td>
        <td width="80%"><input readonly="readonly" type="text" size="50" name="d_last_feed_rows_processed" value="{$manufacturer.d_last_feed_rows_processed}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Validation threshold</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="d_validation_threshold" value="{$manufacturer.d_validation_threshold}" style="width:80%" /></td>
</tr>

<tr>
<td class="FormButton" width="20%">Comments</td>
<td>&nbsp;</td>
<td width="80%"><textarea name="product_feeds_comments" rows="5" cols="60" style="width:80%">{$manufacturer.product_feeds_comments}</textarea></td>
</tr>

</table>


{elseif $d_section.distributor_section eq "18"}
<table cellpadding="3" cellspacing="1" width="100%" id="distributor_section_id_18" {if $distributor_section ne "18"}style="display: none;" {/if}>

<tr>
        <td width="20%" class="FormButton">Enable feed</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="checkbox" name="spf_enabled_feed" value="Y"{if $product_feed_info.enabled_feed eq 'Y'} checked="checked"{/if} /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Marked in DB as "launched by CRON"</td>
        <td>&nbsp;</td>
        <td width="80%">&nbsp;{if $product_feed_info.is_launched eq 'Y'}Yes{else}No{/if}</td>
</tr>

<tr>
        <td width="20%" class="FormButton">Import new products</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="checkbox" name="spf_import_new_products" value="Y"{if $product_feed_info.import_new_products eq 'Y'} checked="checked"{/if} /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Import new and update existing products</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="checkbox" name="spf_import_new_and_update_existing_products" value="Y"{if $product_feed_info.import_new_and_update_existing_products eq 'Y'} checked="checked"{/if} /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Feed updation frequency (in days)</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_updation_frequency" value="{$product_feed_info.updation_frequency|escape}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">FTP host</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_ftp_host" value="{$product_feed_info.ftp_host|escape}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">FTP login</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_ftp_login" value="{$product_feed_info.ftp_login|escape}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">FTP password</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_ftp_password" value="{$product_feed_info.ftp_password|escape}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">FTP folder</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_ftp_folder" value="{$product_feed_info.ftp_folder|escape}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Feed procedure ID</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_feed_procedure_id" value="{$product_feed_info.feed_procedure_id|escape}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Product management team email</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_product_management_team_email" value="{$product_feed_info.product_management_team_email|escape}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Last import date and time</td>
        <td>&nbsp;</td>
        <td width="80%">
	<input readonly="readonly" type="text" size="50" name="spf_last_import_date" value="{$product_feed_info.last_import_date|date_format:'%d-%b-%Y&nbsp; %H:%M:%S'}" style="width:80%" />
	</td>
</tr>

<tr>
        <td width="20%" class="FormButton">Last lines count in file</td>
        <td>&nbsp;</td>
        <td width="80%"><input readonly="readonly" type="text" size="50" name="spf_last_products_count_in_file" value="{$product_feed_info.last_products_count_in_file}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Last imported/updated products count</td>
        <td>&nbsp;</td>
        <td width="80%"><input readonly="readonly" type="text" size="50" name="spf_last_imported_updated_products_count" value="{$product_feed_info.last_imported_updated_products_count}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Default productid</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_default_productid" value="{$product_feed_info.default_productid}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Default parent categoryid</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_default_parent_categoryid" value="{$product_feed_info.default_parent_categoryid}" style="width:80%" /></td>
</tr>

<tr>
        <td width="20%" class="FormButton">Storefront id</td>
        <td>&nbsp;</td>
        <td width="80%"><input type="text" size="50" name="spf_storefrontid" value="{$product_feed_info.storefrontid}" style="width:80%" /></td>
</tr>

<tr>
	<td class="FormButton" width="20%">Comments</td>
	<td>&nbsp;</td>
	<td width="80%"><textarea name="spf_comments" rows="5" cols="60" style="width:80%">{$product_feed_info.comments|escape}</textarea></td>
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
	<td width="*"><input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} "{$disabled} /></td>
</tr>
</table>

</form>

{/capture}

{foreach from=$distributor_sections item=d_section key=k_section}
{if $d_section.distributor_section eq $distributor_section}
{include file="dialog.tpl" title=$d_section.title content=$smarty.capture.dialog extra='width="100%"'}
{/if}
{/foreach}

{* {include file="dialog.tpl" title=$lng.lbl_manufacturer_details content=$smarty.capture.dialog extra='width="100%"'} *}

{/if}

