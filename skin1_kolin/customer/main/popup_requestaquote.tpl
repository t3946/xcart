{* popup_requestaquote.tpl random *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>{$lng.lbl_shipping_quote} : {$config.Company.company_name}</title>
{ include file="meta.tpl" }
<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
<script src="{$SkinDir}/US_City_List/jquery-1.4.js" type="text/javascript"></script>

{include file="check_zipcode_js.tpl"}
{if $config.General.use_js_states eq 'Y'}
{include file="change_states_js.tpl"}
{/if}

<link rel="stylesheet" href="{$SkinDir}/US_City_List/jquery.autocomplete.css" />
<script src="{$SkinDir}/US_City_List/jquery.autocomplete.js" type="text/javascript"></script>
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>

<link rel="stylesheet" href="{$SkinDir}/lib/colorbox/colorbox.css" />
<script src="{$SkinDir}/lib/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>

<script src="{$SkinDir}/check_email_script.js" type="text/javascript"></script>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}

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

    $("#s_zipcode, #s_city").autocomplete("zip_json.php", {
        minChars: 3,
        selectFirst: true,
        matchSubset: true,
//        width: 220,
        scrollHeight: 300,
        max: 1024,
        dataType: 'json',
        extraParams: {
            zip: function () {
                return $("#s_zipcode:focus").val();
            },
            city: function () {
                var c = $("#s_city:focus").val();
                return c && c + '%'
            }
        },
        parse: function (data) {
            var a = [];
            for(var i = 0;i < data.length; i++)
                a.push({ data: data[i],
                         value: data[i].zip,
                         result: data[i].zip
                       });
            return a;
        },
        formatItem: function (item) {
          if ($("#s_countryname").val() == "United States"){
            return "<span class='ac_zip'>" + item.zip + "</span>" +
                              "<span class='ac_city'>" + item.city +
                              ", " + item.state + "</span>";
          } else {
            return false;
          }
        },
    });

    $("#s_zipcode, #s_city").result(function (event, item) {
        $("#s_zipcode").val(item.zip);
        $("#s_city").val(item.city);
        $("#s_state").val(item.state);
        $("#s_statename").val(item.state_name);
    });

});


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

          cidev_check_verified_image_for_field('s_statename');
          cidev_check_verified_image_for_field('s_zipcode');
          cidev_check_verified_image_for_field('s_city');
          return true; ///////////////////////////////////

                var s_city_in_registerform = document.forms["registerform"].s_city.value;
                var s_state_in_registerform = cidev_get_state_code("s_statename", "s_countryname");
