{* $Id: payment_po.tpl,v 1.14 2006/04/10 07:36:17 max Exp $ *}
<script type="text/javascript">
<!--
requiredFields[0] = ["PO_Number", "{$lng.lbl_po_number}"];
requiredFields[1] = ["Company_name", "{$lng.lbl_company_name}"];
requiredFields[2] = ["Name_of_purchaser", "{$lng.lbl_name_of_purchaser}"];
requiredFields[3] = ["Position", "{$lng.lbl_position}"];
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
<input type="text" size="32" id="PO_Number" name="PO_Number" placeholder="{$lng.lbl_fill_in_examples_PO_Number}" onkeyup="cidev_check_field_if_empty('PO_Number')" />
</td>
</tr>

<tr valign="middle">
<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_company_name}
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_Company_name}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td nowrap="nowrap" valign="top">
<input type="text" size="32" id="Company_name" name="Company_name" value="{foreach from=$userinfo.additional_fields item=v}{if $v.section eq 'B' && $v.title eq "Company"}{$v.value}{/if}{/foreach}" placeholder="{$lng.lbl_fill_in_examples_Company_name}" onkeyup="cidev_check_field_if_empty('Company_name')" />
</td>
</tr>

{*
<tr valign="middle">
<td height="20" colspan="3"><hr size="1" /></td>
</tr>
*}

<tr valign="middle">
<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_name_of_purchaser}
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_Name_of_purchaser}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td valign="top" nowrap="nowrap">
<input type="text" size="32" id="Name_of_purchaser" name="Name_of_purchaser" value="{$userinfo.b_firstname}{if $userinfo.b_lastname ne ""} {$userinfo.b_lastname}{/if}" placeholder="{$lng.lbl_fill_in_examples_Name_of_purchaser}" onkeyup="cidev_check_field_name('Name_of_purchaser')" />
</td>
</tr>

<tr valign="middle">
<td class="cidev_padding_top" valign="top" align="right">Position
<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_Position}</div>
</td>
<td valign="top"><font class="Star">*</font></td>
<td valign="top" nowrap="nowrap">
<input type="text" size="32" id="Position" name="Position" placeholder="{$lng.lbl_fill_in_examples_Position}" onkeyup="cidev_check_field_if_empty('Position')" />
</td>
</tr>

</table>
