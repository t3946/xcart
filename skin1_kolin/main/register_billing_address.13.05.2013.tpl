{* $Id: register_billing_address.tpl,v 1.27.2.2 2006/10/25 06:39:34 max Exp $ *}

{if $usertype ne "P" && $usertype ne "A"}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
        function check_zip_code_ship_b() {

		document.forms["registerform"].b_zipcode.value = $.trim(document.forms["registerform"].b_zipcode.value);

                return check_zip_code_field(document.forms["registerform"].b_country, document.forms["registerform"].b_zipcode);
        }

        function cidev_check_zip_b(){

                var b_city_in_registerform = document.forms["registerform"].b_city.value;
                var b_state_in_registerform = document.forms["registerform"].b_state.value;
                var b_zipcode_in_registerform_length = document.forms["registerform"].b_zipcode.value.length;

                var b_country_in_registerform = document.forms["registerform"].b_country.value;
                if (b_country_in_registerform == "US"){
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_zip_reg_form_b&b_city_in_registerform=' + b_city_in_registerform + '&b_state_in_registerform=' + b_state_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_show_zip_b").innerHTML=cidev_xmlHttp.responseText;

//                                                      if (cidev_id$("b_zip_show_text").value){
                                                                document.forms["registerform"].b_zipcode.value = cidev_id$("b_zip_show_text").value;
//                                                      }
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
                var b_country_in_registerform = document.forms["registerform"].b_country.value;

		document.forms["registerform"].b_zipcode.value = document.forms["registerform"].b_zipcode.value.replace(/[^\w]/g, "");

                if (b_country_in_registerform == "US"){
                        cidev_show_state_city_b();
                }
        }

        function cidev_show_state_city_b(){

			document.forms["registerform"].b_zipcode.value = ltrim(document.forms["registerform"].b_zipcode.value);
                        var b_zipcode_in_registerform = document.forms["registerform"].b_zipcode.value;
//                      var b_zipcode_in_registerform = cidev_id$('b_zipcode').value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_state_city_reg_form_b&b_zipcode_in_registerform=' + b_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_reg_form_state_city_values_b").innerHTML=cidev_xmlHttp.responseText;

                                                        document.forms["registerform"].b_state.value = cidev_id$("b_state_show_text").value;
                                                        document.forms["registerform"].b_city.value = cidev_id$("b_city_show_text").value;

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

$(document).ready(function() {

        $('#b_country').change(function() {
                $('#b_city').val(""); //to empty it
                $('#b_zipcode').val(""); //to empty it
                $('#b_city').unautocomplete();

                var countrySelected = $('#b_country option:selected');
                if (countrySelected.val() == "US"){
//                      document.forms["registerform"].b_state.value = "NY";
                        onSelectChange_b();
                }
        });


        $('#b_state').change(function() {
                $('#b_city').val(""); //to empty it
                $('#b_city').unautocomplete();
                onSelectChange_b();
        });

function onSelectChange_b() {
        var cityFilePath = '';
        var stateSelected = $('#b_state option:selected');

        if (stateSelected.val()){

                cityFilePath = "skin1_kolin/Ub_City_List/" +stateSelected.val().toLowerCase()+".js";

                $.getScript(cityFilePath, function() {

                        $('#b_city').autocomplete(city, {
                                autoFill: false,
                                cacheLength: 1
                        });
                });
        }
};

function start_b() {
        onSelectChange_b();
}

        window.onload = start_b;
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
	if (obj.checked && window.start_js_states && document.getElementById('b_country') && localBFamily == 'Opera')
		setTimeout(new Function('', "start_js_states(document.getElementById('b_country'));"), 200);
}
{/literal}
-->
</script>
	
	<br />
	<table cellpadding="0" cellspacing="0">
	<tr>
		<td><label for="ship2diff" class="RegSectionTitle">{$lng.lbl_ship_to_different_address}</label></td>
		<td>&nbsp;</td>
		<td><input type="checkbox" id="ship2diff" name="ship2diff" value="Y" onclick="javascript: ship2diffOpen();"{if $ship2diff} checked="checked"{/if} /></td>
	</tr>
	</table>
	<hr size="1" noshade="noshade" />
	</td>
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
<td align="right">{$lng.lbl_title}</td>
<td>{if $default_fields.b_title.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<select name="b_title" id="b_title">
{include file="main/title_selector.tpl" field=$userinfo.b_titleid}
</select>
</td>
</tr>
{/if}

{if $default_fields.b_firstname.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_first_name}</td>
<td>{if $default_fields.b_firstname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" name="b_firstname" id="b_firstname" size="32" maxlength="32" value="{$userinfo.b_firstname}" placeholder="{$lng.lbl_fill_in_examples_firstname}"  />
{if $reg_error ne "" and $userinfo.b_firstname eq "" && $default_fields.b_firstname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_lastname.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_last_name}</td>
<td>{if $default_fields.b_lastname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" name="b_lastname" id="b_lastname" size="32" maxlength="32" value="{$userinfo.b_lastname}" />
{if $reg_error ne "" and $userinfo.b_lastname eq "" && $default_fields.b_lastname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{include file="main/register_additional_info.tpl" section="B"}

{if $default_fields.b_address.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_address}</td>
<td>{if $default_fields.b_address.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="b_address" name="b_address" size="32" maxlength="64" value="{$userinfo.b_address}" placeholder="{$lng.lbl_fill_in_examples_address}" />
{if $reg_error ne "" and $userinfo.b_address eq "" and $default_fields.b_address.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_address_2.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_address_2}{if $default_fields.b_address_2.required ne 'Y'}<font style="font-size: 11px; font-family: italic; color: #C0C0C0;"><I>(optional)</I></font>{/if}</td>

<td>{if $default_fields.b_address_2.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="b_address_2" name="b_address_2" size="32" maxlength="64" value="{$userinfo.b_address_2}" placeholder="{$lng.lbl_fill_in_examples_address2}" />
{if $reg_error ne "" and $userinfo.b_address_2 eq "" and $default_fields.b_address_2.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_county.avail eq 'Y' and $config.General.use_counties eq "Y"}
<tr>
<td align="right">{$lng.lbl_county}</td>
<td>{if $default_fields.b_county.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
{include file="main/counties.tpl" counties=$counties name="b_county" default=$userinfo.b_county country_name="b_country"}
{if ($reg_error ne "" and $userinfo.b_county eq "" and $default_fields.b_county.required eq 'Y') or $error eq "b_county"}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_country.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_country}</td>
<td>{if $default_fields.b_country.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<select name="b_country" id="b_country" onchange="if ($('#ship2diff').attr('checked')) check_zip_code()">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $userinfo.b_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $userinfo.b_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
{if $reg_error ne "" and $userinfo.b_country eq "" and $default_fields.b_country.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}


{if $default_fields.b_zipcode.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_zip_code}</td>
<td>{if $default_fields.b_zipcode.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="b_zipcode" name="b_zipcode" size="32" maxlength="32" value="{$userinfo.b_zipcode}" onchange="if ($('#ship2diff').attr('checked') )check_zip_code(); {if $usertype ne "P" && $usertype ne "A"} check_zip_code_ship_b(); {/if}" {if $usertype ne "P" && $usertype ne "A"} onkeyup="cidev_check_address_b()" {/if} autocomplete="off" placeholder="{if $geo_litecity_location.postalCode ne ""}{$geo_litecity_location.postalCode}{else}{$lng.lbl_fill_in_examples_zip}{/if}" />
{if $reg_error ne "" and $userinfo.b_zipcode eq "" and $default_fields.b_zipcode.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}


{if $default_fields.b_state.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_state}</td>
<td>{if $default_fields.b_state.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
{include file="main/states.tpl" states=$states name="b_state" default=$userinfo.b_state default_country=$userinfo.b_country country_name="b_country"}
{if $error eq "b_statecode" || ($reg_error ne "" && $userinfo.b_state eq "" && $default_fields.b_state.required eq 'Y')}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}

{if $default_fields.b_state.avail eq 'Y' && $default_fields.b_country.avail eq 'Y' && $js_enabled eq 'Y' && $config.General.use_js_states eq 'Y'}
<tr style="display: none;">
	<td>
{include file="main/register_states.tpl" state_name="b_state" country_name="b_country" county_name="b_county" state_value=$userinfo.b_state county_value=$userinfo.b_county}
	</td>
</tr>
{/if}

{if $default_fields.b_city.avail eq 'Y'}
<tr>
<td align="right">{$lng.lbl_city}</td>
<td>{if $default_fields.b_city.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap">
<input type="text" id="b_city" name="b_city" size="32" maxlength="64" value="{$userinfo.b_city}" onkeyup="cidev_check_zip_b()" placeholder="{if $geo_litecity_location.city ne ""}{$geo_litecity_location.city}{else}{$lng.lbl_fill_in_examples_city}{/if}" />
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
