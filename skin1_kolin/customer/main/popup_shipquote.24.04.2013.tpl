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

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
	function check_zip_code_ship() {


		var s_country_in_shipquoteform = document.forms["shipquoteform"].s_country.value;

		if (s_country_in_shipquoteform == "US"){
			document.getElementById("tr_s_state").style.display = 'none';
			document.getElementById("tr_s_city").style.display = 'none';
			document.getElementById("tr_i_do_not_know_zip").style.display = '';
			document.getElementById("tr_s_state_s_city_table").style.display = '';
		} else {
                        document.getElementById("tr_s_state").style.display = '';
                        document.getElementById("tr_s_city").style.display = '';
			document.getElementById("tr_i_do_not_know_zip").style.display = 'none';
                        document.getElementById("tr_s_state_s_city_table").style.display = 'none';
		}

		return check_zip_code_field(document.forms["shipquoteform"].s_country, document.forms["shipquoteform"].s_zipcode);
	}

	function cidev_show_fields() {
                        document.getElementById("tr_s_state").style.display = '';
                        document.getElementById("tr_s_city").style.display = '';
			document.getElementById("tr_i_do_not_know_zip").style.display = 'none';
                        document.getElementById("tr_s_state_s_city_table").style.display = 'none';
			document.forms["shipquoteform"].s_zipcode.value = "";
			document.forms["shipquoteform"].s_city.value = "";
			document.forms["shipquoteform"].s_state.value = "AL";
	}

