{* $Id: configuration.tpl,v 1.91.2.7 2007/01/16 09:06:32 twice Exp $ *}
{if $option ne 'Multiple_Storefronts'}
    {include file="page_title.tpl" title=$lng.lbl_general_settings}

    {$lng.txt_general_settings_top_text}

    <br /><br />

    {include file="dialog_tools.tpl"}

     <br />
{else}
	{include file="page_title.tpl" title=$sf_page_title}
	{include file="main/include_js.tpl" src="main/popup_image_selection.js"}
{/if}

{capture name=dialog}

{if $active_modules.Multiple_Storefronts && !$configuration && $option ne "Filter_Presets"}

<table cellpadding="3" cellspacing="1" width="100%">

{assign var="option_title" value="option_title_`$option`"}
{if $lng.$option_title}
{assign var="option_title" value=$lng.$option_title}
{else}
{assign var="option_title" value=$option|replace:"_":" "}
{assign var="option_title" value="`$option_title` options"}
{/if}

<tr>
<td class="TopLabel">{include file="main/subheader.tpl" title=$option_title class="black"}</td>
</tr>

<tr>
<td>{if $current_storefront gt 0}{$lng.lbl_no_options}{else}{$lng.txt_main_sf}{/if}</td>
</tr>

</table>
	
{else}

{assign var="cycle_name" value="sep"}

{if $option ne "User_Profiles" && $option ne "Contact_Us" && $option ne "Search_products"}
<form action="configuration.php?option={$option|escape}" method="post" name="processform" enctype="multipart/form-data">
{/if}

{if $active_modules.Multiple_Storefronts and $option eq "Multiple_Storefronts"}
<input type="hidden" name="storefrontid" value="{$current_storefront}" />
{/if}

<table cellpadding="3" cellspacing="1" width="100%">

{assign var="option_title" value="option_title_`$option`"}
{if $lng.$option_title}
{assign var="option_title" value=$lng.$option_title}
{else}
{assign var="option_title" value=$option|replace:"_":" "}
{assign var="option_title" value="`$option_title` options"}
{/if}
{if $option eq 'Filter_Presets' && $filter ne ''}
    {if $filter.title ne ''}
        {assign var="filter_preset_title" value=$filter.title}
    {else}
        {assign var="filter_preset_title" value=$lng.lbl_filter_empty|escape}
    {/if}
{assign var="option_title" value="`$lng.lbl_order_filter_preset`: `$filter_preset_title` (`$filter.row`, `$filter.column`) <a href=\"configuration.php?option=Filter_Presets\" class=\"preset_back\">`$lng.lbl_back_to_presets`</span>"}
{/if}

{if $option  ne 'Search_products'}
<tr>
<td class="TopLabel">
{include file="main/subheader.tpl" title=$option_title class="black"}
</td>
</tr>
{/if}

{if $option eq 'Shipping_Label_Generator'}
<tr>
<td>
<div align="right">
{include file="buttons/button.tpl" button_title=$lng.lbl_usps_labels_help href="javascript:window.open('popup_info.php?action=TSTLBL','TSTLBL_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');"}
</div>

</td>
</tr>
{/if}

</table>

{if $option ne 'Search_products'}
<br />
{/if}

{if $option eq "User_Profiles"}

{include file="admin/main/user_profiles.tpl"}

{elseif $option eq "Contact_Us"}

{include file="admin/main/contact_us_profiles.tpl"}

{elseif $option eq "Search_products"}

{include file="admin/main/search_products_form.tpl"}

{elseif $option eq "Filter_Presets"}

{include file="admin/main/filter_presets.tpl"}

{else}

{if $option eq "Google_Checkout"}
{$lng.txt_gcheckout_setup_note|substitute:"callback_url":$gcheckout_callback_url}
<br />
<br />
{include file="modules/Google_Checkout/gcheckout_requirements.tpl"}

{/if}
{if $option eq "Image_Verification"}
{include file="modules/Image_Verification/spambot_requirements.tpl"}
{/if}

{if $option eq "XPayments_Connector"}
  {include file="modules/XPayments_Connector/config_recommends.tpl"}
{/if}

<table cellpadding="3" cellspacing="1" width=100%>

{assign var="first_row" value=1}

{section name=cat_num loop=$configuration}

{assign var="opt_comment" value="opt_`$configuration[cat_num].name`"}
{assign var="opt_label_id" value="opt_`$configuration[cat_num].name`"}

{if $configuration[cat_num].type eq "separator"}

