{* $Id: shipping_methods.tpl,v 1.7.2.4 2006/12/19 07:44:56 max Exp $ *}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
function cidev_save_shippingid(form, prefix){

        if (!form)
                return;

        if (prefix)
                var reg = new RegExp("^"+prefix, "");

	var shippingids_name_val = "";

        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].type == "radio" && (!prefix || form.elements[i].name.search(reg) == 0) && !form.elements[i].disabled){
			if (form.elements[i].checked == true){
	                        var shippingids_name = form.elements[i].name;
        	                var shippingids_val = form.elements[i].value;
				shippingids_name_val = shippingids_name_val + '&'+ shippingids_name + '=' + shippingids_val;
			}
                }
        }

        if (shippingids_name_val != ""){
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=save_shippingid' + shippingids_name_val;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
//							alert(shippingids_name_val);
                                                }else{
//                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_cart.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_save_shippingid(form, prefix)', 1000);
                        }
        }
}


function cidev_save_use_my_account(manufacturerid){

	var id = 'use_my_account_'+manufacturerid;
	var use_my_account = $('#'+id).val();

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=save_use_my_account&manufacturerid='+manufacturerid+'&use_my_account='+use_my_account;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
//                                                      alert(shippingids_name_val);
                                                }else{
//                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_cart.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_save_use_my_account(manufacturerid)', 1000);
                        }
}


function cidev_save_use_my_account_number(manufacturerid){

        var id = 'use_my_account_number_'+manufacturerid;
        var use_my_account_number = $('#'+id).val();

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=save_use_my_account_number&manufacturerid='+manufacturerid+'&use_my_account_number='+use_my_account_number;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
//                                                      alert(shippingids_name_val);
                                                }else{
//                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_cart.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_save_use_my_account_number(manufacturerid)', 1000);
                        }
}


function cidev_save_ship_by_shipping_method(manufacturerid){

        var id = 'ship_by_shipping_method_'+manufacturerid;
        var ship_by_shipping_method = $('#'+id).val();

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=save_ship_by_shipping_method&manufacturerid='+manufacturerid+'&ship_by_shipping_method='+ship_by_shipping_method;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
//                                                      alert(shippingids_name_val);
                                                }else{
//                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_cart.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_save_ship_by_shipping_method(manufacturerid)', 1000);
                        }
}


function cidev_save_t_use_my_account_number(manufacturerid){

        var id = 't_use_my_account_number_'+manufacturerid;
        var t_use_my_account_number = $('#'+id).val();

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=save_t_use_my_account_number&manufacturerid='+manufacturerid+'&t_use_my_account_number='+t_use_my_account_number;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
//                                                      alert(shippingids_name_val);
                                                }else{
//                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_cart.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_save_t_use_my_account_number(manufacturerid)', 1000);
                        }
}


function cidev_save_t_ship_by_shipping_method(manufacturerid){

        var id = 't_ship_by_shipping_method_'+manufacturerid;
        var t_ship_by_shipping_method = $('#'+id).val();

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = 'cidev_filter_mode=save_t_ship_by_shipping_method&manufacturerid='+manufacturerid+'&t_ship_by_shipping_method='+t_ship_by_shipping_method;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
//                                                      alert(shippingids_name_val);
                                                }else{
//                                                        cidev_Error('no_server', 'Y');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open('POST','cidev_cart.php',true);
                                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader('Connection','close');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout('cidev_save_t_ship_by_shipping_method(manufacturerid)', 1000);
                        }
}


{/literal}
//]]>
</script>

<table cellpadding="5" cellspacing="5" width="100%">

<tr>
<td valign="top" width="30%">
{include file="customer/main/subheader.tpl" title=$lng.lbl_shipping_address}
{if $userinfo}
{$userinfo.s_address}<br />
{if $userinfo.s_address_2}
{$userinfo.s_address_2}<br />
{/if}
{$userinfo.s_city}<br />
{$userinfo.s_statename}<br />
{$userinfo.s_countryname}<br />
{$userinfo.s_zipcode}
{else}
No data
{/if}

{if $login ne ""}
<br /><br />
{include file="buttons/modify.tpl" href="register.php?mode=update&action=cart&amp;paymentid=`$cart.paymentid`"}
{/if}