//                var s_zipcode_in_registerform_length = document.forms["registerform"].s_zipcode.value.length;
                var s_zipcode_in_registerform = document.forms["registerform"].s_zipcode.value;

                var s_country_in_registerform = cidev_get_country_code("s_countryname");


                if (s_country_in_registerform == "US"){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_zip&s_city_in_registerform=' + s_city_in_registerform + '&s_state_in_registerform=' + s_state_in_registerform + '&s_zipcode_in_registerform=' + s_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
							cidev_id$("cidev_ship_form_show_zip").innerHTML=cidev_xmlHttp.responseText;
							if (cidev_id$("s_zipcode_hidden")){
								document.forms["registerform"].s_zipcode.value = cidev_id$("s_zipcode_hidden").value;
							}
                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_popup_requestaquote.php',true);
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

//                        cidev_show_state_city();

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

                                var cidev_parameters = 'cidev_filter_mode=show_state_city&s_zipcode_in_registerform=' + s_zipcode_in_registerform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("tr_s_state_s_city_table").innerHTML=cidev_xmlHttp.responseText;

							if (cidev_id$("td_s_state_show_text")){
								document.forms["registerform"].s_statename.value = cidev_id$("td_s_state_show_text").innerHTML;
							}

							if (cidev_id$("td_s_city_show_text")){
								document.forms["registerform"].s_city.value = cidev_id$("td_s_city_show_text").innerHTML;
							}

                                                }else{
                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_popup_requestaquote.php',true);
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

return true; ///////////////////////////////////

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
{if $cart.products ne ""}
{foreach from=$cart.products item=item key=key}
{math assign="count_products" equation="x+1" x=$count_products}
{/foreach}
{/if}


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
/*
$(function(){
  $("#s_firstname").focusout(function(event){
        if (document.forms["registerform"].s_firstname.value != ""){
                document.getElementById("s_firstname_verified").style.display = '';                      
                document.getElementById("s_firstname_error").style.display = 'none';  
        }
        event.preventDefault();
  });

  $("#s_address").focusout(function(event){
        if (document.forms["registerform"].s_address.value != ""){
                document.getElementById("s_address_verified").style.display = '';                        
                document.getElementById("s_address_error").style.display = 'none';    
        }
        event.preventDefault();
  });

  $("#s_address_2").focusout(function(event){
        if (document.forms["registerform"].s_address_2.value != ""){
                document.getElementById("s_address_2_verified").style.display = '';                      
                document.getElementById("s_address_2_error").style.display = 'none';  
        }
        event.preventDefault();
  });

  $("#s_countryname").focusout(function(event){
        if (document.forms["registerform"].s_countryname.value != ""){
                document.getElementById("s_countryname_verified").style.display = '';                    
                document.getElementById("s_countryname_error").style.display = 'none';
        }
        event.preventDefault();
  });

  $("#s_zipcode").focusout(function(event){
        if (document.forms["registerform"].s_zipcode.value != ""){
                document.getElementById("s_zipcode_verified").style.display = '';                        
                document.getElementById("s_zipcode_error").style.display = 'none';    
        }
        event.preventDefault();
  });

  $("#s_statename").focusout(function(event){
        if (document.forms["registerform"].s_statename.value != ""){
                document.getElementById("s_statename_verified").style.display = '';                      
                document.getElementById("s_statename_error").style.display = 'none';  
        }
        event.preventDefault();
  });

  $("#s_city").focusout(function(event){
        if (document.forms["registerform"].s_city.value != ""){ 
                document.getElementById("s_city_verified").style.display = '';                           
                document.getElementById("s_city_error").style.display = 'none';  
        }
        event.preventDefault();
  });

  $("#company").focusout(function(event){
        if (document.forms["registerform"].company.value != ""){
                document.getElementById("company_verified").style.display = '';                          
                document.getElementById("company_error").style.display = 'none';  
        }
        event.preventDefault();
  });

  $("#phone").focusout(function(event){
        if (document.forms["registerform"].phone.value != ""){
                document.getElementById("phone_verified").style.display = '';                            
                document.getElementById("phone_error").style.display = 'none';  
        }
        event.preventDefault();
  });

  $("#phone_ext").focusout(function(event){
        if (document.forms["registerform"].phone_ext.value != ""){
                document.getElementById("phone_ext_verified").style.display = '';                        
                document.getElementById("phone_ext_error").style.display = 'none';  
        }
        event.preventDefault();
  });

});
*/


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

        $('#email').focusout(function() {

                if ($('#email').val() != ""){
                        checkEmailAddress(document.registerform.email, 'Y')
                }
                else {
                        document.getElementById("email_verified").style.display = 'none';                      
                        document.getElementById("email_error").style.display = '';  
                        document.getElementById("email_error_text").style.display = '';  
                }
        });

        $('#phone').focusout(function() {


                var s_country_in_registerform = cidev_get_country_code("s_countryname");
                var b_country_in_registerform = cidev_get_country_code("b_countryname");

                if (!$('#ship2diff').attr('checked')) {
                        b_country_in_registerform = s_country_in_registerform;
                }

                var phone_length_ok = "Y";

                if (s_country_in_registerform == b_country_in_registerform && b_country_in_registerform == "US"){
                        var tmp_phone_field_val = $('#phone').val();
                        tmp_phone_field_val = tmp_phone_field_val.replace(/[^0-9]/g, '');
                        var tmp_phone_field_val_length = tmp_phone_field_val.length;

                        if (tmp_phone_field_val_length < 10){
                                phone_length_ok = "N";
                        }
                }

                if (phone_length_ok == "Y") {
                        cidev_check_verified_image_for_field('phone');
                }
                else {
                        document.getElementById("phone_error").style.display = '';
                        document.getElementById("phone_verified").style.display = 'none';
                }
        });

        $('#phone_ext').focusout(function() {
                if ($('#phone_ext').val() != ""){
                        document.getElementById("phone_ext_verified").style.display = '';
                        document.getElementById("phone_ext_error").style.display = 'none';
                } else {
                        document.getElementById("phone_ext_error").style.display = 'none';
                        document.getElementById("phone_ext_verified").style.display = 'none';
                }
        });

/*
        $('#company').focusout(function() {
                cidev_check_verified_image_for_field('company');
        });
*/

        $('#company').focusout(function() { 
                if ($('#company').val() != ""){
                        document.getElementById("company_verified").style.display = '';
                        document.getElementById("company_error").style.display = 'none';
                } else {
                        document.getElementById("company_error").style.display = 'none';
                        document.getElementById("company_verified").style.display = 'none';
                }
        });
       
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
{if !($usertype eq "A" || $usertype eq "P")}
    <script type="text/javascript">
        ga('send', 'pageview');
    </script>
{/if}


<form action="popup_requestaquote.php" method="post" name="registerform">
<input type="hidden" name="mode" value="{if $mode eq ''}requestaquote{else}{$mode}{/if}" />



<table width="100%" cellpadding="0" cellspacing="0" align="center" class="Container">
<tr>
	<td class="PopupTitle">Request a quote</td>
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
		<td height="30"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
	</tr>
	<tr>
		<td align="center">

		<div id="cidev_ship_form_show_zip">
		{include file="customer/main/cidev_ship_form_show_zip.tpl"}
		</div>


	    <table cellpadding="2" cellspacing="1" border="0">
		<tr>
			<td align="left" colspan="3">
		    {include file="customer/main/subheader.tpl" title="Your shipping address:"}
			</td>
		</tr>
		<tr>
			<td colspan="3">{$lng.txt_fields_are_mandatory}</td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>


<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_address}
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_address}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td valign="top" nowrap="nowrap">
 <table cellpadding="0" cellspacing="0">
 <tr>
 <td valign="top" nowrap="nowrap">
 <input type="text" id="s_address" name="s_address" size="32" maxlength="64" value="{$userinfo.s_address}" placeholder="{$lng.lbl_fill_in_examples_address}" onkeyup="cidev_check_field_address('s_address')" />
 </td>
 <td id="s_address_verified" valign="top" nowrap="nowrap" {if $userinfo.s_address eq ""}style="display: none;"{/if}>
 <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
 </td>
 <td id="s_address_error" valign="top" nowrap="nowrap" style="display: none;">
 <img src="{$ImagesDir}/checkmark-error.png" alt="" />
 </td>
 </tr>
 </table>