<tr><td colspan="3" class="TableSeparator">{if $first_row eq 0}<br />{/if}<br />{if $lng.$opt_comment ne ""}{$lng.$opt_comment}{elseif $configuration[cat_num].comment}{$configuration[cat_num].comment}{else}<hr />{/if}<br /><br /></td></tr>
{assign var="cycle_name" value=$configuration[cat_num].name}

{else}

{if $configuration[cat_num].name eq "realtime_shipping"}

<tr><td colspan="3">
{$lng.txt_rate_estimation_note}<br /><br />
</td>
</tr>
{elseif $configuration[cat_num].name eq "fancy_cache"}

<tr>
	<td colspan="3"><br /><br />{$lng.txt_fancy_cache_note}<br /></td>
</tr>

{/if}

{if $configuration[cat_num].name eq "intershipper_username" or $configuration[cat_num].name eq "USPS_servername" or $configuration[cat_num].name eq "UPS_username" or $configuration[cat_num].name eq "CPC_merchant_id" or $configuration[cat_num].name eq "ARB_id" or $configuration[cat_num].name eq "dhl_siteid"}

<tr>
<td colspan="3">
{if $configuration[cat_num].name eq "intershipper_username"}
{$lng.txt_intershipper_account_note}
{elseif $configuration[cat_num].name eq "USPS_servername"}
{$lng.txt_usps_account_note}
{elseif $configuration[cat_num].name eq "CPC_merchant_id"}
{$lng.txt_canadapost_account_note}
{elseif $configuration[cat_num].name eq "ARB_id"}
{$lng.txt_airborne_account_note}
{elseif $configuration[cat_num].name eq "dhl_siteid"}
{$lng.txt_dhl_account_note}
{/if}
<br /><br /></td>
</tr>

{/if}

{if $cols_count eq "1"}
{assign var="bgcolor" value=""}
{assign var="cols_count" value=""}
{else}
{assign var="bgcolor" value="class=''"}
{assign var="cols_count" value="1"}
{/if}

{cycle name=$cycle_name values=" class='TableSubHead', " assign="row_style"}

<tr>
	<td width="3%">&nbsp;</td>
	<td {$row_style} width="37%">
{if $configuration[cat_num].type eq "checkbox"}
<label for="{$opt_label_id}">
{/if}
{if $lng.$opt_comment}{$lng.$opt_comment}{else}{$configuration[cat_num].comment}{/if}:
{if $configuration[cat_num].type eq "checkbox"}
</label>
{/if}
	</td>
	<td {$row_style} width="60%">

{assign var="prefix" value=false}

{if $configuration[cat_num].name eq "default_country" || $configuration[cat_num].name eq "location_country"}
	<select name="{$configuration[cat_num].name}" id="{$configuration[cat_num].name}">
{section name=country_idx loop=$countries}
		<option value="{$countries[country_idx].country_code}"{if $countries[country_idx].country_code eq $configuration[cat_num].value} selected="selected"{/if}>{$countries[country_idx].country}</option>
{/section}
	</select>
{assign var="prefix" value=$configuration[cat_num].name|regex_replace:"/_country$/":""}

{elseif $configuration[cat_num].name eq "location_state" || $configuration[cat_num].name eq "default_state"}
{if $configuration[cat_num].name eq "location_state"}
{assign var="country" value=$config.Company.location_country}
{else}
{assign var="country" value=$config.General.default_country}
{/if}
{include file="main/states.tpl" states=$states name=$configuration[cat_num].name default=$configuration[cat_num].value default_country=$country}
{assign var="prefix" value=$configuration[cat_num].name|regex_replace:"/_state$/":""}
{assign_ext var="state_values[`$prefix`]" value=$configuration[cat_num].value}

{elseif $configuration[cat_num].name eq "date_format"}
	<select name="{$configuration[cat_num].name}">
{section name=df loop=$date_formats}
		<option value="{$date_formats[df]}"{if $configuration[cat_num].value eq $date_formats[df]} selected="selected"{/if}>{$gmnow|date_format:$date_formats[df]} ({$date_formats_alt[df]})</option>
{/section}
	</select>

{elseif $configuration[cat_num].name eq "time_format"}
	<select name="{$configuration[cat_num].name}">
{section name=df loop=$time_formats}
		<option value="{$time_formats[df]}"{if $configuration[cat_num].value eq $time_formats[df]} selected="selected"{/if}>{$gmnow|date_format:$time_formats[df]|default:$lng.lbl_none}</option>
{/section}
	</select>

