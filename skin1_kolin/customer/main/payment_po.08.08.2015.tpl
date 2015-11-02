{* $Id: payment_po.tpl,v 1.14 2006/04/10 07:36:17 max Exp $ *}

{if $usertype ne "P" && $usertype ne "A"}

{include file="check_email_script.tpl"}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
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

        $('#purchase_manager_email').focusout(function() {

		var tmp_checkEmailAddress_val = "";
                tmp_checkEmailAddress_val = checkEmailAddress(document.checkout_form.purchase_manager_email, 'Y');

		if (tmp_checkEmailAddress_val){
	                document.getElementById("purchase_manager_email_verified").style.display = ''; 
        	        document.getElementById("purchase_manager_email_error").style.display = 'none';  
                }
                else {
                        document.getElementById("purchase_manager_email_verified").style.display = 'none';                      
                        document.getElementById("purchase_manager_email_error").style.display = '';  
                }
        });

        $('#accounts_payable_email').focusout(function() {

                var tmp_checkEmailAddress_val = "";
                tmp_checkEmailAddress_val = checkEmailAddress(document.checkout_form.accounts_payable_email, 'Y');

                if (tmp_checkEmailAddress_val){
                        document.getElementById("accounts_payable_email_verified").style.display = ''; 
                        document.getElementById("accounts_payable_email_error").style.display = 'none';  
                }
                else {
                        document.getElementById("accounts_payable_email_verified").style.display = 'none';                      
                        document.getElementById("accounts_payable_email_error").style.display = '';  
                }
        });

        $('#PO_Number').focusout(function() {
                cidev_check_verified_image_for_field('PO_Number');
        });

        $('#Company_name').focusout(function() {
                cidev_check_verified_image_for_field('Company_name');
        });

        $('#po_fax').focusout(function() {
                cidev_check_verified_image_for_field('po_fax');
        });
/*
        $('#Position').focusout(function() {
                cidev_check_verified_image_for_field('Position');
        });
*/
        $('#Name_of_purchaser').focusout(function() {
                cidev_check_verified_image_for_field('Name_of_purchaser');
        });

        $('#accounts_payable_full_name').focusout(function() {
                cidev_check_verified_image_for_field('accounts_payable_full_name');
        });

        $('#accounts_payable_phone').focusout(function() {
                cidev_check_verified_image_for_field('accounts_payable_phone');
        });

        $('#accounts_payable_fax').focusout(function() {
                cidev_check_verified_image_for_field('accounts_payable_fax');
        });

/*
        $('#accounts_payable_email').focusout(function() {
                cidev_check_verified_image_for_field('accounts_payable_email');
        });
*/

        $('#purchase_manager_phone').focusout(function() {
                cidev_check_verified_image_for_field('purchase_manager_phone');
        });
/*
        $('#purchase_manager_email').focusout(function() {
                cidev_check_verified_image_for_field('purchase_manager_email');
        });
*/

        $('#accounts_payable_phone_ext').focusout(function() {
                if ($('#accounts_payable_phone_ext').val() != ""){
                        document.getElementById("accounts_payable_phone_ext_verified").style.display = '';
                        document.getElementById("accounts_payable_phone_ext_error").style.display = 'none';
                } else {
                        document.getElementById("accounts_payable_phone_ext_error").style.display = 'none';
                        document.getElementById("accounts_payable_phone_ext_verified").style.display = 'none';
                }
        });

        $('#purchase_manager_phone_ext').focusout(function() {
                if ($('#purchase_manager_phone_ext').val() != ""){
                        document.getElementById("purchase_manager_phone_ext_verified").style.display = '';
                        document.getElementById("purchase_manager_phone_ext_error").style.display = 'none';
                } else {
                        document.getElementById("purchase_manager_phone_ext_error").style.display = 'none';
                        document.getElementById("purchase_manager_phone_ext_verified").style.display = 'none';
                }
        });

  });
{/literal}
//]]>
</script>
{/if}


<script type="text/javascript">
<!--
requiredFields[0] = ["PO_Number", "{$lng.lbl_po_number}"];
requiredFields[1] = ["Company_name", "{$lng.lbl_company_name}"];
requiredFields[2] = ["Name_of_purchaser", "{$lng.lbl_name_of_purchaser}"];
//requiredFields[3] = ["Position", "{$lng.lbl_position}"];
requiredFields[3] = ["accounts_payable_full_name", "{$lng.lbl_accounts_payable_full_name}"];
requiredFields[4] = ["po_fax", "Fax"];
requiredFields[5] = ["accounts_payable_phone", "{$lng.lbl_accounts_payable_phone}"];
requiredFields[6] = ["accounts_payable_fax", "{$lng.lbl_accounts_payable_fax}"];
requiredFields[7] = ["accounts_payable_email", "{$lng.lbl_accounts_payable_email}"];
requiredFields[8] = ["purchase_manager_phone", "{$lng.lbl_purchase_manager_phone}"];
requiredFields[9] = ["purchase_manager_email", "{$lng.lbl_purchase_manager_email}"];
-->
</script>

