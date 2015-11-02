{* popup_shipquote.tpl random *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>{$lng.lbl_shipping_quote} :: {$config.Company.company_name}</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />

{include file="check_zipcode_js.tpl"}
{if $config.General.use_js_states eq 'Y'}
{include file="change_states_js.tpl"}
{/if}

<link rel="stylesheet" href="{$SkinDir}/US_City_List/jquery.autocomplete.css" />
<script src="{$SkinDir}/US_City_List/jquery-1.4.js" type="text/javascript"></script>
<script src="{$SkinDir}/US_City_List/jquery.autocomplete.js" type="text/javascript"></script>
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>

<link rel="stylesheet" href="{$SkinDir}/lib/colorbox/colorbox.css" />
<script src="{$SkinDir}/lib/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}

        function cidev_strtoupper (str) {
                return (str + '').toUpperCase();
        }

        function cidev_get_country_code (countryname_id){

                var countryname_value = $('#'+countryname_id).val();
                countryname_value = $.trim(countryname_value); 

                var countrycode_value = countryname_value;

                {/literal}
                {section name=country_idx loop=$countries}
                {literal}
                if (cidev_strtoupper(countryname_value) == cidev_strtoupper("{/literal}{$countries[country_idx].country|amp}{literal}")){
                        countrycode_value = "{/literal}{$countries[country_idx].country_code|amp}{literal}";
                }
                {/literal}
                {/section}
                {literal}

                return countrycode_value;
        }

        function cidev_get_state_code (statename_id, countryname_id){

                var statename_value = $('#'+statename_id).val(); 
                statename_value = $.trim(statename_value); 
                var statecode_value = statename_value;
                var countrycode_value = cidev_get_country_code(countryname_id);

                {/literal}
                {section name=state_idx loop=$states}
                {literal}
                if (cidev_strtoupper(statename_value) == cidev_strtoupper("{/literal}{$states[state_idx].state|amp}{literal}") && cidev_strtoupper(countrycode_value) == cidev_strtoupper("{/literal}{$states[state_idx].country_code|amp}{literal}")){
                        statecode_value = "{/literal}{$states[state_idx].state_code|amp}{literal}";
                }
                {/literal}
                {/section}
                {literal}
                
                return statecode_value;
        }

	function ltrim(stringToTrim) {
		 return stringToTrim.replace(/^\s+/,"");
	}

        function check_zip_code_ship(zipcode_id, countryname_id) {

                var zipcode = $('#'+zipcode_id).val();
//                zipcode = $.trim(zipcode);
		zipcode = ltrim(zipcode);
                $('#'+zipcode_id).val(zipcode);

                var countrySelected = cidev_get_country_code(countryname_id);
                return cidev_new_check_zip_code_field(countrySelected, cidev_id$(zipcode_id), zipcode_id);
        }

        function cidev_check_zip(){

                var s_city_in_shipquoteform = document.forms["shipquoteform"].s_city.value;
                var s_state_in_shipquoteform = cidev_get_state_code("s_statename", "s_countryname");
//                var s_zipcode_in_shipquoteform_length = document.forms["shipquoteform"].s_zipcode.value.length;
                var s_zipcode_in_shipquoteform = document.forms["shipquoteform"].s_zipcode.value;

                var s_country_in_shipquoteform = cidev_get_country_code("s_countryname");


                if (s_country_in_shipquoteform == "US"){
                        document.getElementById("tr_show_text_for_us").style.display = '';
                } else {
                        document.getElementById("tr_show_text_for_us").style.display = 'none';
                }

                if (s_country_in_shipquoteform == "US"){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_zip&s_city_in_shipquoteform=' + s_city_in_shipquoteform + '&s_state_in_shipquoteform=' + s_state_in_shipquoteform + '&s_zipcode_in_shipquoteform=' + s_zipcode_in_shipquoteform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
							cidev_id$("cidev_ship_form_show_zip").innerHTML=cidev_xmlHttp.responseText;
							if (cidev_id$("s_zipcode_hidden")){
								document.forms["shipquoteform"].s_zipcode.value = cidev_id$("s_zipcode_hidden").value;
							}
                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_popup_shipquote.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_check_zip()', 1000);
                        }
		}
        }

	function cidev_check_address() {
		var s_country_in_shipquoteform = cidev_get_country_code("s_countryname");

		if (s_country_in_shipquoteform == "US"){
			document.forms["shipquoteform"].s_zipcode.value = document.forms["shipquoteform"].s_zipcode.value.replace(/[^\w]/g, "");
			cidev_show_state_city();
		} else {
			document.forms["shipquoteform"].s_zipcode.value = document.forms["shipquoteform"].s_zipcode.value.replace(/[^\w\s]/g, "");
		}
	}

	function cidev_show_state_city(){
			document.forms["shipquoteform"].s_zipcode.value = ltrim(document.forms["shipquoteform"].s_zipcode.value);
			var s_zipcode_in_shipquoteform = document.forms["shipquoteform"].s_zipcode.value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_state_city&s_zipcode_in_shipquoteform=' + s_zipcode_in_shipquoteform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("tr_s_state_s_city_table").innerHTML=cidev_xmlHttp.responseText;

							if (cidev_id$("td_s_state_show_text")){
								document.forms["shipquoteform"].s_statename.value = cidev_id$("td_s_state_show_text").innerHTML;
							}

							if (cidev_id$("td_s_city_show_text")){
								document.forms["shipquoteform"].s_city.value = cidev_id$("td_s_city_show_text").innerHTML;
							}

                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_popup_shipquote.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_show_state_city()', 1000);
                        }
	}

        function onSelectChange() {
                var cityFilePath = '';
                var stateSelected = cidev_get_state_code("s_statename", "s_countryname");

                $('#s_city').unautocomplete();

                var countrySelected = cidev_get_country_code("s_countryname"); 
        
                if (countrySelected == "US"){

                        cityFilePath = "skin1_kolin/US_City_List/" +stateSelected.toLowerCase()+".js";

                        $.getScript(cityFilePath, function() {

                                $('#s_city').autocomplete(city, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
                }
        };

        function cidev_load_countries() {
                var countryFilePath = "skin1_kolin/US_City_List/all_countries.js";
                
                        $.getScript(countryFilePath, function() {

                                $('#s_countryname').autocomplete(country_names, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
        }

        function cidev_load_states() {

                var stateFilePath = "";
                var country_code = cidev_get_country_code("s_countryname");
        
                if (country_code == "US"){
                        stateFilePath = "skin1_kolin/US_City_List/us_states.js";
                }
                if (country_code == "CA"){
                        stateFilePath = "skin1_kolin/US_City_List/ca_states.js";
                }
                
                $('#s_statename').unautocomplete();

                if (country_code == "US" || country_code == "CA"){
                        $.getScript(stateFilePath, function() {

                                $('#s_statename').autocomplete(state_names, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
                }
        }

{/literal}
-->
</script>
</head>
<body{$reading_direction_tag} style="background-color: #FBFBF3;">

{if $smarty.get.shipping_error eq "Y"}
{include file="dialog_message.tpl"}
{/if}

{assign var="count_products" value=0}
{foreach from=$cart.products item=item key=key}
{math assign="count_products" equation="x+1" x=$count_products}
{/foreach}


<script type="text/javascript">
//<![CDATA[
{literal}
$(document).ready(function() {  

        $('#s_countryname').focusout(function() {

                var countrySelected = cidev_get_country_code("s_countryname");

                if (countrySelected == "US" || countrySelected == "CA"){
                        cidev_load_states();
                        onSelectChange();
                } 

                if (countrySelected != "US") {
                        $('#s_city').unautocomplete();
                }

                if (countrySelected != "US" && countrySelected != "CA") {
                        $('#s_statename').unautocomplete();
                }
        });

        $('#s_statename').focusout(function() {
                onSelectChange();
        });

        $('#s_zipcode').focusout(function() {
                onSelectChange();
        });

	function start() {

		{/literal}{if $mode eq ''}{literal}

		cidev_load_countries();
		cidev_load_states();
		onSelectChange();
		cidev_check_zip();

		{/literal}{/if}{literal}
	}
       
        window.onload = start();
});

{/literal}
{if $login eq ""}
{literal}
        var geo_litecity_location_city = "{/literal}{$geo_litecity_location.city}{literal}";
        var geo_litecity_location_region = "{/literal}{$geo_litecity_location.region}{literal}";
        var geo_litecity_location_country = "{/literal}{$geo_litecity_location.country}{literal}";

        {/literal}
        {section name=state_idx loop=$states}
        {literal}
        if (geo_litecity_location_region == "{/literal}{$states[state_idx].state_code|amp}{literal}" && geo_litecity_location_country == "{/literal}{$states[state_idx].country_code|amp}{literal}"){
                var geo_litecity_location_region_name = "{/literal}{$states[state_idx].state|amp}{literal}";
        }
        {/literal}
        {/section}
        {literal}
{/literal}
{else}
{literal}
        var geo_litecity_location_city = "";
        var geo_litecity_location_region = "";
        var geo_litecity_location_country = "";

	var geo_litecity_location_region_name = "";
{/literal}
{/if}
{literal}

{/literal}
//]]>
</script>


{* ------------------- *}
{include file="cidev_tracking_code.tpl" } 
{* ------------------- *}


<form action="popup_shipquote.php" method="post" name="shipquoteform">
<input type="hidden" name="mode" value="{if $mode eq 'grandtotal'}checkout{elseif $mode eq 'shipping'}grandtotal{else}shipping{/if}" />


<table width="100%" cellpadding="0" cellspacing="0" align="center" class="Container">
<tr>
	<td class="PopupTitle">{$lng.lbl_shipping_quote}</td>
</tr>
<tr>
	<td height="1"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
<tr>
	<td class="PopupBG" height="1"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
{if $err ne ''}
<tr> 
    <td align="center"><br /><font class="Star">{if $err eq 'exception'}{$lng.txt_exception_warning}{elseif $err eq 'avail'}{$lng.txt_out_of_stock}{/if}</font><br /></td>
</tr> 
{/if}
<tr>
	<td class="Container">
	<table cellspacing="6" cellpadding="0" width="100%">

	{if $mode eq ''}
	<tr>
		<td height="150"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
	</tr>
	<tr>
		<td align="center">

		<div id="cidev_ship_form_show_zip">
		{include file="customer/main/cidev_ship_form_show_zip.tpl"}
		</div>


	    <table cellpadding="2" cellspacing="1" border="0">
		<tr>
			<td align="left" colspan="3">
		    {include file="customer/main/subheader.tpl" title="`$lng.lbl_enter_your_shipping_address`:"}
			</td>
		</tr>
		<tr>
			<td colspan="3">{$lng.txt_fields_are_mandatory}</td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>

		<tr>
                        <td align="right" width="200"><b>{$lng.lbl_country}</b></td>
                        <td width="15"><font class="Star">*</font></td>
                        <td nowrap="nowrap" align="left" width="300">
<input type="text" id="s_countryname" name="s_countryname" size="32" maxlength="64" value="{if $userinfo.s_countryname ne ""}{$userinfo.s_countryname}{else}{if $geo_litecity_location.country ne ""}{section name=country_idx loop=$countries}{if $geo_litecity_location.country eq $countries[country_idx].country_code}{$countries[country_idx].country|amp}{/if}{/section}{/if}{/if}" 
onkeyup="cidev_check_country_usa('s_countryname'); cidev_check_field_country('s_countryname'); cidev_check_zip();"  onchange="cidev_check_field_country('s_countryname'); cidev_check_zip();"
autocomplete="off" placeholder="{if $geo_litecity_location.country ne ""}{section name=country_idx loop=$countries}{if $geo_litecity_location.country eq $countries[country_idx].country_code}{$countries[country_idx].country|amp}{/if}{/section}{/if}" />
{if $reg_error ne "" and $userinfo.s_country eq "" and $default_fields.s_country.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}

<input type="hidden" id="s_country" name="s_country" size="32" maxlength="32" value="{$userinfo.s_country}" />
			</td>
		</tr>

                <tr>
                        <td align="right"><b>{$lng.lbl_zip_code}</b></td>
                        <td><font class="Star">*</font></td>
                        <td nowrap="nowrap" align="left" id="cidev_show_zip">
<input type="text" id="s_zipcode" name="s_zipcode" size="32" maxlength="32" value="{if $userinfo.s_zipcode ne ""}{$userinfo.s_zipcode}{else}{if $geo_litecity_location.country ne ""}{$geo_litecity_location.postalCode}{/if}{/if}" onkeyup="cidev_check_field('s_zipcode'); cidev_check_address();" onchange="check_zip_code_ship('s_zipcode', 's_countryname');" autocomplete="off" placeholder="{if $geo_litecity_location.postalCode ne ""}{$geo_litecity_location.postalCode}{else}{$lng.lbl_fill_in_examples_zip}{/if}" />
{if $reg_error ne "" and $userinfo.s_zipcode eq "" and $default_fields.s_zipcode.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
			</td>
		</tr>
		{* --- *}
		<input type="hidden" name="clear_city_in_Change_states_js" id="clear_city_in_Change_states_js" value="Y">
		{* --- *}

		<tr><td colspan="3"><br /></td></tr>

                <tr id="tr_show_text_for_us">
		<td colspan="3" align="center">
If you don't know your zip code then enter state and city below:
		</td>		
                </tr>


                <tr id="tr_s_state">
                        <td align="right"><b>{$lng.lbl_state}</b> <font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font></td>
                        <td>&nbsp;</td>
                        <td nowrap="nowrap" align="left">

<input type="text" id="s_statename" name="s_statename" size="32" maxlength="64" 
value="
{if $userinfo.s_statename ne ""}
{$userinfo.s_statename}
{else}
{if $geo_litecity_location.region ne ""}
{section name=state_idx loop=$states}
{if $geo_litecity_location.country eq $states[state_idx].country_code && $geo_litecity_location.region eq $states[state_idx].state_code}
{$states[state_idx].state|amp}
{/if}
{/section}
{/if}
{/if}
" 
onkeyup="cidev_check_field_country('s_statename'); cidev_check_zip();" 
autocomplete="off" 
placeholder="
{if $geo_litecity_location.region ne ""}
{section name=state_idx loop=$states}
{if $geo_litecity_location.country eq $states[state_idx].country_code && $geo_litecity_location.region eq $states[state_idx].state_code}
{$states[state_idx].state|amp}
{/if}
{/section}
{else}
{$userinfo.s_statename}
{/if}
" />
{if $reg_error ne "" and $userinfo.s_state eq "" and $default_fields.s_state.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}

<input type="hidden" id="s_state" name="s_state" size="32" maxlength="32" value="{$userinfo.s_state}" />
                        </td>
                </tr>

                <tr id="tr_s_city">
                        <td align="right"><b>{$lng.lbl_city}</b> <font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font></td>
                        <td>&nbsp;</td>
                        <td nowrap="nowrap" align="left">
<input type="text" id="s_city" name="s_city" size="32" maxlength="64" value="{if $userinfo.s_city ne ""}{$userinfo.s_city}{else}{if $geo_litecity_location.country ne ""}{$geo_litecity_location.city}{/if}{/if}" 
onkeyup="cidev_check_field('s_city'); cidev_check_zip();" placeholder="{if $geo_litecity_location.city ne ""}{$geo_litecity_location.city}{else}{$lng.lbl_fill_in_examples_city}{/if}" />
{if $reg_error ne "" and $userinfo.s_city eq "" and $default_fields.s_city.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
                        </td>
                </tr>

		<tr id="tr_s_state_s_city_table" style="display: none;">
			{* {include file="customer/main/cidev_shipquote_state_city_values.tpl"} *}
		</tr>

		<tr>
			<td class="ButtonsRow" align="center" colspan="3" nowrap="nowrap">

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
function cidev_sqCALCULATE() {

{/literal}
 var cidev_s_country = '"'+document.getElementById('s_country').value + '"';
 var cidev_s_state = '"'+document.getElementById('s_state').value + '"';
 var cidev_s_city = '"'+document.getElementById('s_city').value + '"';
 var cidev_s_zipcode = '"'+document.getElementById('s_zipcode').value + '"';
{literal}

 var yaGoalParams = {
    Country: {/literal}cidev_s_country{literal},
    State: {/literal}cidev_s_state{literal},
    City: {/literal}cidev_s_city{literal}, 
    ZipCode: {/literal}cidev_s_zipcode{literal} 
 };

 yaCounter{/literal}{$config.Company.cidev_yandex_code_number}{literal}.reachGoal('sqCALCULATE', yaGoalParams);


 _gaq.push(['_trackEvent', 'sqCALCULATE']);

 document.shipquoteform.submit();
}


function cidev_check_zipcode() {


        var countrySelected = cidev_get_country_code("s_countryname");
        $('#s_country').val(countrySelected);

        var state_code = cidev_get_state_code("s_statename", "s_countryname");
        $('#s_state').val(state_code);


	if ($("#s_zipcode").val() == ""){
		alert("Zip/Postal code is mandatory.");
		$("#s_zipcode").focus();
		return false;
	}

	var countrySelected = cidev_get_country_code("s_country");
	if (cidev_new_check_zip_code_field(countrySelected, cidev_id$("s_zipcode"), 's_zipcode')){
		cidev_sqCALCULATE();
		return true;
	}
	
	return false;
}

{/literal}
//]]>
</script>


<br />
{include file="buttons/button.tpl" button_title=$lng.lbl_calculate_shippings type="input" href="javascript: cidev_check_zipcode();" js_to_href="Y" b="1"}
</td>
		</tr>
		</table>
		</td>
	</tr>
	{/if}

	{if $mode eq 'shipping' and $shipping_groups}
	<tr>
		<td align="center">
		<table cellspacing="5px">
		<tr>
			<td style="vertical-align: top;">
			{include file="customer/main/subheader.tpl" title=$lng.lbl_shipping_address}
			{$userinfo.s_countryname}<br />
			{$userinfo.s_statename}<br />
			{$userinfo.s_city}<br />
			{$userinfo.s_zipcode}
			<br /><br />
			{include file="buttons/modify.tpl" href="popup_shipquote.php?update"}
			</td>
			<td width="70%">

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
function cidev_select_shipping(id) {
 {/literal}
 {foreach from=$shipping_groups item=v key=k}
  {foreach from=$shippings[$k] item=s}
  {literal}

   var shippingid = {/literal}{$s.shippingid}{literal};

   if (shippingid == id){
    var set_shipping = "{/literal}{$s.shipping|trademark:$insert_trademark}{literal} - {/literal}{$s.shipping_time}{literal}: ${/literal}{$s.rate}{literal}";
    document.getElementById('cidev_shipping').value = set_shipping;
   }

  {/literal}
  {/foreach}
 {/foreach}
 {literal}
}
{/literal}
-->
</script>


			{foreach from=$shipping_groups item=v key=k}
			{assign var="found_any_shipping" value="N"}
			{assign var="selected_any" value="N"}
			{cycle values=''}
{* 			{assign var=delivery_text value=$lng.txt_for_fastlane_checkout_delivery|replace:"XX":"`$v.m_city`, `$v.m_state`, `$v.m_country`."|replace:"YY":"`$v.group_name`"} *}

{if $v.m_country_code eq "US"}
{assign var="m_country_code" value="USA"}
{else}
{assign var="m_country_code" value=$v.m_country}
{/if}

{assign var=delivery_text value=$lng.txt_for_fastlane_checkout_delivery|replace:"XX":"`$v.m_city`, `$v.m_state_code`, `$m_country_code`"|replace:"YY":""}

			{include file="customer/main/subheader.tpl" title="`$lng.lbl_delivery_methods` `$delivery_text`"}

		        {assign var='Maxshippings' value="0"}
		        {foreach from=$shippings[$k] item=s}
                		{math equation="$Maxshippings+1" assign="Maxshippings"}
		        {/foreach}

			{foreach from=$shippings[$k] item=s}
			{if $s.active eq "Y" && $s.allowed eq "1"}
			{assign var="found_any_shipping" value="Y"}
			<table cellpadding="1" cellspacing="0" width="100%" {cycle values=" class='TableSubHead', "}>
			<tr>
          {if $Maxshippings eq "1"}
                <input type="hidden" id="shippingid{$s.shippingid}" name="shippingids[{$k}]" value="{$s.shippingid}">
          {else}

				<td width="5"><input type="radio" id="shippingid{$s.shippingid}" name="shippingids[{$k}]" value="{$s.shippingid}"{if $s.shippingid eq $shippingids[$k].shippingid || ($shippingids[$k] eq "" && $selected_any eq "N")}{assign var="selected_any" value="Y"}    

{assign var="cidev_shipping1" value=$s.shipping|trademark:"`$insert_trademark`"}
{if $s.shipping_time ne ""} 
{assign var="cidev_shipping" value="`$cidev_shipping1` - `$s.shipping_time`: $`$s.rate`"}
{/if}


			            checked="checked"{/if}{if $allow_cod} onclick="javascript: cidev_select_shipping('{$s.shippingid}'); display_cod({if $s.is_cod eq 'Y'}true{else}false{/if});"{else} onclick="javascript: cidev_select_shipping('{$s.shippingid}');"{/if} /></td>
	  {/if}
				<td>

                        {if $s.shipping eq "_USE_MY_UPS_FEDEX_ACCOUNT_"}
<table cellspacing="0" cellpadding="0">
<tr>
<td nowrap="nowrap">
<label for="shippingid{$s.shippingid}">
Use my
</label>
</td>
<td>
<select id="use_my_account_{$k}" name="use_my_account[{$k}]" onchange="javascript: cidev_save_use_my_account('{$k}');">
<option value="UPS" {if $cart.use_my_account[$k] eq "UPS"}selected="selected"{/if}>UPS account</option>
<option value="FedEx" {if $cart.use_my_account[$k] eq "FedEx"}selected="selected"{/if}>FedEx account</option>
</select>
</td>
<td nowrap="nowrap">
<input type="text" id="use_my_account_number_{$k}" name="use_my_account_number[{$k}]" value="{$cart.use_my_account_number[$k]}" size="10" placeholder="{$lng.lbl_use_my_account_number}">
</td>
<td nowrap="nowrap">
<label for="shippingid{$s.shippingid}">and ship by</label>
</td>
<td>
<input type="text" id="ship_by_shipping_method_{$k}" name="ship_by_shipping_method[{$k}]" value="{$cart.ship_by_shipping_method[$k]}" placeholder="{$lng.lbl_ship_by_shipping_method}">:
</td>
</tr>

<tr>
<td colspan="2"></td>
<td align="center"><label for="shippingid{$s.shippingid}"><div class="cidev_checkout_descr" style="float: left;">{$lng.lbl_use_my_account_number_under}</div><div style="float: left; color: #FF0000; margin-top: -3px; margin-left: 2px;">*</div></label></td>
<td></td>
<td align="center"><label for="shippingid{$s.shippingid}"><div class="cidev_checkout_descr" style="float: left;">{$lng.lbl_ship_by_shipping_method_under}</div><div style="float: left; color: #FF0000; margin-top: -3px; margin-left: 2px;">*</div></label></td>
</tr>

<tr>
<td colspan="5">
<label for="shippingid{$s.shippingid}">$5.00 handling fee will apply (the fields marked with <font class="Star"><b>*</b></font> are mandatory)</label>
</td>
</tr>
</table>

                        {elseif $s.shipping eq "_SHIP_BY_FASTEST_METHOD_"}
                                <label for="shippingid{$s.shippingid}">
                                        Ship by the fastest possible shipping method upon your discretion and add shipping charge to my order's total
                                </label>
                        {else}
<label for="shippingid{$s.shippingid}">{$s.shipping|trademark:$insert_trademark}{if $s.shipping_time ne ""} - {$s.shipping_time}{/if}{if $config.Appearance.display_shipping_cost eq "Y"}: {include file="currency.tpl" value=$s.rate}{/if}</label>
			{/if}

				</td>
			</tr>
			</table>
			{/if}
			{/foreach}
			{if $found_any_shipping ne "Y" and $need_shipping}
			<font class="ErrorMessage">{$lng.lbl_no_shipping_for_location}</font><br />
			<br />
			{/if}
			<br />
			{/foreach}
			<td>
		</tr>
		</table>
		</td>
	</tr>
	
	<tr>
		<td class="ButtonsRow" align="center" nowrap="nowrap">

<input type="hidden" name="cidev_shipping" id="cidev_shipping" value="{$cidev_shipping}">

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
function cidev_sqGRAND_TOTAL() {

{/literal}
 var cidev_Delivery = '"'+document.getElementById('cidev_shipping').value + '"';
{literal}

var yaGoalParams = {
    s3Param: "GRANDTOTAL",
    Country: "{/literal}{$userinfo.s_country}{literal}",
    State: "{/literal}{$userinfo.s_statename}{literal}",
    City: "{/literal}{$userinfo.s_city}{literal}",
    ZipCode: "{/literal}{$userinfo.s_zipcode}{literal}", 
    Delivery: {/literal}cidev_Delivery{literal},
goods: 
[ 
{/literal}
{assign var="tmp_counter" value=0}
{foreach from=$cart.products item=item key=key}
{math assign="tmp_counter" equation="x+1" x=$tmp_counter}
{literal}
{
id:"{/literal}{$item.productcode}{literal}", 
name:"{/literal}{$item.product|escape}{literal}",
price: {/literal}{$item.price}{literal},
quantity: {/literal}{$item.amount}{literal}
}
{/literal}
{if $tmp_counter ne $count_products}{literal},{/literal}{/if}
{/foreach}
{literal}
] 
 };

 yaCounter{/literal}{$config.Company.cidev_yandex_code_number}{literal}.reachGoal('sqGRAND_TOTAL', yaGoalParams);

 _gaq.push(['_trackEvent', 'sqGRAND_TOTAL']);

 document.shipquoteform.submit();
}
{/literal}
-->
</script>

{include file="buttons/button.tpl" button_title=$lng.lbl_calculate_grandtotal type="input" href="javascript: cidev_sqGRAND_TOTAL();" js_to_href="Y" b="1"}

{*
{include file="buttons/button.tpl" button_title=$lng.lbl_calculate_grandtotal type="input" href="javascript: yaCounter`$config.Company.cidev_yandex_code_number`.reachGoal('sqGRAND_TOTAL'); document.shipquoteform.submit()" js_to_href="Y" b="1"}
*}
		</td>
	</tr>
	{/if}

	{if $mode eq 'grandtotal'}
	<tr>
		<td style="vertical-align: top;" colspan="2">
		{include file="customer/main/subheader.tpl" title=$lng.lbl_shipping_address}
<table>
<tr>
		<td align="right"><b>{$lng.lbl_country}:</b></td><td align="left">{$userinfo.s_countryname}</td>
</tr>
<tr>
		<td align="right"><b>{$lng.lbl_state}:</b></td><td align="left">{$userinfo.s_statename}</td>
</tr>
<tr>
		<td align="right"><b>{$lng.lbl_city}:</b></td><td align="left">{$userinfo.s_city}</td>
</tr>
<tr>
		<td align="right"><b>{$lng.lbl_zip_code}:</b></td><td align="left">{$userinfo.s_zipcode}</td>
</tr>
</table>
		</td>
	</tr>

	<tr>
		<td colspan="2">
		{if $config.Appearance.show_cart_details eq "Y" or $config.Appearance.show_cart_details eq "L"}
		{include file="customer/main/cart_details.tpl" link_qty="Y"}
		{else}
		{include file="customer/main/cart_contents.tpl" link_qty="Y"}
		{/if}
		{include file="customer/main/cart_totals.tpl" link_shipping="Y" no_form_fields=true}
		<br /><br />
		</td>
	</tr>

	<tr>
		<td class="ButtonsRow" align="left" nowrap="nowrap">


<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
function cidev_sqCLOSE(param) {
{/literal}

var tmp_param = "'"+param+"'";

{foreach from=$cart.shipping_groups item=v key=k}
{if $cart.groups_delivery[$k] ne ''}
var cidev_Delivery = '"{$cart.groups_delivery[$k]|trademark:$insert_trademark} ${$cart.display_shipping_costs[$k]|formatprice}"';
{/if}
{/foreach}
{literal}

var yaGoalParams = {
    s3Param: "SHIPPING QUOTE PAGE QUIT",
    Country: "{/literal}{$userinfo.s_country}{literal}",
    State: "{/literal}{$userinfo.s_statename}{literal}",
    City: "{/literal}{$userinfo.s_city}{literal}",
    ZipCode: "{/literal}{$userinfo.s_zipcode}{literal}", 
    Delivery: {/literal}cidev_Delivery{literal},
goods: 
[ 
{/literal}
{assign var="tmp_counter" value=0}
{foreach from=$cart.products item=item key=key}
{math assign="tmp_counter" equation="x+1" x=$tmp_counter}
{literal}
{
id:"{/literal}{$item.productcode}{literal}", 
name:"{/literal}{$item.product|escape}{literal}",
price: {/literal}{$item.price}{literal},
quantity: {/literal}{$item.amount}{literal}
}
{/literal}
{if $tmp_counter ne $count_products}{literal},{/literal}{/if}
{/foreach}
{literal}
] 
 };

 yaCounter{/literal}{$config.Company.cidev_yandex_code_number}{literal}.reachGoal(tmp_param, yaGoalParams);

 _gaq.push(['_trackEvent', tmp_param]);

}
{/literal}
-->
</script>


			{if $short eq 'Y'}

				{include file="buttons/button.tpl" button_title=$lng.lbl_shipquote_close_short type="input" href="javascript: cidev_sqCLOSE('sqCLOSE_THIS_WINDOW'); window.close() " js_to_href="Y"}

{*
				{include file="buttons/button.tpl" button_title=$lng.lbl_shipquote_close_short type="input" href="javascript: yaCounter`$config.Company.cidev_yandex_code_number`.reachGoal('sqCLOSE_THIS_WINDOW'); window.close()" js_to_href="Y"}
*}
			{else}
				{include file="buttons/button.tpl" button_title=$lng.lbl_shipquote_close type="input" href="javascript: cidev_sqCLOSE('sqRETURN_TO_CART'); window.close() " js_to_href="Y"}
{*
				{include file="buttons/button.tpl" button_title=$lng.lbl_shipquote_close type="input" href="javascript: yaCounter`$config.Company.cidev_yandex_code_number`.reachGoal('sqRETURN_TO_CART'); window.close()" js_to_href="Y"}
*}
			{/if}
		</td>
		<td class="ButtonsRow" align="right" nowrap="nowrap">

{include file="buttons/button.tpl" button_title=$lng.lbl_shipquote_proceed type="input" href="javascript: cidev_sqCLOSE('sqPROCEED_WITH_ORDER'); document.shipquoteform.submit()" js_to_href="Y" b="1"}

{*
{include file="buttons/button.tpl" button_title=$lng.lbl_shipquote_proceed type="input" href="javascript: yaCounter`$config.Company.cidev_yandex_code_number`.reachGoal('sqPROCEED_WITH_ORDER'); document.shipquoteform.submit()" js_to_href="Y" b="1"}
*}
		</td>
	</tr>

	{/if}


	</table>

	</td>
</tr>
{*
<tr>
	<td valign="bottom">{include file="popup_bottom.tpl"}</td>
</tr>
*}
</table>
</form>

{if $config.Company.cidev_google_adwords ne ""}
{assign var="ecomm_prodid_replacement" value="ecomm_prodid: ''"}
{assign var="ecomm_pagetype_replacement" value="ecomm_pagetype: 'siteview'"}
{assign var="ecomm_totalvalue_replacement" value="ecomm_totalvalue: ''"}

        {$config.Company.cidev_google_adwords|replace:"ecomm_prodid: ''":"`$ecomm_prodid_replacement`"|replace:"ecomm_pagetype: 'siteview'":"`$ecomm_pagetype_replacement`"|replace:"ecomm_totalvalue: ''":"`$ecomm_totalvalue_replacement`"}
{/if}

</body>
</html>