</td>
</tr>

<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_address_2} <font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font>
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_address2}</div></td>
<td valign="top">{if $default_fields.s_address_2.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td nowrap="nowrap" valign="top">
 <table cellpadding="0" cellspacing="0">
 <tr>
 <td valign="top" nowrap="nowrap">
 <input type="text" id="s_address_2" name="s_address_2" size="32" maxlength="64" value="{$userinfo.s_address_2}" placeholder="{$lng.lbl_fill_in_examples_address2}" onkeyup="cidev_check_field_address('s_address_2')" />
 </td>
 <td id="s_address_2_verified" valign="top" nowrap="nowrap" {if $userinfo.s_address_2 eq ""}style="display: none;"{/if}>
 <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
 </td>
 <td id="s_address_2_error" valign="top" nowrap="nowrap" style="display: none;">
 <img src="{$ImagesDir}/checkmark-error.png" alt="" />
 </td>
 </tr>
 </table>
</td>
</tr>


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

<input type="hidden" id="s_country" name="s_country" size="32" maxlength="32" value="{$userinfo.s_country}" />
</td>
</tr>


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

</td>
</tr>


<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_state}
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_state}</div>
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

<input type="hidden" id="s_state" name="s_state" size="32" maxlength="32" value="{$userinfo.s_state}" />
</td>
</tr>


