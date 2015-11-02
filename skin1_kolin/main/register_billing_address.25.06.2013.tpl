{* $Id: register_billing_address.tpl,v 1.27.2.2 2006/10/25 06:39:34 max Exp $ *}

{if $usertype ne "P" && $usertype ne "A"}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

        function cidev_check_zip_b(){

                var b_city_in_registerform = document.forms["registerform"].b_city.value;
                var b_state_in_registerform = cidev_get_state_code("b_statename", "b_countryname");
                var b_zipcode_in_registerform_length = document.forms["registerform"].b_zipcode.value.length;

                var b_country_in_registerform = cidev_get_country_code("b_countryname");
                if (b_country_in_registerform == "US"){
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_zip_reg_form_b&b_city_in_registerform=' + b_city_in_registerform + '&b_state_in_registerform=' + b_state_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_show_zip_b").innerHTML=cidev_xmlHttp.responseText;

                                                        document.forms["registerform"].b_zipcode.value = cidev_id$("b_zip_show_text").value;

                                                        if (cidev_id$("b_zip_show_text").value != ""){
                                                                document.getElementById("b_zipcode_verified").style.display = '';                      
                                                                document.getElementById("b_zipcode_error").style.display = 'none';    
                                                                document.getElementById("b_zipcode_error_text").style.display = 'none';     
                                                                document.getElementById("b_zipcode_error_text_div").innerHTML='';   
                                                        }
                                                        else {
                                                                document.getElementById("b_zipcode_verified").style.display = 'none';                      
                                                                document.getElementById("b_zipcode_error").style.display = '';  
                                                        }

							cidev_check_verified_image_for_field("b_city");
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
                                setTimeout('cidev_check_zip_b()', 1000);
                        }
                }
        }

        function cidev_check_address_b() {
                var b_country_in_registerform = cidev_get_country_code("b_countryname");

                document.forms["registerform"].b_zipcode.value = document.forms["registerform"].b_zipcode.value.replace(/[^\w]/g, "");

                if (b_country_in_registerform == "US"){

                        if (document.forms["registerform"].b_zipcode.value.length == "5"){
                                document.getElementById("b_zipcode_error_text").style.display = 'none';
                        }

                        cidev_show_state_city_b();
                }

                if (b_country_in_registerform == "CA"){
                        if (document.forms["registerform"].b_zipcode.value.length == "6"){
                                document.getElementById("b_zipcode_error_text").style.display = 'none';
                        }
                }
        }

        function cidev_show_state_city_b(){
			document.forms["registerform"].b_zipcode.value = ltrim(document.forms["registerform"].b_zipcode.value);
                        var b_zipcode_in_registerform = document.forms["registerform"].b_zipcode.value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_state_city_reg_form_b&b_zipcode_in_registerform=' + b_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_state_city_values_b").innerHTML=cidev_xmlHttp.responseText;

//                                                        document.forms["registerform"].b_state.value = cidev_id$("b_state_show_text").value;
//                                                        document.forms["registerform"].b_city.value = cidev_id$("b_city_show_text").value;

                                                        document.forms["registerform"].b_statename.value = cidev_id$("b_state_show_text").value;
                                                        document.forms["registerform"].b_city.value = cidev_id$("b_city_show_text").value;

                                                        if (cidev_id$("b_state_show_text").value != ""){
                                                                document.getElementById("b_statename_verified").style.display = '';                        
                                                                document.getElementById("b_statename_error").style.display = 'none';       
                                                        }
                                                        else {
                                                                document.getElementById("b_statename_verified").style.display = 'none';                        
                                                                document.getElementById("b_statename_error").style.display = '';    
                                                        }

                                                        if (cidev_id$("b_city_show_text").value != ""){
                                                                document.getElementById("b_city_verified").style.display = '';                         
                                                                document.getElementById("b_city_error").style.display = 'none';        
                                                        }
                                                        else {
                                                                document.getElementById("b_city_verified").style.display = 'none';                         
                                                                document.getElementById("b_city_error").style.display = '';  
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
                                setTimeout('cidev_show_state_city_b()', 1000);
                        }
        }

        function onSelectChange_b() {
                var cityFilePath = '';
                var stateSelected = cidev_get_state_code("b_statename", "b_countryname");

                $('#b_city').unautocomplete();

                var countrySelected = cidev_get_country_code("b_countryname"); 
        
                if (countrySelected == "US"){

                        cityFilePath = "skin1_kolin/US_City_List/" +stateSelected.toLowerCase()+".js";

                        $.getScript(cityFilePath, function() {

                                $('#b_city').autocomplete(city, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
                }
        };

        function cidev_load_countries_b() {
                var countryFilePath = "skin1_kolin/US_City_List/all_countries.js";
                
                        $.getScript(countryFilePath, function() {

                                $('#b_countryname').autocomplete(country_names, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
        }

        function cidev_load_states_b() {

                var stateFilePath = "";
                var country_code = cidev_get_country_code("b_countryname");
        
                if (country_code == "US"){
                        stateFilePath = "skin1_kolin/US_City_List/us_states.js";
                }
                if (country_code == "CA"){
                        stateFilePath = "skin1_kolin/US_City_List/ca_states.js";
                }
                
                $('#b_statename').unautocomplete();

                if (country_code == "US" || country_code == "CA"){
                        $.getScript(stateFilePath, function() {

                                $('#b_statename').autocomplete(state_names, {
                                        autoFill: false,
                                        cacheLength: 1
                                });
                        });
                }
        }


  $(document).ready(function() {  

        $('#b_firstname').focusout(function() {
		cidev_check_verified_image_for_field("b_firstname");
        });

        $('#b_address').focusout(function() {
		cidev_check_verified_image_for_field('b_address');
        });

        $('#b_address_2').focusout(function() {
                if ($('#b_address_2').val() != ""){
                        if (document.getElementById("b_address_2") && document.getElementById("b_address_2_error")){
                                document.getElementById("b_address_2_verified").style.display = '';                      
                                document.getElementById("b_address_2_error").style.display = 'none';     
                        }
                }
                else {
                        if (document.getElementById("b_address_2_verified") && document.getElementById("b_address_2_error")){
                                document.getElementById("b_address_2_verified").style.display = 'none';                      
                                document.getElementById("b_address_2_error").style.display = 'none';  
                        }
                }
        });

        $('#b_zipcode').focusout(function() {
		cidev_check_verified_image_for_field('b_zipcode');
        });

        $('#b_city').focusout(function() {
                cidev_check_verified_image_for_field('b_statename');
                cidev_check_verified_image_for_field('b_zipcode');
                cidev_check_verified_image_for_field('b_city');
        });

        $('#b_city').change(function() {
                cidev_check_verified_image_for_field('b_statename');
                cidev_check_verified_image_for_field('b_zipcode');
                cidev_check_verified_image_for_field('b_city');
        });

        $('#b_statename').change(function() {
                cidev_check_verified_image_for_field('b_statename');
                cidev_check_verified_image_for_field('b_zipcode');
        });

        $('#b_countryname').focusout(function() {

                var countrySelected = cidev_get_country_code("b_countryname");

                if (countrySelected == "US" || countrySelected == "CA"){
                        cidev_load_states_b();
                        onSelectChange_b();
                } 

                if (countrySelected != "US") {
                        $('#b_city').unautocomplete();
                }

                if (countrySelected != "US" && countrySelected != "CA") {
                        $('#b_statename').unautocomplete();
                }

		cidev_check_verified_image_for_field('b_countryname');

                if ($('#b_zipcode').val() != ""){
                        document.getElementById("b_zipcode_error_text").style.display = 'none';     
                        document.getElementById("b_zipcode_error_text_div").innerHTML=''; 
                }

        });

        $('#b_statename').focusout(function() {
                onSelectChange_b();

                cidev_check_verified_image_for_field('b_statename');
                cidev_check_verified_image_for_field('b_zipcode');
        });

        function start_b() {
                cidev_load_countries_b();
                cidev_load_states_b();
                onSelectChange_b();
        }

        window.onload = start_b();
  });

{/literal}
//]]>
</script>
{/if}



{if $is_areas.B eq 'Y'}
{if $hide_header eq ""}
<tr>
	<td height="20" colspan="3">
<script type="text/javascript">
<!--
{literal}
function ship2diffOpen() {
	var obj = document.getElementById('ship2diff');
	var box = document.getElementById('ship_box');
	if (!obj || !box)
		return;

	box.style.display = obj.checked ? "" : "none";


	if (obj.checked){

                if ($('#s_firstname').val() != ""){
                        document.getElementById("b_firstname_verified").style.display = '';                      
                        document.getElementById("b_firstname_error").style.display = 'none';     
                }

                if ($('#s_address').val() != ""){
                        document.getElementById("b_address_verified").style.display = '';                      
                        document.getElementById("b_address_error").style.display = 'none';     
                }

                if ($('#s_address_2').val() != ""){
                        document.getElementById("b_address_2_verified").style.display = '';                      
                        document.getElementById("b_address_2_error").style.display = 'none';     
                }

                if ($('#s_zipcode').val() != ""){
                        document.getElementById("b_zipcode_verified").style.display = '';                      
                        document.getElementById("b_zipcode_error").style.display = 'none';     
                        document.getElementById("b_zipcode_error_text").style.display = 'none';     
                        document.getElementById("b_zipcode_error_text_div").innerHTML='';  
                }

                if ($('#s_city').val() != ""){
                        document.getElementById("b_city_verified").style.display = '';                         
                        document.getElementById("b_city_error").style.display = 'none';        
                }

                if ($('#s_countryname').val() != ""){
                        document.getElementById("b_countryname_verified").style.display = '';                      
                        document.getElementById("b_countryname_error").style.display = 'none';     
                }

                if ($('#s_statename').val() != ""){
                        document.getElementById("b_statename_verified").style.display = '';                        
                        document.getElementById("b_statename_error").style.display = 'none';       
                }

		if (document.getElementById("additional_values_1") && document.getElementById("additional_values_2")){
	                if ($('#additional_values_2').val() != ""){
        	                document.getElementById("additional_values_1_verified").style.display = '';                        
                	        document.getElementById("additional_values_1_error").style.display = 'none';       
	                }
		}
	}

	if (obj.checked && window.start_js_states && document.getElementById('b_country') && localBFamily == 'Opera')
		setTimeout(new Function('', "start_js_states(document.getElementById('b_country'));"), 200);
}
{/literal}
-->
</script>
	<B>Bill to a Different Address</B>
	<hr size="1" noshade="noshade" />
	</td>
</tr>
<tr>
		<td align="right"><label for="ship2diff"{* class="RegSectionTitle" *}>My billing address is different from my shipping address</label></td>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="ship2diff" name="ship2diff" value="Y" onclick="javascript: ship2diffOpen();"{if $ship2diff} checked="checked"{/if} /></td>
</tr>
{/if}

</tbody>
<tbody id="ship_box">

{if $action eq "cart"}
<tr style="display: none;">
<td>
<input type="hidden" name="action" value="cart" />
<input type="hidden" name="paymentid" value="{$paymentid}" />
</td>
</tr>
{/if}

<tr>
<td colspan="3">{$lng.txt_newbie_registration_bottom_small_billing}

{* --- *}
<div id="cidev_reg_form_state_city_values_b">
{include file="main/cidev_reg_form_state_city_values_b.tpl"}
</div>

<div id="cidev_reg_form_show_zip_b">
{include file="main/cidev_reg_form_show_zip_b.tpl"}
</div>

<input type="hidden" name="clear_city_in_Change_states_js" id="clear_city_in_Change_states_js" value="Y">
{* --- *}


</td>
</tr>

{if $default_fields.b_title.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_title}</td>
<td valign="top">{if $default_fields.b_title.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<select name="b_title" id="b_title">
{include file="main/title_selector.tpl" field=$userinfo.b_titleid}
</select>
</td>
</tr>
{/if}

{if $default_fields.b_firstname.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_first_name}
{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_firstname}</div>{/if}
</td>
<td valign="top">{if $default_fields.b_firstname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" name="b_firstname" id="b_firstname" size="32" maxlength="32" value="{$userinfo.b_firstname}" placeholder="{$lng.lbl_fill_in_examples_firstname}" onkeyup="cidev_check_field_name('b_firstname')"  />
</td>

{if $usertype eq "C"}
<td id="b_firstname_verified" valign="top" nowrap="nowrap" {if $userinfo.b_firstname eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="b_firstname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.b_firstname eq "" && $default_fields.b_firstname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_lastname.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_last_name}</td>
<td valign="top">{if $default_fields.b_lastname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" name="b_lastname" id="b_lastname" size="32" maxlength="32" value="{$userinfo.b_lastname}" onkeyup="cidev_check_field_name('b_lastname')" />
</td>

{if $usertype eq "C"}
<td id="b_lastname_verified" valign="top" nowrap="nowrap" {if $userinfo.b_lastname eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="b_lastname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.b_lastname eq "" && $default_fields.b_lastname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{include file="main/register_additional_info.tpl" section="B"}

{if $default_fields.b_address.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_address}
{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_address}</div>{/if}
</td>
<td valign="top">{if $default_fields.b_address.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_address" name="b_address" size="32" maxlength="64" value="{$userinfo.b_address}" placeholder="{$lng.lbl_fill_in_examples_address}" onkeyup="cidev_check_field_address('b_address')" />
</td>

{if $usertype eq "C"}
<td id="b_address_verified" valign="top" nowrap="nowrap" {if $userinfo.b_address eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="b_address_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.b_address eq "" and $default_fields.b_address.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_address_2.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_address_2}{if $default_fields.b_address_2.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font>{/if}
{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_address2}</div>{/if}
</td>

<td valign="top">{if $default_fields.b_address_2.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_address_2" name="b_address_2" size="32" maxlength="64" value="{$userinfo.b_address_2}" placeholder="{$lng.lbl_fill_in_examples_address2}" onkeyup="cidev_check_field_address('b_address_2')" />
</td>

{if $usertype eq "C"}
<td id="b_address_2_verified" valign="top" nowrap="nowrap" {if $userinfo.b_address_2 eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="b_address_2_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.b_address_2 eq "" and $default_fields.b_address_2.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_county.avail eq 'Y' and $config.General.use_counties eq "Y"}
<tr>
<td valign="top" align="right">{$lng.lbl_county}</td>
<td valign="top">{if $default_fields.b_county.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
{include file="main/counties.tpl" counties=$counties name="b_county" default=$userinfo.b_county country_name="b_country"}
{if ($reg_error ne "" and $userinfo.b_county eq "" and $default_fields.b_county.required eq 'Y') or $error eq "b_county"}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}


{if $default_fields.b_country.avail eq 'Y'}

{if $usertype ne "P" && $usertype ne "A"}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_country}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_country ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_country}</div>{/if}
</td>
<td valign="top">{if $default_fields.b_country.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_countryname" name="b_countryname" size="32" maxlength="64" value="{if $geo_litecity_location.country ne ""}{section name=country_idx loop=$countries}{if $geo_litecity_location.country eq $countries[country_idx].country_code}{if $countries[country_idx].country ne ""}{$countries[country_idx].country|amp}{assign var="cidev_is_country_b" value="Y"}{/if}{/if}{/section}{else}{if $userinfo.b_countryname ne ""}{$userinfo.b_countryname}{assign var="cidev_is_country_b" value="Y"}{/if}{/if}" 
onkeyup="cidev_check_field_country('b_countryname'); cidev_check_zip_b();"  onchange="cidev_check_field_country('b_countryname'); cidev_check_zip_b();"
autocomplete="off" placeholder="{if $geo_litecity_location.country ne ""}{section name=country_idx loop=$countries}{if $geo_litecity_location.country eq $countries[country_idx].country_code}{$countries[country_idx].country|amp}{/if}{/section}{/if}" />
</td>

{if $usertype eq "C"}
<td id="b_countryname_verified" valign="top" nowrap="nowrap" {if $cidev_is_country_b ne "Y"}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="b_countryname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>


{if $reg_error ne "" and $userinfo.b_country eq "" and $default_fields.b_country.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}


<input type="hidden" id="b_country" name="b_country" size="32" maxlength="32" value="{$userinfo.b_country}" />


</td>
</tr>
{/if}


{if $usertype eq "P" || $usertype eq "A"}
<tr {if $usertype ne "P" && $usertype ne "A"}style="display: none;"{/if}>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_country}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_country ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_country}</div>{/if}
</td>
<td valign="top">{if $default_fields.b_country.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<select name="b_country" id="b_country" size="1" onchange="check_zip_code();">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}" {if $geo_litecity_location.country ne ""}{if $geo_litecity_location.country eq $countries[country_idx].country_code} selected="selected"{/if}{else}{if $userinfo.b_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $userinfo.b_country eq ""} selected="selected"{/if}{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
{if $reg_error ne "" and $userinfo.b_country eq "" and $default_fields.b_country.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{/if}



{if $default_fields.b_zipcode.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_zip_code}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_zipcode ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_zipcode}</div>{/if}
</td>
<td valign="top">{if $default_fields.b_zipcode.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_zipcode" name="b_zipcode" size="32" maxlength="32" value="{$userinfo.b_zipcode}" {if $usertype ne "P" && $usertype ne "A"} onchange="if ($('#ship2diff').attr('checked') )cidev_new_check_zip_code(); check_zip_code_ship('b_zipcode', 'b_countryname');" onkeyup="cidev_check_field('b_zipcode'); cidev_check_address_b();" {/if} autocomplete="off" placeholder="{if $geo_litecity_location.postalCode ne ""}{$geo_litecity_location.postalCode}{else}{$lng.lbl_fill_in_examples_zip}{/if}" />
</td>
{if $usertype eq "C"}
<td id="b_zipcode_verified" valign="top" nowrap="nowrap" {if $geo_litecity_location.postalCode eq "" && $userinfo.s_zipcode eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="b_zipcode_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>

<td id="b_zipcode_error_text" valign="top" style="display: none;">
<div class="cidev_NoteBox" id="b_zipcode_error_text_div"></div>
</td>

{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.b_zipcode eq "" and $default_fields.b_zipcode.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}


{if $default_fields.b_state.avail eq 'Y'}

{if $usertype ne "P" && $usertype ne "A"}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_state}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_state ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_state}</div>{/if}
</td>
<td valign="top">{if $default_fields.b_state.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_statename" name="b_statename" size="32" maxlength="64" 
value="
{if $geo_litecity_location.region ne ""}
{section name=state_idx loop=$states}
{if $geo_litecity_location.country eq $states[state_idx].country_code && $geo_litecity_location.region eq $states[state_idx].state_code}
{if $states[state_idx].state ne ""}{$states[state_idx].state|amp}{assign var="cidev_is_state_b" value="Y"}{/if}
{/if}
{/section}
{else}
{if $userinfo.b_statename ne ""}{$userinfo.b_statename}{assign var="cidev_is_state_b" value="Y"}{/if}
{/if}
" 
onkeyup="cidev_check_field_country('b_statename'); cidev_check_zip_b();" 
autocomplete="off" 
placeholder="
{if $geo_litecity_location.region ne ""}
{section name=state_idx loop=$states}
{if $geo_litecity_location.country eq $states[state_idx].country_code && $geo_litecity_location.region eq $states[state_idx].state_code}
{$states[state_idx].state|amp}
{/if}
{/section}
{else}
{$userinfo.b_statename}
{/if}
" />
</td>
{if $usertype eq "C"}
<td id="b_statename_verified" valign="top" nowrap="nowrap" {if $cidev_is_state_b ne "Y"}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="b_statename_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.b_state eq "" and $default_fields.b_state.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}


<input type="hidden" id="b_state" name="b_state" size="32" maxlength="32" value="{$userinfo.b_state}" />

</td>
</tr>
{/if}


{if $usertype eq "P" || $usertype eq "A"}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_state}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_state ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_state}</div>{/if}
</td>
<td valign="top">{if $default_fields.b_state.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
{include file="main/states.tpl" states=$states name="b_state" default=$userinfo.b_state default_country=$userinfo.b_country|default:$config.General.default_country country_name="b_country"}
{if ($reg_error ne "" and $userinfo.b_state eq "" and $default_fields.b_state.required eq 'Y') or $error eq "b_statecode"}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_state.avail eq 'Y' && $default_fields.b_country.avail eq 'Y' && $js_enabled eq 'Y' && $config.General.use_js_states eq 'Y'}
<tr style="display: none;">
        <td valign="top">
{include file="main/register_states.tpl" state_name="b_state" country_name="b_country" county_name="b_county" state_value=$userinfo.b_state county_value=$userinfo.b_county}
        </td>
</tr>
{/if}
{/if}


{if $default_fields.b_city.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_city}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_city ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_b_city}</div>{/if}
</td>
<td valign="top">{if $default_fields.b_city.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="b_city" name="b_city" size="32" maxlength="64" value="{if $geo_litecity_location.country ne ""}{$geo_litecity_location.city}{else}{$userinfo.b_city}{/if}" {if $usertype ne "P" && $usertype ne "A"} onkeyup="cidev_check_field('b_city'); cidev_check_zip_b();" {/if} placeholder="{if $geo_litecity_location.city ne ""}{$geo_litecity_location.city}{else}{$lng.lbl_fill_in_examples_city}{/if}" />
</td>
{if $usertype eq "C"}
<td id="b_city_verified" valign="top" nowrap="nowrap" {if $geo_litecity_location.city eq "" && $userinfo.b_city eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="b_city_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.b_city eq "" and $default_fields.b_city.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}



{if !$ship2diff}
<tr style="display: none;">
    <td>
<script type="text/javascript">
<!--
if (document.getElementById('ship_box'))
    document.getElementById('ship_box').style.display = 'none';
-->
</script>
    </td>
</tr>
{/if}
</tbody>
<tbody>
{/if}
