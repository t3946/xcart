{* $Id: payment_po.tpl,v 1.14 2006/04/10 07:36:17 max Exp $ *}

{if $usertype ne "P" && $usertype ne "A"}
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

        $('#PO_Number').focusout(function() {
                cidev_check_verified_image_for_field('PO_Number');
        });

        $('#Company_name').focusout(function() {
                cidev_check_verified_image_for_field('Company_name');
        });

        $('#po_fax').focusout(function() {
                cidev_check_verified_image_for_field('po_fax');
        });

        $('#Position').focusout(function() {
                cidev_check_verified_image_for_field('Position');
        });

        $('#Name_of_purchaser').focusout(function() {
                cidev_check_verified_image_for_field('Name_of_purchaser');
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
requiredFields[3] = ["Position", "{$lng.lbl_position}"];
requiredFields[4] = ["po_fax", "Fax"];
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
<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_company_name}
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
<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_name_of_purchaser}
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

</table>