<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_city}
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_s_city}</div>
</td>
<td valign="top">{if $default_fields.s_city.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_city" name="s_city" size="32" maxlength="64" value="{if $geo_litecity_location.country ne ""}{$geo_litecity_location.city}{else}{$userinfo.s_city}{/if}" {if $usertype ne "P" && $usertype ne "A"} onkeyup="cidev_check_field('s_city'); cidev_check_zip(); cidev_check_verified_image_for_field('s_zipcode');" {/if} placeholder="{if $geo_litecity_location.city ne ""}{$geo_litecity_location.city}{else}{$lng.lbl_fill_in_examples_city}{/if}" />
</td>
<td id="s_city_verified" valign="top" nowrap="nowrap" {if $geo_litecity_location.city eq "" && $userinfo.s_city eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>
<td id="s_city_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
</tr>
</table>
</td>
</tr>





                <tr>
                        <td align="left" colspan="3">
                    {include file="customer/main/subheader.tpl" title="Your contact information:"}
                        </td>
                </tr>


<tr>
<td valign="top" align="right" width="49%" class="cidev_padding_top">{$lng.lbl_first_name}
<div class="cidev_checkout_descr">{$lng.lbl_POPUP_QUOTE_s_firstname}</div>
</td>
<td valign="top" width="5"><font class="Star">*</font></td>
<td valign="top" nowrap="nowrap" {if $usertype eq "C"}width="*"{/if}>

<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="s_firstname" name="s_firstname" size="32" maxlength="32" value="{$userinfo.s_firstname|replace:"&amp;#039;":"'"}" placeholder="{$lng.lbl_fill_in_examples_firstname}" onkeyup="cidev_check_field_name('s_firstname')" />
</td>

<td id="s_firstname_verified" valign="top" nowrap="nowrap" {if $userinfo.s_firstname eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="s_firstname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>

</tr>
</table>
</td>
</tr>


<tr>
<td valign="top" align="right" width="49%" class="cidev_padding_top">{$lng.lbl_company}
<div class="cidev_checkout_descr">{$lng.lbl_POPUP_QUOTE_company}</div>
</td>
<td valign="top" width="5"></td>
<td valign="top" nowrap="nowrap">

<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="company" name="company" size="32" maxlength="32" value="{$userinfo.company|replace:"&amp;#039;":"'"}" placeholder="{$lng.lbl_fill_in_examples_company}" onkeyup="cidev_check_field_name('company')" />
</td>

<td id="company_verified" valign="top" nowrap="nowrap" {if $userinfo.company eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="company_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>

</tr>
</table>
</td>
</tr>



<tr>
<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_phone}
<div class="cidev_checkout_descr">{$lng.lbl_POPUP_QUOTE_phone}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="phone" name="phone" size="32" maxlength="32" value="{$userinfo.phone}" placeholder="{$lng.lbl_fill_in_examples_phone}" onkeyup="cidev_check_field_phone('phone')" />
</td>

<td width="25">
<table cellpadding="0" cellspacing="0">
<tr>
<td id="phone_verified" valign="top" nowrap="nowrap" {if $userinfo.phone eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>
<td id="phone_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
</tr>
</table>
</td>

{* --------------- *}
<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_phone_ext}
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_phone_ext}</div>
</td>
<td valign="top">{if $default_fields.phone_ext.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<input type="text" id="phone_ext" name="phone_ext" size="6" maxlength="6" value="{$userinfo.phone_ext}" placeholder="{$lng.lbl_fill_in_examples_phone_ext}" onkeyup="cidev_check_field_phone_ext('phone_ext')" />
</td>

<td id="phone_ext_verified" valign="top" nowrap="nowrap" {if $userinfo.phone_ext eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>
<td id="phone_ext_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{* --------------- *}

</tr>
</table>
</td>
</tr>


<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_email}
<div class="cidev_checkout_descr">{$lng.lbl_POPUP_QUOTE_email}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="email" name="email" size="32" maxlength="128" value="{$userinfo.email}" placeholder="{$lng.lbl_fill_in_examples_email}" {* onblur="javascript: $('#email_note').hide();" onfocus="javascript: cidev_showNote('email_note', this);" *} />
</td>

<td id="email_verified" valign="top" nowrap="nowrap" {if $userinfo.email eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="email_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
<td id="email_error_text" valign="top" style="display: none;">
<div id="email_note" class="cidev_NoteBox">{$lng.txt_email_invalid}</div>
</td>

</tr>
</table>
</td>
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

 var cidev_yandex_code_number = "{/literal}{$config.Company.cidev_yandex_code_number}{literal}";

 if (cidev_yandex_code_number != ""){
	 yaCounter{/literal}{$config.Company.cidev_yandex_code_number}{literal}.reachGoal('sqCALCULATE', yaGoalParams);

	 _gaq.push(['_trackEvent', 'sqCALCULATE']);
 }

 document.registerform.submit();
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

        if ($("#s_address").val() == "" || $("#s_city").val() == "" || $("#s_statename").val() == "" || $("#s_countryname").val() == "" || $("#s_firstname").val() == "" || $("#company").val() == "" || $("#phone").val() == "" || $("#email").val() == ""){
                alert("The fields marked with * are mandatory.");
                return false;
        }

	document.registerform.submit();

//	var countrySelected = cidev_get_country_code("s_country");
//	if (cidev_new_check_zip_code_field(countrySelected, cidev_id$("s_zipcode"), 's_zipcode')){
//		cidev_sqCALCULATE();
//		return true;
//	}
	
//	return false;
}

{/literal}
//]]>
</script>


<br />
{include file="buttons/button.tpl" button_title="Request a quote" type="input" href="javascript: cidev_check_zipcode();" js_to_href="Y" b="1"}
</td>
		</tr>
		</table>
		</td>
	</tr>
	{/if}

	{if $mode eq 'requested'}
        <tr>
                <td height="30"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
        </tr>
        <tr>
                <td align="center">
			{$lng.lbl_requested_quote}
                </td>
        </tr>

	<tr>
		<td align="center">
				{include file="buttons/button.tpl" button_title="Close window" type="input" href="javascript: window.close() " js_to_href="Y"}
		</td>
	</tr>
	{/if}

	</table>
	</td>
</tr>
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