</td>
<td valign="top" width="70%">

{*  ERROR: no shipping methods available [begin]  }
{if $shipping_calc_error ne ""}
{$shipping_calc_service} {$lng.lbl_err_shipping_calc}<br />
<font class="ErrorMessage">{$shipping_calc_error}</font><br />
{/if}
{if $shipping eq "" and $need_shipping}
<font class="ErrorMessage">{$lng.lbl_no_shipping_for_location}</font><br />
<br />
{/if}
{  ERROR: no shipping methods available [end]  *}

{*  Select the shipping carrier [begin]  }
{if $login ne "" or $config.General.apply_default_country eq "Y" or $cart.shipping_cost gt 0}

{if $active_modules.UPS_OnLine_Tools and $config.Shipping.realtime_shipping eq "Y" and $config.Shipping.use_intershipper ne "Y" and $show_carriers_selector eq "Y"}
<font class="FormButton"><label for="">{$lng.lbl_shipping_carrier}:</label> </font>
<select name="selected_carrier" id="selected_carrier" onchange="javascript: self.location='cart.php?mode=checkout&amp;action=update&amp;selected_carrier='+this.options[this.selectedIndex].value;">
<option value="UPS">{$lng.lbl_ups_carrier}</option>
<option value="" selected="selected">{$lng.lbl_other_carriers}</option>
</select>
<br /><br />
{/if}

{/if}
{  Select the shipping carrier: [end]  *}

{*  Select the shipping method: [begin]  *}
{if $cart.shipping_groups ne ""}
{* if $config.Shipping.realtime_shipping eq "Y" && $config.Shipping.use_intershipper ne "Y" && (!$active_modules.UPS_OnLine_Tools || $show_carriers_selector ne 'Y' || $current_carrier ne 'UPS')}
{if $arb_account_used}
{$lng.txt_arb_account_checkout_note}
<br />
{elseif $use_airborne_account}
{$lng.lbl_arb_account}: <input type="text" name="arb_account" value="{$airborne_account}" /><br />
<br />
{/if}
{/if *}
{* $arb_account_used *}

{if $login ne "" || $config.General.apply_default_country eq "Y" || $cart.shipping_cost gt 0}
{foreach from=$cart.shipping_groups item=v key=k}
{assign var="found_any_shipping" value="N"}
{assign var="selected_any" value="N"}
{cycle values=''}
{* {assign var=delivery_text value=$lng.txt_for_fastlane_checkout_delivery|replace:"XX":"`$v.m_city`, `$v.m_state`, `$v.m_country`."|replace:"YY":"`$v.group_name`"} *}


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
		<td width="5"><input onclick="cidev_save_shippingid(document.cartform, 'shippingids');" type="radio" id="shippingid{$s.shippingid}" name="shippingids[{$k}]" value="{$s.shippingid}"{if $s.shippingid eq $cart.shippingids[$k] || ($cart.shippingids[$k] eq "" && $selected_any eq "N")}{assign var="selected_any" value="Y"} checked="checked"{/if}{if $allow_cod} onclick="javascript: display_cod({if $s.is_cod eq 'Y'}true{else}false{/if});"{/if} /></td>
	  {/if}
		<td>
			{if $s.shipping eq "_USE_MY_UPS_FEDEX_ACCOUNT_"}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

$(function(){

  $("#use_my_account_number_{/literal}{$k}{literal}").focusout(function(event){
	cidev_save_use_my_account_number('{/literal}{$k}{literal}');
        event.preventDefault();
  });

  $("#ship_by_shipping_method_{/literal}{$k}{literal}").focusout(function(event){
        cidev_save_ship_by_shipping_method('{/literal}{$k}{literal}');
        event.preventDefault();
  });

});

{/literal}
//]]>
</script>
<br />

<table cellspacing="0" cellpadding="0">
<tr>
<td>
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
<td>
<input type="text" id="use_my_account_number_{$k}" name="use_my_account_number[{$k}]" value="{$cart.use_my_account_number[$k]}" size="10" placeholder="{$lng.lbl_use_my_account_number}">
</td>
<td>
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
<label for="shippingid{$s.shippingid}">$5.00 handling fee will apply</label>
</td>
</tr>
</table>
<br />



                        {elseif $s.shipping eq "_USE_MY_TRUCKING_ACCOUNT_"}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}

