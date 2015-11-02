{* $Id: register_shipping_address.tpl,v 1.38.2.7 2007/01/16 06:42:49 max Exp $ *}

{if $usertype ne "P" && $usertype ne "A"}

<script type="text/javascript">
//<![CDATA[
{literal}

$(function(){
  $("#s_firstname").focusout(function(event){

//	if (document.forms["registerform"].s_firstname.value != "" && document.forms["registerform"].firstname && document.forms["registerform"].firstname.value == ""){
	if (document.forms["registerform"].s_firstname.value != "" && document.forms["registerform"].firstname){
        	document.forms["registerform"].firstname.value = document.forms["registerform"].s_firstname.value;
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


	function ltrim(stringToTrim) {
		return stringToTrim.replace(/^\s+/,"");
	}

        function check_zip_code_ship() {

		document.forms["registerform"].s_zipcode.value = $.trim(document.forms["registerform"].s_zipcode.value);

                return check_zip_code_field(document.forms["registerform"].s_country, document.forms["registerform"].s_zipcode);
        }

        function cidev_check_zip(){

                var s_city_in_registerform = document.forms["registerform"].s_city.value;
                var s_state_in_registerform = document.forms["registerform"].s_state.value;
                var s_zipcode_in_registerform_length = document.forms["registerform"].s_zipcode.value.length;

                var s_country_in_registerform = document.forms["registerform"].s_country.value;
                if (s_country_in_registerform == "US"){
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_zip_reg_form&s_city_in_registerform=' + s_city_in_registerform + '&s_state_in_registerform=' + s_state_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_show_zip").innerHTML=cidev_xmlHttp.responseText;

//							if (cidev_id$("s_zip_show_text").value){
								document.forms["registerform"].s_zipcode.value = cidev_id$("s_zip_show_text").value;
//							}
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
                var s_country_in_registerform = document.forms["registerform"].s_country.value;


		document.forms["registerform"].s_zipcode.value = document.forms["registerform"].s_zipcode.value.replace(/[^\w]/g, "");
//		document.forms["registerform"].s_zipcode.value = $.trim(document.forms["registerform"].s_zipcode.value);

                if (s_country_in_registerform == "US"){
                        cidev_show_state_city();
                }
        }

        function cidev_show_state_city(){
			document.forms["registerform"].s_zipcode.value = ltrim(document.forms["registerform"].s_zipcode.value);
                        var s_zipcode_in_registerform = document.forms["registerform"].s_zipcode.value;
//                      var s_zipcode_in_registerform = cidev_id$('s_zipcode').value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_state_city_reg_form&s_zipcode_in_registerform=' + s_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_state_city_values").innerHTML=cidev_xmlHttp.responseText;

                                                        document.forms["registerform"].s_state.value = cidev_id$("s_state_show_text").value;
                                                        document.forms["registerform"].s_city.value = cidev_id$("s_city_show_text").value;

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

$(document).ready(function() {  

        $('#s_country').change(function() {
                $('#s_city').val(""); //to empty it
                $('#s_zipcode').val(""); //to empty it
                $('#s_city').unautocomplete();

                var countrySelected = $('#s_country option:selected');
                if (countrySelected.val() == "US"){
//                      document.forms["registerform"].s_state.value = "NY";
                        onSelectChange();
                }
        });


        $('#s_state').change(function() {
                $('#s_city').val(""); //to empty it
                $('#s_city').unautocomplete();
                onSelectChange();
        });
        
function onSelectChange() {
        var cityFilePath = '';
        var stateSelected = $('#s_state option:selected');

        if (stateSelected.val()){

                cityFilePath = "skin1_kolin/US_City_List/" +stateSelected.val().toLowerCase()+".js";

                $.getScript(cityFilePath, function() {

                        $('#s_city').autocomplete(city, {
                                autoFill: false,
                                cacheLength: 1
                        });
                });
        }
};

function start() {

//	var geo_litecity_location_city = "{/literal}{$geo_litecity_location.city}{literal}";
//	var geo_litecity_location_region = "{/literal}{$geo_litecity_location.region}{literal}";
//	var geo_litecity_location_country = "{/literal}{$geo_litecity_location.country}{literal}";

//	if (geo_litecity_location_country == "US" || geo_litecity_location_country == "CA"){
//		document.forms["registerform"].s_state.value = geo_litecity_location_region;
//	}

        onSelectChange();
//alert(geo_litecity_location_city);
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
{else}
{literal}
        var geo_litecity_location_city = "";
        var geo_litecity_location_region = "";
        var geo_litecity_location_country = "";
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
<td align="right">{$lng.lbl_title}</td>
<td>{if $default_fields.s_title.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap"> 
<select name="s_title" id="s_title">
{include file="main/title_selector.tpl" field=$userinfo.s_titleid}
</select> 
</td> 
</tr> 
 {/if}

{if $default_fields.s_firstname.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_first_name}</td>
<td>{if $default_fields.s_firstname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap"> 
<input type="text" id="s_firstname" name="s_firstname" size="32" maxlength="32" value="{$userinfo.s_firstname}" placeholder="{$lng.lbl_fill_in_examples_firstname}" />
{if $reg_error ne "" and $userinfo.s_firstname eq "" && $default_fields.s_firstname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
 {/if}

{if $default_fields.s_lastname.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_last_name}</td>
<td>{if $default_fields.s_lastname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="s_lastname" name="s_lastname" size="32" maxlength="32" value="{$userinfo.s_lastname}" />
{if $reg_error ne "" and $userinfo.s_lastname eq "" && $default_fields.s_lastname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{include file="main/register_additional_info.tpl" section="S"}

{if $default_fields.s_address.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_address}</td>
<td>{if $default_fields.s_address.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="s_address" name="s_address" size="32" maxlength="64" value="{$userinfo.s_address}" placeholder="{$lng.lbl_fill_in_examples_address}" />
{if $reg_error ne "" and $userinfo.s_address eq "" and $default_fields.s_address.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_address_2.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_address_2} {if $default_fields.s_address_2.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #C0C0C0;"><I>(optional)</I></font>{/if}</td>
<td>{if $default_fields.s_address_2.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="s_address_2" name="s_address_2" size="32" maxlength="64" value="{$userinfo.s_address_2}" placeholder="{$lng.lbl_fill_in_examples_address2}" />
{if $reg_error ne "" and $userinfo.s_address_2 eq "" and $default_fields.s_address_2.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_county.avail eq 'Y' and $config.General.use_counties eq "Y"}
<tr>
<td align="right">{$lng.lbl_county}</td>
<td>{if $default_fields.s_county.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
{include file="main/counties.tpl" counties=$counties name="s_county" default=$userinfo.s_county country_name="s_country"}
{if ($reg_error ne "" and $userinfo.s_county eq "" and $default_fields.s_county.required eq 'Y') or $error eq "s_county"}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_country.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_country}</td>
<td>{if $default_fields.s_country.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<select name="s_country" id="s_country" size="1" onchange="check_zip_code(); {if $usertype ne "P" && $usertype ne "A"} check_zip_code_ship(); {/if}">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}" {if $geo_litecity_location.country ne ""}{if $geo_litecity_location.country eq $countries[country_idx].country_code} selected="selected"{/if}{else}{if $userinfo.s_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $userinfo.s_country eq ""} selected="selected"{/if}{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
{if $reg_error ne "" and $userinfo.s_country eq "" and $default_fields.s_country.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_zipcode.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_zip_code}</td>
<td>{if $default_fields.s_zipcode.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="s_zipcode" name="s_zipcode" size="32" maxlength="32" value="{$userinfo.s_zipcode}" {if $usertype ne "P" && $usertype ne "A"} onkeyup="cidev_check_address()" {/if} onchange="check_zip_code(); {if $usertype ne "P" && $usertype ne "A"} check_zip_code_ship(); {/if}" autocomplete="off" placeholder="{if $geo_litecity_location.postalCode ne ""}{$geo_litecity_location.postalCode}{else}{$lng.lbl_fill_in_examples_zip}{/if}" />
{if $reg_error ne "" and $userinfo.s_zipcode eq "" and $default_fields.s_zipcode.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_state.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_state}</td>
<td>{if $default_fields.s_state.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
{include file="main/states.tpl" states=$states name="s_state" default=$userinfo.s_state default_country=$userinfo.s_country|default:$config.General.default_country country_name="s_country"}
{if ($reg_error ne "" and $userinfo.s_state eq "" and $default_fields.s_state.required eq 'Y') or $error eq "s_statecode"}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.s_state.avail eq 'Y' && $default_fields.s_country.avail eq 'Y' && $js_enabled eq 'Y' && $config.General.use_js_states eq 'Y'}
<tr style="display: none;">
	<td>
{include file="main/register_states.tpl" state_name="s_state" country_name="s_country" county_name="s_county" state_value=$userinfo.s_state county_value=$userinfo.s_county}
	</td>
</tr>
{/if}

{if $default_fields.s_city.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_city}</td>
<td>{if $default_fields.s_city.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="s_city" name="s_city" size="32" maxlength="64" value="{$userinfo.s_city}" {if $usertype ne "P" && $usertype ne "A"} onkeyup="cidev_check_zip()" {/if} placeholder="{if $geo_litecity_location.city ne ""}{$geo_litecity_location.city}{else}{$lng.lbl_fill_in_examples_city}{/if}" />
{if $reg_error ne "" and $userinfo.s_city eq "" and $default_fields.s_city.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{/if}
