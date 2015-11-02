{* $Id: register_contact_info.tpl,v 1.10 2005/11/17 06:55:39 max Exp $ *}

{if $usertype ne "P" && $usertype ne "A"}
<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
  $(document).ready(function() {  

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
		cidev_check_verified_image_for_field('phone');
        });

        $('#firstname').focusout(function() {
		cidev_check_verified_image_for_field('firstname');
        });

	$('#company').focusout(function() {
		cidev_check_verified_image_for_field('company');
	});
  });
{/literal}
//]]>
</script>
{/if}


{if $is_areas.C eq 'Y'}
{if $hide_header eq ""}
<tr>
<td height="20" colspan="3"><font class="RegSectionTitle">{$lng.lbl_contact_information}</font><hr size="1" noshade="noshade" /></td>
</tr>
{/if}

{*
<tr>
<td colspan="3">{$lng.txt_newbie_registration_bottom_small}</td>
</tr>
*}

{if $is_areas.P eq 'Y'}
{if $default_fields.title.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_title}</td>
<td valign="top">{if $default_fields.title.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<select name="title" id="title">
{include file="main/title_selector.tpl" field=$userinfo.titleid}
</select>
</td>
</tr>
{/if}
{if $default_fields.firstname.avail eq 'Y'}
<tr>
<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_first_name}
{if $usertype eq "C" && $lng.lbl_CHECKOUT_FIELD_DESCRIPTION_first_name ne ""}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_first_name}</div>{/if}
</td>
<td valign="top">{if $default_fields.firstname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="firstname" name="firstname" size="32" maxlength="32" value="{$userinfo.firstname}" placeholder="{$lng.lbl_fill_in_examples_firstname}" onkeyup="cidev_check_field_name('firstname')" />
</td>
{if $usertype eq "C"}
<td id="firstname_verified" valign="top" nowrap="nowrap" {if $userinfo.firstname eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="firstname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.firstname eq "" && $default_fields.firstname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.lastname.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_last_name}</td>
<td valign="top">{if $default_fields.lastname.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<input type="text" id="lastname" name="lastname" size="32" maxlength="32" value="{$userinfo.lastname}" onkeyup="cidev_check_field_name('lastname')" />
{if $reg_error ne "" and $userinfo.lastname eq "" && $default_fields.lastname.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.company.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_company}</td>
<td valign="top">{if $default_fields.company.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="company" name="company" size="32" maxlength="255" value="{$userinfo.company}" placeholder="{$lng.lbl_fill_in_examples_Company_name}" onkeyup="cidev_check_field('company')" />
</td>
{if $usertype eq "C"}
<td id="company_verified" valign="top" nowrap="nowrap" {if $userinfo.firstname eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="company_error" valign="top" nowrap="nowrap" {if $userinfo.company eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.company eq "" && $default_fields.company.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.ssn.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_ssn}</td>
<td valign="top">{if $default_fields.ssn.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<input type="text" id="ssn" name="ssn" size="32" maxlength="32" value="{$userinfo.ssn}" onkeyup="cidev_check_field('ssn')" />
{if $reg_error ne "" and $userinfo.ssn eq "" && $default_fields.ssn.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.tax_number.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_tax_number}</td>
<td valign="top">{if $default_fields.tax_number.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
{if $userinfo.tax_exempt ne "Y" or $config.Taxes.allow_user_modify_tax_number eq "Y" or $usertype eq "A" or $usertype eq "P"}
<input type="text" id="tax_number" name="tax_number" size="32" maxlength="32" value="{$userinfo.tax_number}" onkeyup="cidev_check_field('tax_number')" />
{if $reg_error ne "" and $userinfo.tax_number eq "" && $default_fields.tax_number.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
{else}
{$userinfo.tax_number}
{/if}
</td>
</tr>
{/if}
{if $config.Taxes.enable_user_tax_exemption eq 'Y'}
{if (($userinfo.usertype eq "C" or $smarty.get.usertype eq "C") and $userinfo.tax_exempt eq "Y") or ($usertype eq "A" or $usertype eq "P")}
<tr>
<td valign="top" align="right">{$lng.lbl_tax_exemption}</td>
<td valign="top">&nbsp;</td>
<td valign="top" nowrap="nowrap">
{if $usertype eq "A" or $usertype eq "P"} 
<input type="checkbox" id="tax_exempt" name="tax_exempt" value="Y"{if $userinfo.tax_exempt eq "Y"} checked="checked"{/if} />
{elseif $userinfo.tax_exempt eq "Y"}
{$lng.txt_tax_exemption_assigned}
{/if}
</td>
</tr>
{/if}
{/if}
{if $usertype eq "A" or $usertype eq "P"}
<tr>
<td valign="top" align="right">{$lng.lbl_referred_by}</td>
<td valign="top"></td>
<td nowrap="nowrap">
{if $userinfo.referer}
<a href="{$userinfo.referer}">{$userinfo.referer}</a>
{else}
{$lng.lbl_unknown}
{/if}
</td>
</tr>
{/if}
{include file="main/register_additional_info.tpl" section="P"}
{/if}

{if $default_fields.phone.avail eq 'Y'}
<tr>
<td class="cidev_padding_top" valign="top" align="right">{$lng.lbl_phone}
{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_phone}</div>{/if}
</td>
<td valign="top">{if $default_fields.phone.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="phone" name="phone" size="32" maxlength="32" value="{$userinfo.phone}" placeholder="{$lng.lbl_fill_in_examples_phone}" onkeyup="cidev_check_field_phone('phone')" />
</td>

{if $usertype eq "C"}
<td id="phone_verified" valign="top" nowrap="nowrap" {if $userinfo.phone eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>
<td id="phone_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
{/if}

</tr>
</table>

{if $reg_error ne "" and $userinfo.phone eq "" and $default_fields.phone.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>

{/if}
{if $default_fields.email.avail eq 'Y'}
<tr>
<td valign="top" align="right" class="cidev_padding_top">{$lng.lbl_email}
{if $usertype eq "C"}<div class="cidev_checkout_descr">{$lng.lbl_CHECKOUT_FIELD_DESCRIPTION_email}</div>{/if}
</td>
<td valign="top">{if $default_fields.email.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="email" name="email" size="32" maxlength="128" value="{$userinfo.email}" placeholder="{$lng.lbl_fill_in_examples_email}" {* onblur="javascript: $('#email_note').hide();" onfocus="javascript: cidev_showNote('email_note', this);" *} />
</td>

{if $usertype eq "C"}
<td id="email_verified" valign="top" nowrap="nowrap" {if $userinfo.email eq ""}style="display: none;"{/if}>
<img src="{$ImagesDir}/checkmark-verified.png" alt="" />
</td>

<td id="email_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="{$ImagesDir}/checkmark-error.png" alt="" />
</td>
<td id="email_error_text" valign="top" style="display: none;">
<div id="email_note" class="cidev_NoteBox">{$lng.txt_email_invalid}</div>
</td>
{/if}

</tr>
</table>

{*
<div id="email_note" class="cidev_NoteBox" style="display: none;">{$lng.txt_email_note}<br /></div>
{if $emailerror ne "" or ($reg_error ne "" and $userinfo.email eq "" and $default_fields.email.required eq 'Y')}<font class="Star">&lt;&lt;</font>{/if}
*}
</td>
</tr>
{/if}
{if $default_fields.fax.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_fax}</td>
<td valign="top">{if $default_fields.fax.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<input type="text" id="fax" name="fax" size="32" maxlength="128" value="{$userinfo.fax}" />
{if $reg_error ne "" and $userinfo.fax eq "" and $default_fields.fax.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{if $default_fields.url.avail eq 'Y'}
<tr>
<td valign="top" align="right">{$lng.lbl_web_site}</td>
<td valign="top">{if $default_fields.url.required eq 'Y'}<font class="Star">*</font>{else}&nbsp;{/if}</td>
<td valign="top" nowrap="nowrap">
<input type="text" id="url" name="url" size="32" maxlength="128" value="{$userinfo.url}" />
{if $reg_error ne "" and $userinfo.url eq "" and $default_fields.url.required eq 'Y'}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>
{/if}
{include file="main/register_additional_info.tpl" section="C"}
{/if}