<script src="{$SkinDir}/check_zipcode.js" type="text/javascript"></script>

<table cellspacing="0" cellpadding="2" align="center" width="100%">

{if $hide_header ne "Y"}
<tr valign="middle">
<td height="20" colspan="3"><b>{$lng.lbl_po_information}</b><hr size="1" noshade="noshade" /></td>
</tr>
{/if}

<tr valign="middle">
<td class="cidev_padding_top" valign="top" align="right" width="48%">{$lng.lbl_po_number}
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_PO_Number}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" size="32" id="PO_Number" name="PO_Number" placeholder="{$lng.lbl_fill_in_examples_PO_Number}" onkeyup="cidev_check_field_if_empty('PO_Number')" />
</td>
{if $usertype eq "C"}
<td id="PO_Number_verified" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>
<td id="PO_Number_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}
</tr>
</table>
</td>
</tr>

<tr valign="middle">
<td class="cidev_padding_top" valign="top" align="right">Organization Name
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_Company_name}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td nowrap="nowrap" valign="top">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" size="32" id="Company_name" name="Company_name" value="{foreach from=$userinfo.additional_fields item=v}{if $v.section eq 'B' && $v.title eq "Company"}{$v.value}{/if}{/foreach}" placeholder="{$lng.lbl_fill_in_examples_Company_name}" onkeyup="cidev_check_field_if_empty('Company_name')" />
</td>
{if $usertype eq "C"}
<td id="Company_name_verified" valign="top" nowrap="nowrap" {foreach from=$userinfo.additional_fields item=v}{if $v.section eq 'B' && $v.title eq "Company"}{if $v.value eq ""}style="display: none;"{/if}{/if}{/foreach}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>
<td id="Company_name_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}
</tr>
</table>
</td>
</tr>