$(function(){

  $("#t_use_my_account_number_{/literal}{$k}{literal}").focusout(function(event){
        cidev_save_t_use_my_account_number('{/literal}{$k}{literal}');
        event.preventDefault();
  });

  $("#t_ship_by_shipping_method_{/literal}{$k}{literal}").focusout(function(event){
        cidev_save_t_ship_by_shipping_method('{/literal}{$k}{literal}');
        event.preventDefault();
  });

});

{/literal}
//]]>
</script>
<br />

<table cellspacing="0" cellpadding="0">
<tr>
<td>
<label for="shippingid{$s.shippingid}">
Use my trucking account #
</label>
</td>
<td>
<span style="color: red;"> *</span><input type="text" id="t_use_my_account_number_{$k}" name="t_use_my_account_number[{$k}]" value="{$cart.t_use_my_account_number[$k]}" size="10" placeholder="123456">
</td>
<td>
<label for="shippingid{$s.shippingid}"> with </label>
</td>
<td>
<span style="color: red;"> *</span><input type="text" id="t_ship_by_shipping_method_{$k}" name="t_ship_by_shipping_method[{$k}]" value="{$cart.t_ship_by_shipping_method[$k]}" placeholder="AFB Freight"> trucking company:
</td>
</tr>

<tr>
<td colspan="5">
<label for="shippingid{$s.shippingid}">$5.00 handling fee will apply</label>
</td>
</tr>
</table>
<br />



			{elseif $s.shipping eq "_SHIP_BY_FASTEST_METHOD_"}
				<label for="shippingid{$s.shippingid}">
					Ship by the fastest possible shipping method upon your discretion and add shipping charge to my order's total
				</label>
			{else}
				<label for="shippingid{$s.shippingid}">
				{$s.shipping|trademark:$insert_trademark}{if $s.shipping_time ne ""} - {$s.shipping_time}{/if}{if $config.Appearance.display_shipping_cost eq "Y" and ($login ne "" or $config.General.apply_default_country eq "Y" or $cart.shipping_cost gt 0)}: {include file="currency.tpl" value=$s.rate}{/if}
				</label>
			{/if}
		</td>
	</tr>
	{if $s.warning ne ""}
	<tr>
	<td>&nbsp;</td>
	<td class="SmallText">{$s.warning}</td>
	</tr>
	{/if}
	</table>
	{/if}
	{/foreach}

{if $found_any_shipping ne "Y" and $need_shipping}
<font class="ErrorMessage">
{if $v.count_shipping_rates_for_canada eq "0" && $userinfo.s_country eq "CA"}
	{$lng.lbl_we_dont_ship_to_Canada_checkout}
	{assign var="disable_continue" value="Y"}
{else}
	{if $userinfo.s_country ne "US"}
		{$lng.lbl_we_ship_to_US_only}
	{else}
		{$lng.lbl_no_shipping_for_location}
	{/if}
{/if}
</font><br />
<br />
{/if}

<br /><br />
{/foreach}

{if $disable_continue eq "Y"}

<script type="text/javascript">
//<![CDATA[
{literal}
        function start_btn() {
                document.getElementById("continue_btn_able").style.display = 'none';
                document.getElementById("continue_btn_disable").style.display = '';
        }
{/literal}
//]]>
</script>
{else}
<script type="text/javascript">
//<![CDATA[
{literal}
        function start_btn() {

		{/literal}
	        {if $userinfo.s_country ne "US" && $cart.confirmation_of_responsibility ne "Y"}
		{literal}

	                document.getElementById("continue_btn_able").style.display = 'none';
        	        document.getElementById("continue_btn_disable").style.display = '';
			$("#id_confirmation_of_responsibility_checkbox").focus();

		{/literal}
        	{/if}
		{literal}
        }
{/literal}
//]]>
</script>

{/if}

{/if}
{else}
<input type="hidden" name="shippingid" value="0" />
{/if}
{*  Select the shipping method: [end]  *}
{include file="customer/main/dhl_ext_countries.tpl"}
</td>
</tr>
</table>