{elseif $configuration[cat_num].name eq "blowfish_enabled" && $configuration[cat_num].value eq "Y" && $is_merchant_password ne "Y"}
{$lng.lbl_enabled}<input type="hidden" name="{$configuration[cat_num].name}" value='{$configuration[cat_num].value}' />
	</td>
</tr>

<tr>
<td colspan="2"><font class="ErrorMessage">{$lng.txt_no_disable_blowfish}</font></td>
</tr>

{elseif $option eq "Logging" and $configuration[cat_num].name|regex_replace:"/_.*/":"" eq "log"}
<select name="{$configuration[cat_num].name}">
<option value="N"{if $configuration[cat_num].value eq "N"} selected="selected"{/if}>{$lng.lbl_log_act_nothing}</option>
<option value="L"{if $configuration[cat_num].value eq "L"} selected="selected"{/if}>{$lng.lbl_log_act_log}</option>
<option value="E"{if $configuration[cat_num].value eq "E"} selected="selected"{/if}>{$lng.lbl_log_act_email}</option>
<option value="LE"{if $configuration[cat_num].value eq "LE"} selected="selected"{/if}>{$lng.lbl_log_act_log_n_email}</option>
</select>

{elseif $configuration[cat_num].name eq "default_giftcert_template"}
<select name="{$configuration[cat_num].name}">
{foreach from=$gc_templates item=gc_tpl}
<option value="{$gc_tpl|escape}"{if $configuration[cat_num].value eq $gc_tpl} selected="selected"{/if}>{$gc_tpl}</option>
{/foreach}
</select>

{elseif $configuration[cat_num].name eq "periodic_logs"}
<input type="hidden" name="periodic_logs" value="" />
<select name="periodic_logs[]" multiple="multiple" size="10">
{foreach key=log_label item=txt_label from=$periodical_logs_names}
<option value="{$log_label}"{if $periodical_log_labels.$log_label ne ""} selected="selected"{/if}>{$txt_label}</option>
{/foreach}
</select>

{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "company_website"}
<input type="text" style="width: 370px;" name="{$configuration[cat_num].name}" value="{$configuration[cat_num].value|escape:html}" /> 
&nbsp;&nbsp;<a href="{$configuration[cat_num].value}" target="_blank">{$lng.lbl_sf_website}</a>