{*
<tr valign="middle">
<td class="cidev_padding_top" valign="top" align="right">Position
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_Position}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" size="32" id="Position" name="Position" placeholder="{$lng.lbl_fill_in_examples_Position}" onkeyup="cidev_check_field_if_empty('Position')" />
</td>
{if $usertype eq "C"}
<td id="Position_verified" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>
<td id="Position_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}
</tr>
</table>
</td>
</tr>
*}


	<tr valign="middle">
	<td height="20" colspan="3">
	<table cellspacing="0" class="SubHeader"><tr><td class="Green2">Purchasing manager</td></tr><tr><td class="SubHeaderLine"><img alt="" class="Spc" src="/skin1_kolin/images/spacer.gif"><br></td></tr></table>
	</td>
	</tr>

	<tr valign="middle">
	<td class="cidev_padding_top" valign="top" align="right">Full Name
	<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_Name_of_purchaser}</div>
	</td>
	<td valign="top"><font class="Star">*</font></td>
	<td valign="top" nowrap="nowrap">
	<table cellpadding="0" cellspacing="0">
	<tr>
	<td valign="top" nowrap="nowrap">
	<input type="text" size="32" id="Name_of_purchaser" name="Name_of_purchaser" value="{$userinfo.b_firstname}{if $userinfo.b_lastname ne ""} {$userinfo.b_lastname}{/if}" placeholder="{$lng.lbl_fill_in_examples_Name_of_purchaser}" onkeyup="cidev_check_field_name('Name_of_purchaser')" />
	</td>
	{if $usertype eq "C"}
	<td id="Name_of_purchaser_verified" valign="top" nowrap="nowrap" {if $userinfo.b_firstname eq ""}style="display: none;"{/if}>
	<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
	</td>
	<td id="Name_of_purchaser_error" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-error.png" alt="" />
	</td>
	{/if}
	</tr>
	</table>
	</td>
	</tr>

	<tr valign="middle">
	<td class="cidev_padding_top" valign="top" align="right" width="48%">{$lng.lbl_purchase_manager_phone}
	<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_purchase_manager_phone}</div>
	</td>
	<td valign="top"><font class="Star">*</font></td>
	<td valign="top" nowrap="nowrap">
	<table cellpadding="0" cellspacing="0">
	<tr>
	<td valign="top" nowrap="nowrap">
	<input type="text" size="32" id="purchase_manager_phone" name="purchase_manager_phone" placeholder="{$lng.lbl_fill_in_examples_purchase_manager_phone}" onkeyup="cidev_check_field_phone('purchase_manager_phone')" value="{$userinfo.phone}" />
	</td>
	{if $usertype eq "C"}
	<td>
	<table width="30" cellspacing="0" cellpadding="0">
	<tr>

	<td id="purchase_manager_phone_verified" valign="top" nowrap="nowrap" {if $userinfo.phone_ext eq ""}style="display: none;"{/if}>
	<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
	</td>
	<td id="purchase_manager_phone_error" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-error.png" alt="" />
	</td>

	</tr>
	</table>
	</td>
	{/if}

	{* --------------- *}
	<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_phone_ext}
	{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_phone_ext}</div>{/if}
	</td>
	<td valign="top">{if $default_fields.phone_ext.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
	<td valign="top" nowrap="nowrap">
	<input type="text" id="purchase_manager_phone_ext" name="purchase_manager_phone_ext" size="6" maxlength="6" value="{$userinfo.phone_ext}" placeholder="{$lng.lbl_fill_in_examples_phone_ext}" onkeyup="cidev_check_field_phone_ext('purchase_manager_phone_ext')" />
	</td>

	{if $usertype eq "C"}
	<td id="purchase_manager_phone_ext_verified" valign="top" nowrap="nowrap" {if $userinfo.phone_ext eq ""}style="display: none;"{/if}>
	<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
	</td>
	<td id="purchase_manager_phone_ext_error" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-error.png" alt="" />
	</td>
	{/if}
	{* --------------- *}

	</tr>
	</table>
	</td>
	</tr>

	<tr valign="middle">
	<td class="cidev_padding_top" valign="top" align="right">Fax
	<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_po_fax}</div>
	</td>
	<td valign="top"><font class="Star">*</font></td>
	<td nowrap="nowrap" valign="top">
	<table cellpadding="0" cellspacing="0">
	<tr>
	<td valign="top" nowrap="nowrap">
	<input type="text" size="32" id="po_fax" name="po_fax" value="" placeholder="{$lng.lbl_fill_in_examples_po_fax}" onkeyup="cidev_check_field_phone('po_fax');" />
	</td>
	<td id="po_fax_verified" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
	</td>
	<td id="po_fax_error" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-error.png" alt="" />
	</td>
	</tr>
	</table>
	</td>
	</tr>

        <tr valign="middle">
        <td class="cidev_padding_top" valign="top" align="right" width="48%">{$lng.lbl_purchase_manager_email}
        <div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_purchase_manager_email}</div>
        </td>
        <td valign="top"><font class="Star">*</font></td>
        <td valign="top" nowrap="nowrap">
        <table cellpadding="0" cellspacing="0">
        <tr>
        <td valign="top" nowrap="nowrap">
        <input type="text" size="32" id="purchase_manager_email" name="purchase_manager_email" placeholder="{$lng.lbl_fill_in_examples_purchase_manager_email}" {* onkeyup="cidev_check_field_if_empty('purchase_manager_email')" *} value="{$userinfo.email}" />
        </td>
        {if $usertype eq "C"}
        <td id="purchase_manager_email_verified" valign="top" nowrap="nowrap" {if $userinfo.email eq ""}style="display: none;"{/if}>
        <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
        </td>
        <td id="purchase_manager_email_error" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-error.png" alt="" />
        </td>
        {/if}
        </tr>
        </table>
        </td>
        </tr>



        <tr valign="middle">
        <td height="20" colspan="3">
        <table cellspacing="0" class="SubHeader"><tr><td class="Green2">Accounts payable</td></tr><tr><td class="SubHeaderLine"><img alt="" class="Spc" src="/skin1_kolin/images/spacer.gif"><br></td></tr></table>
        </td>
        </tr>

        <tr valign="middle">
        <td class="cidev_padding_top" valign="top" align="right" width="48%">{$lng.lbl_accounts_payable_full_name}
        <div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_accounts_payable_full_name}</div>
        </td>
        <td valign="top"><font class="Star">*</font></td>
        <td valign="top" nowrap="nowrap">
        <table cellpadding="0" cellspacing="0">
        <tr>
        <td valign="top" nowrap="nowrap">
        <input type="text" size="32" id="accounts_payable_full_name" name="accounts_payable_full_name" placeholder="{$lng.lbl_fill_in_examples_accounts_payable_full_name}" onkeyup="cidev_check_field_if_empty('accounts_payable_full_name')" />
        </td>
        {if $usertype eq "C"}
        <td id="accounts_payable_full_name_verified" valign="top" nowrap="nowrap" style="display: none;" >
        <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
        </td>
        <td id="accounts_payable_full_name_error" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-error.png" alt="" />
        </td>
        {/if}
        </tr>
        </table>
        </td>
        </tr>

        <tr valign="middle">
        <td class="cidev_padding_top" valign="top" align="right" width="48%">{$lng.lbl_accounts_payable_phone}
        <div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_accounts_payable_phone}</div>
        </td>
        <td valign="top"><font class="Star">*</font></td>
        <td valign="top" nowrap="nowrap">
        <table cellpadding="0" cellspacing="0">
        <tr>
        <td valign="top" nowrap="nowrap">
        <input type="text" size="32" id="accounts_payable_phone" name="accounts_payable_phone" placeholder="{$lng.lbl_fill_in_examples_accounts_payable_phone}" onkeyup="cidev_check_field_phone('accounts_payable_phone')" />
        </td>
        {if $usertype eq "C"}
	<td>
	<table width="30" cellspacing="0" cellpadding="0">
	<tr>

        <td id="accounts_payable_phone_verified" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
        </td>
        <td id="accounts_payable_phone_error" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-error.png" alt="" />
        </td>

	</tr>
	</table>
	</td>

        {/if}

	{* --------------- *}
	<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_phone_ext}
	{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_phone_ext}</div>{/if}
	</td>
	<td valign="top">{if $default_fields.phone_ext.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
	<td valign="top" nowrap="nowrap">
	<input type="text" id="accounts_payable_phone_ext" name="accounts_payable_phone_ext" size="6" maxlength="6" placeholder="{$lng.lbl_fill_in_examples_phone_ext}" onkeyup="cidev_check_field_phone_ext('accounts_payable_phone_ext')" />
	</td>

	{if $usertype eq "C"}
	<td id="accounts_payable_phone_ext_verified" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
	</td>
	<td id="accounts_payable_phone_ext_error" valign="top" nowrap="nowrap" style="display: none;">
	<img src="{$ImagesDir}/checkmark-error.png" alt="" />
	</td>
	{/if}
	{* --------------- *}

        </tr>
        </table>
        </td>
        </tr>

        <tr valign="middle">
        <td class="cidev_padding_top" valign="top" align="right" width="48%">{$lng.lbl_accounts_payable_fax}
        <div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_accounts_payable_fax}</div>
        </td>
        <td valign="top"><font class="Star">*</font></td>
        <td valign="top" nowrap="nowrap">
        <table cellpadding="0" cellspacing="0">
        <tr>
        <td valign="top" nowrap="nowrap">
        <input type="text" size="32" id="accounts_payable_fax" name="accounts_payable_fax" placeholder="{$lng.lbl_fill_in_examples_accounts_payable_fax}" onkeyup="cidev_check_field_phone('accounts_payable_fax')" />
        </td>
        {if $usertype eq "C"}
        <td id="accounts_payable_fax_verified" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
        </td>
        <td id="accounts_payable_fax_error" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-error.png" alt="" />
        </td>
        {/if}
        </tr>
        </table>
        </td>
        </tr>

        <tr valign="middle">
        <td class="cidev_padding_top" valign="top" align="right" width="48%">{$lng.lbl_accounts_payable_email}
        <div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_accounts_payable_email}</div>
        </td>
        <td valign="top"><font class="Star">*</font></td>
        <td valign="top" nowrap="nowrap">
        <table cellpadding="0" cellspacing="0">
        <tr>
        <td valign="top" nowrap="nowrap">
        <input type="text" size="32" id="accounts_payable_email" name="accounts_payable_email" placeholder="{$lng.lbl_fill_in_examples_accounts_payable_email}" {* onkeyup="cidev_check_field_if_empty('accounts_payable_email')" *} />
        </td>
        {if $usertype eq "C"}
        <td id="accounts_payable_email_verified" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-verified.png" alt="" />
        </td>
        <td id="accounts_payable_email_error" valign="top" nowrap="nowrap" style="display: none;">
        <img src="{$ImagesDir}/checkmark-error.png" alt="" />
        </td>
        {/if}
        </tr>
        </table>
        </td>
        </tr>

</table>
