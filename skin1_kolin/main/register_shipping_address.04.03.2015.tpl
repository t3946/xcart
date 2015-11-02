{* $Id: register_shipping_address.tpl,v 1.38.2.7 2007/01/16 06:42:49 max Exp $ *}

{if $usertype ne "P" && $usertype ne "A"}

<script type="text/javascript">
//<![CDATA[
{literal}

$(function(){
  $("#s_firstname").focusout(function(event){

	if (document.forms["registerform"].s_firstname.value != "" && document.forms["registerform"].firstname){
        	document.forms["registerform"].firstname.value = document.forms["registerform"].s_firstname.value;

                document.getElementById("firstname_verified").style.display = '';                      
                document.getElementById("firstname_error").style.display = 'none';  
	}

	event.preventDefault();
  });
});

{/literal}
//]]>
</script>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
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
//		zipcode = $.trim(zipcode);
		zipcode = ltrim(zipcode);

		$('#'+zipcode_id).val(zipcode);

		var countrySelected = cidev_get_country_code(countryname_id);

                return cidev_new_check_zip_code_field(countrySelected, cidev_id$(zipcode_id), zipcode_id);
        }

        function cidev_check_zip(){

                var s_city_in_registerform = document.forms["registerform"].s_city.value;
                var s_state_in_registerform = cidev_get_state_code("s_statename", "s_countryname");
//                var s_zipcode_in_registerform_length = document.forms["registerform"].s_zipcode.value.length;
                var s_zipcode_in_registerform = document.forms["registerform"].s_zipcode.value;

                var s_country_in_registerform = cidev_get_country_code("s_countryname");
                if (s_country_in_registerform == "US"){
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_zip_reg_form&s_city_in_registerform=' + s_city_in_registerform + '&s_state_in_registerform=' + s_state_in_registerform + '&s_zipcode_in_registerform=' + s_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_show_zip").innerHTML=cidev_xmlHttp.responseText;


							if (cidev_id$("s_zip_show_text")){
								document.forms["registerform"].s_zipcode.value = cidev_id$("s_zip_show_text").value;

						                if (cidev_id$("s_zip_show_text").value != ""){
						                        document.getElementById("s_zipcode_verified").style.display = '';                      
						                        document.getElementById("s_zipcode_error").style.display = 'none';     
						                        document.getElementById("s_zipcode_error_text").style.display = 'none';     
						                        document.getElementById("s_zipcode_error_text_div").innerHTML='';     
					        	        }
					                	else {
					                        	document.getElementById("s_zipcode_verified").style.display = 'none';                      
						                        document.getElementById("s_zipcode_error").style.display = '';  
						                }
							}

							cidev_check_verified_image_for_field("s_city");

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
                var s_country_in_registerform = cidev_get_country_code("s_countryname");


                if (s_country_in_registerform == "US"){
			document.forms["registerform"].s_zipcode.value = document.forms["registerform"].s_zipcode.value.replace(/[^\w]/g, "");

                        if (document.forms["registerform"].s_zipcode.value.length == "5"){
                                document.getElementById("s_zipcode_error_text").style.display = 'none';
                        }

                        cidev_show_state_city();
                } else {
			document.forms["registerform"].s_zipcode.value = document.forms["registerform"].s_zipcode.value.replace(/[^\w\s]/g, "");
		}

                if (s_country_in_registerform == "CA"){
                        if (document.forms["registerform"].s_zipcode.value.length == "6"){
                                document.getElementById("s_zipcode_error_text").style.display = 'none';
                        }
		}
        }

        function cidev_show_state_city(){
			document.forms["registerform"].s_zipcode.value = ltrim(document.forms["registerform"].s_zipcode.value);
                        var s_zipcode_in_registerform = document.forms["registerform"].s_zipcode.value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_state_city_reg_form&s_zipcode_in_registerform=' + s_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_state_city_values").innerHTML=cidev_xmlHttp.responseText;

							if (cidev_id$("s_state_show_text")){
	
                	                                        document.forms["registerform"].s_statename.value = cidev_id$("s_state_show_text").value;

                                                                if (cidev_id$("s_state_show_text").value != ""){
                                                                        document.getElementById("s_statename_verified").style.display = '';                        
                                                                        document.getElementById("s_statename_error").style.display = 'none';       
                                                                }
                                                                else {
                                                                        document.getElementById("s_statename_verified").style.display = 'none';                        
                                                                        document.getElementById("s_statename_error").style.display = '';    
                                                                }
							}

							if (cidev_id$("s_city_show_text")){
								document.forms["registerform"].s_city.value = cidev_id$("s_city_show_text").value;

						                if (cidev_id$("s_city_show_text").value != ""){
						                        document.getElementById("s_city_verified").style.display = '';                         
						                        document.getElementById("s_city_error").style.display = 'none';        
					        	        }
					                	else {
					                        	document.getElementById("s_city_verified").style.display = 'none';                         
						                        document.getElementById("s_city_error").style.display = '';  
						                }
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

	function cidev_check_verified_image_for_field(field_id){

                if ($('#'+field_id).val() != ""){
			if (document.getElementById(field_id+"_verified") && document.getElementById(field_id+"_error")){
	                        document.getElementById(field_id+"_verified").style.display = '';                      
        	                document.getElementById(field_id+"_error").style.display = 'none';     
			}
                }
                else {
			if (document.getElementById(field_id+"_verified") && document.getElementById(field_id+"_error")){
	                        document.getElementById(field_id+"_verified").style.display = 'none';                      
        	                document.getElementById(field_id+"_error").style.display = '';  
			}
                }
	}

  $(document).ready(function() {  

	$('#s_firstname').focusout(function() {
		cidev_check_verified_image_for_field("s_firstname");
	});

        $('#s_address').focusout(function() {
		cidev_check_verified_image_for_field('s_address');
        });

	$('#s_address_2').focusout(function() {
                if ($('#s_address_2').val() != ""){
                        if (document.getElementById("s_address_2") && document.getElementById("s_address_2_error")){
                                document.getElementById("s_address_2_verified").style.display = '';                      
                                document.getElementById("s_address_2_error").style.display = 'none';     
                        }
                }
                else {
                        if (document.getElementById("s_address_2_verified") && document.getElementById("s_address_2_error")){
                                document.getElementById("s_address_2_verified").style.display = 'none';                      
                                document.getElementById("s_address_2_error").style.display = 'none';  
                        }
                }
	});

        $('#s_zipcode').focusout(function() {
		cidev_check_verified_image_for_field('s_zipcode');
		onSelectChange();
        });

        $('#s_city').focusout(function() {
                cidev_check_verified_image_for_field('s_statename');
                cidev_check_verified_image_for_field('s_zipcode');
		cidev_check_verified_image_for_field('s_city');
        });

        $('#s_city').change(function() {
                cidev_check_verified_image_for_field('s_statename');
                cidev_check_verified_image_for_field('s_zipcode');
                cidev_check_verified_image_for_field('s_city');
        });

        $('#s_statename').change(function() {
                cidev_check_verified_image_for_field('s_statename');
                cidev_check_verified_image_for_field('s_zipcode');
	});

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

		cidev_check_verified_image_for_field('s_countryname');

		if ($('#s_zipcode').val() != ""){
                        document.getElementById("s_zipcode_error_text").style.display = 'none';     
                        document.getElementById("s_zipcode_error_text_div").innerHTML=''; 
		}

        });

        $('#s_statename').focusout(function() {
                onSelectChange();

		cidev_check_verified_image_for_field('s_statename');
		cidev_check_verified_image_for_field('s_zipcode');

        });

	function start() {
		cidev_load_countries();
		cidev_load_states();
        	onSelectChange();
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

{else}

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

{/if}

{if $is_areas.S eq 'Y'}
{if $hide_header eq ""}
<tr>
<td colspan="3" class="RegSectionTitle">{$lng.lbl_shipping_address}<hr size="1" noshade="noshade" /></td>
</tr>
{/if}

<tr>
<td colspan="3">{$lng.txt_fields_are_mandatory}

{* --- *}
<div id="cidev_reg_form_state_city_values">
{include file="main/cidev_reg_form_state_city_values.tpl"}
</div>

<div id="cidev_reg_form_show_zip">
{include file="main/cidev_reg_form_show_zip.tpl"}
</div>

<input type="hidden" name="clear_city_in_Change_states_js" id="clear_city_in_Change_states_js" value="Y">
{* --- *}

</td>
</tr>

{if $default_fields.s_title.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_title}</td>
<td valign="top">{if $default_fields.s_title.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap"> 
<select name="s_title" id="s_title">
{include file="main/title_selector.tpl" field=$userinfo.s_titleid}
</select> 
</td> 
</tr> 
 {/if}

{if $default_fields.s_firstname.avail eq 'Y'}
<tr>
<td valign="top" align="right" width="49%" class="cidev_padding_top">{$lng.lbl_first_name}
{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_firstname}</div>{/if}
</td>
<td valign="top" width="5">{if $default_fields.s_firstname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap" {if $usertype eq "C"}width="*"{/if}> 

<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_firstname" name="s_firstname" size="32" maxlength="32" value="{$userinfo.s_firstname|replace:"&amp;#039;":"'"}" placeholder="{$lng.lbl_fill_in_examples_firstname}" onkeyup="cidev_check_field_name('s_firstname')" />
</td>

{if $usertype eq "C"}
<td id="s_firstname_verified" valign="top" nowrap="nowrap" {if $userinfo.s_firstname eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_firstname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.s_firstname eq "" && $default_fields.s_firstname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
 {/if}

{if $default_fields.s_lastname.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_last_name}</td>
<td valign="top">{if $default_fields.s_lastname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_lastname" name="s_lastname" size="32" maxlength="32" value="{$userinfo.s_lastname|replace:"&amp;#039;":"'"}" onkeyup="cidev_check_field_name('s_lastname')" />
</td>
{if $usertype eq "C"}
<td id="s_lastname_verified" valign="top" nowrap="nowrap" {if $userinfo.s_lastname eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_lastname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.s_lastname eq "" && $default_fields.s_lastname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{include file="main/register_additional_info.tpl" section="S"}

{if $default_fields.s_address.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_address}
{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_address}</div>{/if}
</td>
<td valign="top">{if $default_fields.s_address.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_address" name="s_address" size="32" maxlength="64" value="{$userinfo.s_address}" placeholder="{$lng.lbl_fill_in_examples_address}" onkeyup="cidev_check_field_address('s_address')" />
</td>

{if $usertype eq "C"}
<td id="s_address_verified" valign="top" nowrap="nowrap" {if $userinfo.s_address eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_address_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.s_address eq "" and $default_fields.s_address.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_address_2.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_address_2} {if $default_fields.s_address_2.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font>{/if}
{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_address2}</div>{/if}</td>
<td valign="top">{if $default_fields.s_address_2.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap" valign="top">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_address_2" name="s_address_2" size="32" maxlength="64" value="{$userinfo.s_address_2}" placeholder="{$lng.lbl_fill_in_examples_address2}" onkeyup="cidev_check_field_address('s_address_2')" />
</td>

{if $usertype eq "C"}
<td id="s_address_2_verified" valign="top" nowrap="nowrap" {if $userinfo.s_address_2 eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_address_2_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.s_address_2 eq "" and $default_fields.s_address_2.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_county.avail eq 'Y' and $config.General.use_counties eq "Y"}
<tr>
<td valign="top" align="right">{$lng.lbl_county}</td>
<td valign="top">{if $default_fields.s_county.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
{include file="main/counties.tpl" counties=$counties name="s_county" default=$userinfo.s_county country_name="s_country"}
{if ($reg_error ne "" and $userinfo.s_county eq "" and $default_fields.s_county.required eq 'Y') or $error eq "s_county"}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}


{if $default_fields.s_country.avail eq 'Y'}

{if $usertype ne "P" && $usertype ne "A"}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_country}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_country ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_country}</div>{/if}
</td>
<td valign="top">{if $default_fields.s_country.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_countryname" name="s_countryname" size="32" maxlength="64" value="{if $geo_litecity_location.country ne ""}{section name=country_idx loop=$countries}{if $geo_litecity_location.country eq $countries[country_idx].country_code}{if $countries[country_idx].country ne ""}{$countries[country_idx].country|amp}{assign var="cidev_is_country" value="Y"}{/if}{/if}{/section}{else}{if $userinfo.s_countryname ne ""}{$userinfo.s_countryname}{assign var="cidev_is_country" value="Y"}{/if}{/if}" 
onkeyup="cidev_check_country_usa('s_countryname'); cidev_check_field_country('s_countryname'); cidev_check_zip();"  onchange="cidev_check_field_country('s_countryname'); cidev_check_zip();"
autocomplete="off" placeholder="{if $geo_litecity_location.country ne ""}{section name=country_idx loop=$countries}{if $geo_litecity_location.country eq $countries[country_idx].country_code}{$countries[country_idx].country|amp}{/if}{/section}{/if}" />
</td>

{if $usertype eq "C"}
<td id="s_countryname_verified" valign="top" nowrap="nowrap" {if $cidev_is_country ne "Y"}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_countryname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.s_country eq "" and $default_fields.s_country.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}


<input type="hidden" id="s_country" name="s_country" size="32" maxlength="32" value="{$userinfo.s_country}" />


</td>
</tr>
{/if}


{if $usertype eq "P" || $usertype eq "A"}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_country}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_country ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_country}</div>{/if}
</td>
<td valign="top">{if $default_fields.s_country.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<select name="s_country" id="s_country" size="1" onchange="check_zip_code();"
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}" {if $geo_litecity_location.country ne ""}{if $geo_litecity_location.country eq $countries[country_idx].country_code} selected="selected"{/if}{else}{if $userinfo.s_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $userinfo.s_country eq ""} selected="selected"{/if}{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
{if $reg_error ne "" and $userinfo.s_country eq "" and $default_fields.s_country.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{/if}



{if $default_fields.s_zipcode.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_zip_code}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_zipcode ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_zipcode}</div>{/if}
</td>
<td valign="top">{if $default_fields.s_zipcode.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_zipcode" name="s_zipcode" size="32" maxlength="32" value="{if $geo_litecity_location.country ne "" && $geo_litecity_location.country eq "US"}{$geo_litecity_location.postalCode}{else}{$userinfo.s_zipcode}{/if}" {if $usertype ne "P" && $usertype ne "A"} onkeyup="cidev_check_field('s_zipcode'); cidev_check_address();" onchange="cidev_new_check_zip_code(); check_zip_code_ship('s_zipcode', 's_countryname');" {/if} autocomplete="off" placeholder="{if $geo_litecity_location.postalCode ne ""}{$geo_litecity_location.postalCode}{else}{$lng.lbl_fill_in_examples_zip}{/if}" />
</td>
{if $usertype eq "C"}
<td id="s_zipcode_verified" valign="top" nowrap="nowrap" {if $geo_litecity_location.postalCode eq "" && $userinfo.s_zipcode eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_zipcode_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>

<td id="s_zipcode_error_text" valign="top" style="display: none;">
<div class="cidev_NoteBox" id="s_zipcode_error_text_div"></div>
</td>

{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.s_zipcode eq "" and $default_fields.s_zipcode.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}


{if $default_fields.s_state.avail eq 'Y'}

{if $usertype ne "P" && $usertype ne "A"}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_state}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_state ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_state}</div>{/if}
</td>
<td valign="top">{if $default_fields.s_state.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_statename" name="s_statename" size="32" maxlength="64" 
value="
{if $geo_litecity_location.region ne ""}
{section name=state_idx loop=$states}
{if $geo_litecity_location.country eq $states[state_idx].country_code && $geo_litecity_location.region eq $states[state_idx].state_code}
{if $states[state_idx].state ne ""}{$states[state_idx].state|amp}{assign var="cidev_is_state" value="Y"}{/if}
{/if}
{/section}
{else}
{if $userinfo.s_statename ne ""}{$userinfo.s_statename}{assign var="cidev_is_state" value="Y"}{/if}
{/if}
" 
onkeyup="cidev_check_field_country('s_statename'); cidev_check_zip(); cidev_check_verified_image_for_field('s_zipcode');" 
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
</td>
{if $usertype eq "C"}
<td id="s_statename_verified" valign="top" nowrap="nowrap" {if $cidev_is_state ne "Y"}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_statename_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.s_state eq "" and $default_fields.s_state.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}


<input type="hidden" id="s_state" name="s_state" size="32" maxlength="32" value="{$userinfo.s_state}" />

</td>
</tr>
{/if}



{if $usertype eq "P" || $usertype eq "A"}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_state}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_state ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_state}</div>{/if}
</td>
<td valign="top">{if $default_fields.s_state.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
{include file="main/states.tpl" states=$states name="s_state" default=$userinfo.s_state default_country=$userinfo.s_country|default:$config.General.default_country country_name="s_country"}
{if ($reg_error ne "" and $userinfo.s_state eq "" and $default_fields.s_state.required eq 'Y') or $error eq "s_statecode"}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_state.avail eq 'Y' && $default_fields.s_country.avail eq 'Y' && $js_enabled eq 'Y' && $config.General.use_js_states eq 'Y'}
<tr style="display: none;">
	<td valign="top">
{include file="main/register_states.tpl" state_name="s_state" country_name="s_country" county_name="s_county" state_value=$userinfo.s_state county_value=$userinfo.s_county}
	</td>
</tr>
{/if}
{/if}



{if $default_fields.s_city.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_city}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_city ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_city}</div>{/if}
</td>
<td valign="top">{if $default_fields.s_city.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_city" name="s_city" size="32" maxlength="64" value="{if $geo_litecity_location.country ne ""}{$geo_litecity_location.city}{else}{$userinfo.s_city}{/if}" {if $usertype ne "P" && $usertype ne "A"} onkeyup="cidev_check_field('s_city'); cidev_check_zip(); cidev_check_verified_image_for_field('s_zipcode');" {/if} placeholder="{if $geo_litecity_location.city ne ""}{$geo_litecity_location.city}{else}{$lng.lbl_fill_in_examples_city}{/if}" />
</td>
{if $usertype eq "C"}
<td id="s_city_verified" valign="top" nowrap="nowrap" {if $geo_litecity_location.city eq "" && $userinfo.s_city eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_city_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.s_city eq "" and $default_fields.s_city.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{/if}