/*
	function cidev_check_city() {

		var s_country_in_shipquoteform = document.forms["shipquoteform"].s_country.value;
                if (s_country_in_shipquoteform == "US"){


			var s_city_length = document.forms["shipquoteform"].s_city.value.length;

			if (s_city_length > "1"){
	                        cidev_show_cities();
			}
                }
	}


	function cidev_show_cities(){
                        var s_city_in_shipquoteform = document.forms["shipquoteform"].s_city.value;
                        var s_state_in_shipquoteform = document.forms["shipquoteform"].s_state.value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_cities&s_city_in_shipquoteform=' + s_city_in_shipquoteform + '&s_state_in_shipquoteform=' + s_state_in_shipquoteform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_show_cities_js").innerHTML=cidev_xmlHttp.responseText;
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
                                setTimeout('cidev_show_cities()', 1000);
                        }
	}
*/


        function cidev_check_zip(){

                var s_city_in_shipquoteform = document.forms["shipquoteform"].s_city.value;
                var s_state_in_shipquoteform = document.forms["shipquoteform"].s_state.value;
                var s_zipcode_in_shipquoteform_length = document.forms["shipquoteform"].s_zipcode.value.length;

                var s_country_in_shipquoteform = document.forms["shipquoteform"].s_country.value;
                if (s_country_in_shipquoteform == "US"){
//                  if (s_zipcode_in_shipquoteform_length == "0") {
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_zip&s_city_in_shipquoteform=' + s_city_in_shipquoteform + '&s_state_in_shipquoteform=' + s_state_in_shipquoteform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("cidev_show_zip").innerHTML=cidev_xmlHttp.responseText;
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
//                  }
		}
        }


	function cidev_check_address() {
		var s_country_in_shipquoteform = document.forms["shipquoteform"].s_country.value;

		if (s_country_in_shipquoteform == "US"){
			cidev_show_state_city();
		}
	}


	function cidev_show_state_city(){
			var s_zipcode_in_shipquoteform = document.forms["shipquoteform"].s_zipcode.value;
//                      var s_zipcode_in_shipquoteform = cidev_id$('s_zipcode').value;

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=show_state_city&s_zipcode_in_shipquoteform=' + s_zipcode_in_shipquoteform;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("tr_s_state_s_city_table").innerHTML=cidev_xmlHttp.responseText;

							document.forms["shipquoteform"].s_state.value = cidev_id$("td_s_state_show_text").innerHTML;
							document.forms["shipquoteform"].s_city.value = cidev_id$("td_s_city_show_text").innerHTML;

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

{/literal}
-->
</script>
</head>
<body{$reading_direction_tag} style="background-color: #FBFBF3;">


<script type="text/javascript">
//<![CDATA[
{literal}
$(document).ready(function() {  

/*
        $('#s_zipcode').change(function() {
		cidev_check_address();
	});
*/
        $('#s_country').change(function() {
                $('#s_city').val(""); //to empty it
                $('#s_zipcode').val(""); //to empty it
                $('#s_city').unautocomplete();

                var countrySelected = $('#s_country option:selected');
                if (countrySelected.val() == "US"){
//                      document.forms["shipquoteform"].s_state.value = "NY";
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
        onSelectChange();
}
        
        window.onload = start;
});

{/literal}
//]]>
</script>



{* ------------------- *}
{include file="cidev_tracking_code.tpl" }
{* ------------------- *}

{* {$config.Company.cidev_tracking_code} *}


{*
<div id="cidev_show_cities_js">{include file="customer/main/cidev_show_cities_js.tpl"}</div> 
<div id="cidev_show_cities_js"></div>
{include file="customer/main/cidev_show_cities_js.tpl"}
*}

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
			<td align="right" width="200">{$lng.lbl_country}</td>
			<td width="15"><font class="Star">*</font></td>
			<td nowrap="nowrap" align="left" width="300">
			<select name="s_country" id="s_country" size="1" onchange="check_zip_code_ship()">
			{section name=country_idx loop=$countries}
			<option value="{$countries[country_idx].country_code}"{if $userinfo.s_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $userinfo.s_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
			{/section}
			</select>
			</td>
		</tr>
                <tr>
                        <td align="right">{$lng.lbl_zip_code}</td>
                        <td><font class="Star">*</font></td>
                        <td nowrap="nowrap" align="left" id="cidev_show_zip">
                        <input type="text" id="s_zipcode" name="s_zipcode" size="27" maxlength="32" value="{$userinfo.s_zipcode}" onkeyup="cidev_check_address()" onchange="{* cidev_check_address();*} check_zip_code_ship()" autocomplete="off" />
                        </td>
                </tr>

                <tr id="tr_i_do_not_know_zip">
                        <td colspan="3" align="center"><a style="color: #FF0000; border-bottom:1px dotted; TEXT-DECORATION: none;" href="javascript: void(0);" onclick="javascript: cidev_show_fields();">I don't know my ZIP/Postal code!</a></td>
                </tr>

		{* --- *}
		<input type="hidden" name="clear_city_in_Change_states_js" id="clear_city_in_Change_states_js" value="Y">
		{* --- *}


		<tr id="tr_s_state">
			<td align="right">{$lng.lbl_state}</td>
			<td>&nbsp;</td>
			<td nowrap="nowrap" align="left">
			{include file="main/states.tpl" states=$states name="s_state" default=$userinfo.s_state default_country=$userinfo.s_country|default:$config.General.default_country country_name="s_country" }
			</td>
		</tr>
		<tr style="display: none;">
			<td colspan="3">
			{include file="main/register_states.tpl" state_name="s_state" country_name="s_country" county_name="s_county" state_value=$userinfo.s_state county_value=$userinfo.s_county}
			</td>
		</tr>
		<tr id="tr_s_city">
			<td align="right">{$lng.lbl_city}</td>
			<td>&nbsp;</td>
			<td nowrap="nowrap" align="left">
			<input type="text" id="s_city" name="s_city" size="27" maxlength="64" value="{$userinfo.s_city}" {* onchange="cidev_check_zip()"*} onkeyup="cidev_check_zip()"  />
			</td>
		</tr>


		<tr id="tr_s_state_s_city_table" style="display: none;">
			{* {include file="customer/main/cidev_shipquote_state_city_values.tpl"} *}
		</tr>


		<tr>
{*
			<td class="ButtonsRow" align="center" colspan="3" nowrap="nowrap"><br />{include file="buttons/button.tpl" button_title=$lng.lbl_calculate_shippings type="input" href="javascript: yaCounter`$config.Company.cidev_yandex_code_number`.reachGoal('sqCALCULATE'); document.shipquoteform.submit()" js_to_href="Y" b="1"}</td>
*}
			<td class="ButtonsRow" align="center" colspan="3" nowrap="nowrap">

<script type="text/javascript" language="JavaScript 1.2">
<!--
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
{/literal}
-->
</script>


<br />
{include file="buttons/button.tpl" button_title=$lng.lbl_calculate_shippings type="input" href="javascript: cidev_sqCALCULATE()" js_to_href="Y" b="1"}
</td>
		</tr>
		</table>
		</td>
	</tr>
	{/if}

	{if $mode eq 'shipping' and $shipping_groups}
	<tr>
		<td>
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
			{assign var=delivery_text value=$lng.txt_for_fastlane_checkout_delivery|replace:"XX":"`$v.m_city`, `$v.m_state`, `$v.m_country`."|replace:"YY":"`$v.group_name`"}
			{include file="customer/main/subheader.tpl" title="`$lng.lbl_delivery_methods` `$delivery_text`"}
			{foreach from=$shippings[$k] item=s}
			{if $s.active eq "Y" && $s.allowed eq "1"}
			{assign var="found_any_shipping" value="Y"}
			<table cellpadding="1" cellspacing="0" width="100%" {cycle values=" class='TableSubHead', "}>
			<tr>
				<td width="5"><input type="radio" id="shippingid{$s.shippingid}" name="shippingids[{$k}]" value="{$s.shippingid}"{if $s.shippingid eq $shippingids[$k].shippingid || ($shippingids[$k] eq "" && $selected_any eq "N")}{assign var="selected_any" value="Y"}    

{assign var="cidev_shipping1" value=$s.shipping|trademark:"`$insert_trademark`"}
{if $s.shipping_time ne ""} 
{assign var="cidev_shipping" value="`$cidev_shipping1` - `$s.shipping_time`: $`$s.rate`"}
{/if}


			            checked="checked"{/if}{if $allow_cod} onclick="javascript: cidev_select_shipping('{$s.shippingid}'); display_cod({if $s.is_cod eq 'Y'}true{else}false{/if});"{else} onclick="javascript: cidev_select_shipping('{$s.shippingid}');"{/if} /></td>
				<td><label for="shippingid{$s.shippingid}">{$s.shipping|trademark:$insert_trademark}{if $s.shipping_time ne ""} - {$s.shipping_time}{/if}{if $config.Appearance.display_shipping_cost eq "Y"}: {include file="currency.tpl" value=$s.rate}{/if}</label></td>
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
{foreach from=$cart.products item=item key=key}
{literal}
{
id:"{/literal}{$item.productcode}{literal}", 
name:"{/literal}{$item.product|escape}{literal}",
price: {/literal}{$item.price}{literal},
quantity: {/literal}{$item.amount}{literal},
}
{/literal}
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
		{$lng.lbl_country}: {$userinfo.s_countryname}<br />
		{$lng.lbl_state}: {$userinfo.s_statename}<br />
		{$lng.lbl_city}: {$userinfo.s_city}<br />
		{$lng.lbl_zip_code}: {$userinfo.s_zipcode}<br /><br />
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
{foreach from=$cart.products item=item key=key}
{literal}
{
id:"{/literal}{$item.productcode}{literal}", 
name:"{/literal}{$item.product|escape}{literal}",
price: {/literal}{$item.price}{literal},
quantity: {/literal}{$item.amount}{literal},
}
{/literal}
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
</body>
</html>