{* ----- 
{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "cidev_tracking_code"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="5">{$configuration[cat_num].value|escape}</textarea>

{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "cidev_main_page_code"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="5">{$configuration[cat_num].value|escape}</textarea>
{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "cidev_footer_code"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="5">{$configuration[cat_num].value|escape}</textarea>

{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "cidev_keywords"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="5">{$configuration[cat_num].value|escape}</textarea>
----- *}

{* ---- *}
{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "sf_top_image_favicon"}
<span id="upload_fname_favicon">{if $storefront_info.is_image_favicon}<a href="{$catalogs.customer}/image.php?type=F&amp;id={$current_storefront}" target="_blank">{$storefront_info.image_favicon.filename|escape}</a>&nbsp;&nbsp;&nbsp;{/if}</span>
<input type="button" id="F_edit_image_favicon" value="{$lng.lbl_plus|strip_tags:false|escape}" onclick="javascript: popup_image_selection('F', '{$current_storefront}', 'edit_image_favicon');" />
<input type="file" id="file_edit_image_favicon" size="25" name="file_edit_image_favicon" onchange="javascript: $('#F_edit_image_favicon').attr('disabled', 'disabled');" />
{* ---- *}

{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "sf_top_image"}
<span id="upload_fname">{if $storefront_info.is_image}<a href="{$catalogs.customer}/image.php?type=S&amp;id={$current_storefront}" target="_blank">{$storefront_info.image.filename|escape}</a>&nbsp;&nbsp;&nbsp;{/if}</span>
<input type="button" id="S_edit_image" value="{$lng.lbl_plus|strip_tags:false|escape}" onclick="javascript: popup_image_selection('S', '{$current_storefront}', 'edit_image');" />
<input type="file" id="file_edit_image" size="25" name="file_edit_image" onchange="javascript: $('#S_edit_image').attr('disabled', 'disabled');" />

{elseif $configuration[cat_num].name eq "spambot_arrest_img_generator"}
<select name="{$configuration[cat_num].name}">
{foreach item=generator from=$img_generators}
<option value="{$generator}" {if $configuration[cat_num].value eq $generator}selected="selected"{/if}>{$generator}</option>
{/foreach}
</select>
{elseif $configuration[cat_num].type eq "numeric"}
<input type="text" size="10" name="{$configuration[cat_num].name}" value="{$configuration[cat_num].value|formatnumeric}" />

{elseif $configuration[cat_num].type eq "text"}
<input type="text" style="width: 370px;" name="{$configuration[cat_num].name}" value="{$configuration[cat_num].value|escape:html}" />

{elseif $configuration[cat_num].type eq "checkbox"}
<input type="checkbox" id="{$opt_label_id}" name="{$configuration[cat_num].name}"{if $configuration[cat_num].value eq "Y"} checked="checked"{/if} />

{elseif $configuration[cat_num].type eq "textarea"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="5">{$configuration[cat_num].value|escape:html}</textarea>

{elseif ($configuration[cat_num].type eq "selector" || $configuration[cat_num].type eq "multiselector") && $configuration[cat_num].variants ne ''}
{if $configuration[cat_num].type eq "multiselector"}
<select name="{$configuration[cat_num].name}[]" multiple="multiple" size="5">
{else}
<select name="{$configuration[cat_num].name}"{if $configuration[cat_num].auto_submit} onchange="javascript: document.processform.submit()"{/if}>
{/if}
{foreach from=$configuration[cat_num].variants item=vitem key=vkey}
	<option value="{$vkey}"{if $vitem.selected} selected="selected"{/if}>{$vitem.name}</option>
{/foreach}
</select>
{/if}

{if $prefix ne ''}
{if $dynamic_states.$prefix > 0}
{math assign="next" equation="x+1" x=$dynamic_states.$prefix}
{assign_ext var="dynamic_states[`$prefix`]" value=$next}
{else}
{assign_ext var="dynamic_states[`$prefix`]" value=1}
{/if}
{/if}
</td>
</tr>

{/if}

{assign var="first_row" value=0}

{/section}

{if $dynamic_states ne '' && $js_enabled eq 'Y' && $config.General.use_js_states eq 'Y'}
<tr style="display: none;">
<td>
{include file="change_states_js.tpl"}
{foreach from=$dynamic_states item=cnt key=name}
{if $cnt eq 2}
{include file="main/register_states.tpl" state_name="`$name`_state" country_name="`$name`_country" state_value=$state_values.$name}
{/if}
{/foreach}

</td>
</tr>
{/if}
{if $option eq "Product_Page"}
	{include file="admin/main/product_page_options.tpl"}
{/if}
<tr>
<td colspan="3"><br /><br />
<input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} "  />
</td>
</tr>

</table>

{if $option ne "User_Profiles" && $option ne "Contact_Us" && $option ne "Search_products"}
</form>
{/if}

{if $option eq "Shipping" && $is_realtime}

<hr />

<h3>{$lng.lbl_test_realtime_calculation}</h3>

{$lng.txt_test_realtime_calculation_text}

<br /><br />

<form action="test_realtime_shipping.php" target="_blank">

{$lng.lbl_package_weight} <input type="text" name="weight" value="1" /> <input type="submit" value="{$lng.lbl_test|strip_tags:false|escape}" />

</form>

{elseif $option eq "Security"}

<hr />

<h3>{$lng.lbl_test_data_encryption}</h3>

<a href="test_pgp.php">{$lng.lbl_test_data_encryption_link}</a>

{elseif $option eq "XPayments_Connector"}

  {include file="modules/XPayments_Connector/config_bottom.tpl"}

{/if}

<br />

{/if}

{if $current_storefront ne '0'}
<script type="text/javascript">
{strip}
var ds_config = new Array(
{foreach from=$domain_specific_config[$option] item=v key=k name="f_dc_config"}
	{if $v eq 'Y'}
		{if !$smarty.foreach.f_dc_config.first}, {/if}'{$k}'
	{/if}
{/foreach}
);
{/strip}

var txt_domain_specific_option = '{$lng.txt_domain_specific_option}';

{literal}
if (ds_config.length > 0) {
	for (i = 0; i < ds_config.length; i++) {
		$('input[name="' + ds_config[i] + '"]').attr('disabled', 'disabled');
		$('input[name="' + ds_config[i] + '"]').after('<br />' + txt_domain_specific_option);
	}
}
{/literal}
</script>
{/if}
{/if} {* /if $active_module.Multiple_Storefronts && !$configuration*}

{/capture}

{if $option eq 'Multiple_Storefronts'}
    {assign var=capture_title value=$lng.lbl_sf_properties}
{else}
    {assign var=capture_title value=$lng.lbl_general_settings}
{/if}

{include file="dialog.tpl" title=$capture_title content=$smarty.capture.dialog extra='width="100%"'}
{if $additional_config}{include file=$additional_config}{/if}
